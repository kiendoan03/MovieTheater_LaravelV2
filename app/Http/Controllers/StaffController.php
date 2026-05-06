<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Account;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    /**
     * Danh sách tất cả nhân viên (Admin panel).
     */
    public function index()
    {
        $staffs = Account::with('staff')
            ->where('role', UserRole::Staff)
            ->latest()
            ->paginate(10);

        return view('admin.Account.index-staff', compact('staffs'));
    }

    /**
     * Form tạo nhân viên mới.
     */
    public function create()
    {
        return view('admin.Account.add-staff-account');
    }

    /**
     * Tạo tài khoản nhân viên mới.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:accounts,email',
            'name' => 'required|string|max:255',
            'phonenumber' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
        ]);

        DB::transaction(function () use ($data) {
            $account = Account::create([
                'email' => $data['email'],
                'password' => 'Abc@123456', // Mật khẩu mặc định như thông báo ở View
                'role' => UserRole::Staff,
                'is_active' => true,
            ]);

            Staff::create([
                'account_id' => $account->id,
                'name' => $data['name'],
                'phonenumber' => $data['phonenumber'] ?? null,
                'address' => $data['address'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
            ]);
        });

        return redirect()->route('admin.accounts.staff.index')
            ->with('success', 'Tạo tài khoản nhân viên thành công.');
    }

    /**
     * Xem chi tiết nhân viên + lịch sử vé bán (Admin panel).
     * $id = Account ID
     */
    public function show($id)
    {
        $staff = Account::with([
            'staff',
            'staff.tickets.bookings.seat.seatType',
            'staff.tickets.bookings.schedule.movie',
            'staff.tickets.bookings.schedule.room',
        ])->findOrFail($id);

        return view('admin.Account.show-staff', compact('staff'));
    }

    /**
     * Form chỉnh sửa nhân viên — nhận Account ID, truyền $staff (Account) vào view.
     */
    public function edit($id)
    {
        $staff = Account::with('staff')->findOrFail($id);

        return view('admin.Account.edit-staff-info', compact('staff'));
    }

    /**
     * Cập nhật thông tin và is_active cho Account + Staff.
     */
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $isActive = $request->boolean('is_active');
        $account->update(['is_active' => $isActive]);

        if ($account->staff) {
            $avatarPath = $account->staff->avatar;

            if ($request->hasFile('avatar')) {
                if ($avatarPath) {
                    Storage::delete('public/img/avatars/'.$avatarPath);
                }
                $filename = time().'_avatar_'.$request->file('avatar')->getClientOriginalName();
                Storage::putFileAs('public/img/avatars', $request->file('avatar'), $filename);
                $avatarPath = $filename;
            }

            $account->staff->update(array_merge(
                collect($data)->except('avatar')->toArray(),
                ['avatar' => $avatarPath, 'is_active' => $isActive]
            ));
        }

        return redirect()->back()->with('success', 'Cập nhật thông tin nhân viên thành công.');
    }

    /**
     * Soft delete: tắt is_active của Account + Staff.
     */
    public function destroy($id)
    {
        app(AccountController::class)->deactivateAccount($id);
        $staff = Staff::where('account_id', $id)->first();
        if ($staff) {
            $staff->update(['is_active' => false]);
        }

        return redirect()->route('admin.accounts.staff.index')
            ->with('success', 'Đã vô hiệu hoá tài khoản nhân viên.');
    }
}
