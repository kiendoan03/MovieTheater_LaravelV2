# 🎬 MovieTheater Laravel V2 - Ticket Booking & PayOs Payment

## 📋 Giới thiệu

Hệ thống quản lý rạp phim với chức năng **đặt vé tại quầy cho nhân viên** và **thanh toán qua PayOs QR**. 

**Tính năng chính:**
- ✅ Giao diện đặt vé 3 bước (chọn phim → lịch chiếu → ghế)
- ✅ Thanh toán 2 phương thức: Tiền mặt + PayOs QR
- ✅ Chuẩn bị sẵn cho realtime seat updates (Laravel Reverb)
- ✅ Webhook xử lý từ PayOs khi thanh toán xong

---

## ✅ Những Gì Đã Hoàn Thành

### 1. **Backend - Ticket Booking Controller**
- `TicketBookingController` - Quản lý toàn bộ flow đặt vé
- `createTicketCash()` - Tạo vé thanh toán tiền mặt (trực tiếp Reserved)
- `initPaymentPayOs()` - Khởi tạo QR code PayOs
- `checkPaymentStatus()` - Kiểm tra trạng thái thanh toán

### 2. **PayOs Integration**
- `PayOsService` - API wrapper cho PayOs
  - `createQRCode()` - Tạo QR code từ PayOs
  - `getTransactionStatus()` - Kiểm tra status giao dịch
- `PayOsWebhookController` - Xử lý callback từ PayOs
  - Verify signature HMAC-SHA256
  - Update Booking status thành Reserved

### 3. **Models & Database**
- `Ticket` - Lưu thông tin vé (code, final_price, staff_id)
- `Booking` - Lưu trạng thái từng ghế (status: 0=Available, 1=Booked, 2=Reserved)
- `Seat` - Ghế trong rạp
- `Schedule` - Lịch chiếu

### 4. **Views**
- `seat-layout.blade.php` - Giao diện chọn ghế + thanh toán
- `payment-status.blade.php` - Trang hiển thị kết quả thanh toán

### 5. **Events & Broadcasting** (chuẩn bị cho Realtime)
- `PaymentCompleted` - Event broadcast khi thanh toán xong
- `SeatStatusChanged` - Event broadcast khi ghế thay đổi trạng thái

---

## 🔄 Logic Flow Hiện Tại

### **Tiền Mặt**
```
1. Staff chọn ghế → Booking.status = 1 (Booked)
2. Click "Thanh toán bằng tiền mặt"
3. Tạo Ticket + Cập nhật Booking.status = 2 (Reserved)
4. ✅ Vé hoàn thành
```

### **PayOs QR**
```
1. Staff chọn ghế → Booking.status = 1 (Booked)
2. Click "Thanh toán QR"
3. Tạo Ticket + Gọi PayOs API → gen order code (= Ticket.code)
4. Hiển thị QR code modal
5. Chờ webhook từ PayOs (hoặc polling fallback)
6. Webhook đến → Verify signature → Update Booking.status = 2 (Reserved)
7. ✅ Vé hoàn thành
```

---

## 📊 Cấu Trúc Database

### **Tickets Table**
```sql
id, code, final_price, customer_id, staff_id, created_at, updated_at
```
- `code` - Mã vé (format: TKxxxxxxxx) - **Dùng làm order code cho PayOs**
- `final_price` - Tổng tiền vé

### **Bookings Table**
```sql
id, schedule_id, seat_id, ticket_id, staff_id, status, created_at, updated_at
```
- `status` - **Trạng thái thanh toán**:
  - `0` = Available (ghế trống)
  - `1` = Booked (chưa thanh toán / staff đang chọn)
  - `2` = Reserved (đã thanh toán)

---

## 🛠️ Cấu Hình Hiện Tại

### **.env - PayOs Credentials**
```env
PAYOS_CLIENT_ID=472dd1bb-cdbf-4ec5-9654-4f9a2b83e946
PAYOS_API_KEY=5e333f9a-4ef3-419a-bcfc-294889f15519
PAYOS_CHECKSUM_KEY=e1155517ede05a4b6005f5131e3f28ee35c2743f2ca8855e2706ff5dc24541a4
PAYOS_WEBHOOK_URL=http://localhost/webhook/payos
```

### **config/auth.php**
```php
'guards' => [
    'api' => ['driver' => 'jwt', 'provider' => 'accounts'],
    'staff' => ['driver' => 'session', 'provider' => 'staff'],
]

'providers' => [
    'accounts' => ['model' => \App\Models\Account::class],
    'staff' => ['model' => \App\Models\Staff::class],
]
```

### **routes/api.php** - PayOs Endpoints
```
POST /api/ticket-booking/create-ticket-cash
POST /api/ticket-booking/init-payment-payos
GET  /api/ticket-booking/check-payment-status/{ticketCode}
GET  /api/ticket-booking/schedule-seats/{scheduleId}
POST /api/ticket-booking/update-seat-status
```

### **routes/web.php** - Webhook
```
POST /webhook/payos  (PUBLIC - không cần auth)
GET  /Admin/TicketBooking/payment-status/{ticketCode}
```

---

## 📦 Cần Install Thêm Gì?

### **1. Laravel Reverb** (cho Realtime WebSocket)
```bash
composer require laravel/reverb
php artisan reverb:install
```

### **2. Laravel Echo** (Client-side - dùng CDN)
```html
<!-- Đã add vào seat-layout.blade.php -->
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.1/dist/echo.iife.js"></script>
```

### **3. Node Packages** (Optional, nếu dùng build system)
```bash
npm install
npm run dev  # Build CSS/JS
```

