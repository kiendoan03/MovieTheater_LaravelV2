# Token Rotation & Persistent Login — Thiết kế & Triển khai

## Tổng quan kiến trúc

```
Client (Cookie)
 ├── access_token   (JWT, HttpOnly, SameSite=Strict, 60 phút)
 └── refresh_token  (random 64 chars hashed SHA-256, HttpOnly, SameSite=Strict, 30 ngày)
                           │
                           ▼
                    DB: refresh_tokens
                    ┌────────────────────────────────┐
                    │ id, token (sha256), account_id │
                    │ expires_at, is_revoked          │
                    │ revoked_at, replaced_by_token  │ ← audit trail
                    └────────────────────────────────┘
```

## Middleware Pipeline (mỗi API request protected)

```
Request
  → EncryptCookies        (giải mã cookie)
  → JWTFromCookie         (copy access_token cookie → Authorization header)
  → SilentRefresh   ★ MỚI (nếu token sắp hết hạn → tự rotate, set cookie mới)
  → auth:api              (xác thực JWT)
  → Controller
  → Response (kèm cookie mới nếu đã silent refresh)
```

## Token Rotation Flow

### 1. Login / Register
```
POST /api/auth/process-login
→ Tạo access_token (JWT, 60 phút)
→ Tạo refresh_token (random, lưu hash vào DB, 30 ngày)
→ Set 2 HttpOnly cookie
```

### 2. Silent Refresh (transparent, frontend không cần làm gì)
```
Mỗi API request:
  IF access_token còn ≤ 5 phút (hoặc đã hết hạn) AND có refresh_token hợp lệ:
    → Revoke refresh_token cũ (ghi replaced_by_token)
    → Phát access_token MỚI + refresh_token MỚI
    → Gắn vào Authorization header của request hiện tại
    → Set 2 cookie mới vào response
    → Set header X-Token-Refreshed: true (frontend có thể dùng để sync state)
```

### 3. Manual Refresh (fallback, frontend gọi khi nhận 401)
```
POST /api/auth/refresh
  Cookie: refresh_token=<raw>
→ Validate → Rotation → Response + cookie mới
```

### 4. Logout
```
POST /api/auth/logout
→ Revoke refresh_token trong DB
→ Invalidate JWT access_token (blacklist)
→ Clear cả 2 cookie
```

## Reuse Attack Detection ★ BẢO MẬT

> **Kịch bản tấn công:** Kẻ tấn công đánh cắp refresh_token của nạn nhân. Nạn nhân dùng token đó → rotation xảy ra. Kẻ tấn công cũng thử dùng token cũ đó.

```
Kẻ tấn công gọi /refresh với token đã bị revoke
  → Server phát hiện is_revoked = TRUE
  → REVOKE TOÀN BỘ refresh token của account đó
  → Force logout tất cả thiết bị
  → Response 401 + xóa cookie
  → Nạn nhân bị đăng xuất → nhận ra có vấn đề → đổi mật khẩu
```

## Files đã thêm/sửa

| File | Thay đổi |
|------|----------|
| `app/Http/Controllers/TokenController.php` | Reuse attack detection, replaced_by_token tracking |
| `app/Http/Middleware/SilentRefresh.php` | ★ MỚI — Tự động rotate token sắp hết hạn |
| `app/Console/Commands/CleanExpiredRefreshTokens.php` | ★ MỚI — Dọn DB hàng đêm |
| `app/Console/Kernel.php` | Schedule cleanup lúc 3:00 AM hàng ngày |
| `app/Http/Kernel.php` | Đăng ký alias `silent.refresh` |
| `app/Models/RefreshToken.php` | Fix import `Attribute`, `Builder` |
| `routes/api.php` | Thêm `silent.refresh` vào protected group |

## Cấu hình quan trọng

```env
JWT_TTL=60          # access token sống 60 phút
                    # silent refresh khi còn ≤ 5 phút (SilentRefresh::REFRESH_THRESHOLD_MINUTES)

# refresh_token: REFRESH_TTL_DAYS = 30 ngày (hardcode trong TokenController)
```

> [!TIP]
> Nếu muốn thay đổi ngưỡng silent refresh, chỉnh `REFRESH_THRESHOLD_MINUTES` trong `SilentRefresh.php`. Nên đặt bằng `JWT_TTL / 12` (tức 5 phút với TTL = 60 phút).

## Chạy scheduler (production)

```bash
# Windows Task Scheduler hoặc crontab (Linux)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1

# Test thủ công
php artisan tokens:clean-expired
php artisan tokens:clean-expired --days=3   # Xóa token cũ hơn 3 ngày
```

## Frontend — Không cần làm gì thêm!

Với cookie-based approach, frontend hoàn toàn **transparent**. Tuy nhiên nếu muốn sync state:

```javascript
// Sau mỗi API response, kiểm tra header
if (response.headers.get('X-Token-Refreshed') === 'true') {
    // Token đã được làm mới tự động
    // Có thể cập nhật expiry timer trong local state nếu cần
    console.log('Token silently refreshed');
}
```