### **Không cần install thêm gì khác** - Tất cả đã sẵn!

---

## 🚀 Các Bước Tiếp Theo Cần Làm

### **Priority 1: Setup Realtime (Ngay)**

**1. Install & Setup Reverb**
```bash
composer require laravel/reverb
php artisan reverb:install
```

**2. Config .env**
```env
BROADCAST_DRIVER=reverb
REVERB_APP_ID=1
REVERB_APP_KEY=default
REVERB_APP_SECRET=default
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
```

**3. Start Reverb Server** (trong terminal riêng)
```bash
php artisan reverb:start
```

**4. Update seat-layout.blade.php** - Integrate Laravel Echo
- Khởi tạo Echo connection
- Subscribe tới `schedule.{scheduleId}` channel
- Listen `SeatStatusChanged` event
- Thay thế polling bằng real-time updates

---

### **Priority 2: Test & Verify**

**1. Test Cash Payment**
```
→ Open: http://localhost:8000/Admin/TicketBooking
→ Chọn phim → lịch chiếu → ghế
→ Click "Thanh toán tiền mặt"
→ Kiểm tra: Ticket được tạo, Booking.status = 2
```

**2. Test PayOs Webhook** (Postman)
```
POST http://localhost/webhook/payos

Headers:
  X-Signature: {hmac-sha256-signature}
  Content-Type: application/json

Body:
{
  "code": "00",
  "data": {
    "orderCode": "TK1234567890",
    "status": "PAID",
    "amount": 150000
  }
}
```

**3. Test Realtime** (2 browser windows)
```
Window 1: Select seats → Watch Window 2 update instantly
```

---

### **Priority 3: Production Checklist**

- [ ] Setup SSL/TLS cho WebSocket (wss://)
- [ ] Configure CORS cho production domain
- [ ] Move PayOs credentials sang `.env` production
- [ ] Setup Reverb server (Docker / Standalone / Platform)
- [ ] Configure webhook endpoint (public URL)
- [ ] Setup monitoring & logging
- [ ] Database backups
- [ ] Load testing realtime connections

---

## 📁 File Structure

```
app/
├── Services/
│   └── PayOsService.php              # PayOs API integration
├── Events/
│   ├── PaymentCompleted.php          # Broadcast payment done
│   └── SeatStatusChanged.php          # Broadcast seat updates
├── Http/Controllers/
│   ├── TicketBookingController.php    # Main booking logic
│   └── PayOsWebhookController.php     # Webhook handler
└── Models/
    ├── Ticket.php
    ├── Booking.php
    ├── Seat.php
    └── Schedule.php

config/
├── payos.php                          # PayOs config
└── auth.php                           # Auth guards (staff + api)

resources/views/admin/TicketBooking/
├── seat-layout.blade.php              # Main booking UI
└── payment-status.blade.php           # Payment result

routes/
├── api.php                            # Ticket booking APIs
└── web.php                            # Webhook + payment status
```

---

## 🧪 Test Commands

```bash
# Test Reverb connection
php artisan tinker
> broadcast(new App\Events\SeatStatusChanged(1, 1, 0, 1))->toOthers();

# Test PayOs service
> $payos = app('App\Services\PayOsService');
> $payos->createQRCode('TEST123', 100000, 'Test', 'http://localhost');

# View logs
tail -f storage/logs/laravel.log
```

---

## 🔐 Security

✅ HMAC-SHA256 signature verification trên tất cả webhook
✅ JWT authentication trên API endpoints
✅ Staff session validation
✅ Ticket.code unique constraint
✅ CSRF protection

---

## 📞 API Documentation

### **Ticket Booking Endpoints**

```
GET /api/ticket-booking/movies
→ Lấy danh sách phim đang chiếu

GET /api/ticket-booking/schedules/{movieId}
→ Lấy lịch chiếu theo phim

GET /api/ticket-booking/schedule-seats/{scheduleId}
→ Lấy danh sách ghế + trạng thái

POST /api/ticket-booking/update-seat-status
Body: { schedule_id, seat_id, status }
→ Update ghế (real-time broadcast)

POST /api/ticket-booking/create-ticket-cash
Body: { schedule_id, seat_ids: [1,2,3] }
→ Tạo vé + xác nhận ngay

POST /api/ticket-booking/init-payment-payos
Body: { schedule_id, seat_ids: [1,2,3] }
→ Khởi tạo QR code PayOs

GET /api/ticket-booking/check-payment-status/{ticketCode}
→ Kiểm tra trạng thái thanh toán

GET /api/ticket-booking/ticket/{ticketCode}
→ Lấy chi tiết vé
```

---

## 🎯 Tóm Tắt

| Item | Status | Note |
|------|--------|------|
| Ticket Booking UI | ✅ | 3-step flow |
| Cash Payment | ✅ | Immediate |
| PayOs QR | ✅ | Webhook ready |
| Realtime Setup | ⏳ | Reverb to install |
| Frontend Echo | ⏳ | Need to integrate |
| Testing | ⏳ | End-to-end test |

---

## 🚀 Quick Start

```bash
# 1. Install dependencies
composer install

# 2. Setup env
cp .env.example .env
# Add PayOs credentials + Reverb config

# 3. Install Reverb
composer require laravel/reverb
php artisan reverb:install

# 4. Run migrations
php artisan migrate

# 5. Start services (3 terminal tabs)
# Tab 1:
php artisan serve

# Tab 2:
php artisan reverb:start

# Tab 3:
npm run dev  # (optional)

# 6. Access
http://localhost:8000/Admin/TicketBooking
```

---

**Last Updated:** April 20, 2026  
**Status:** Ready for Realtime Integration ✅
