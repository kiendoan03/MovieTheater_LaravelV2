<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Trang hồ sơ cá nhân của khách hàng đang đăng nhập.
     * GET /profile
     */
    public function show()
    {
        /** @var Account $account */
        $account = Auth::guard('api')->user();

        if (! $account) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem hồ sơ.');
        }

        // Eager-load đầy đủ dữ liệu ticket/booking
        $account->load([
            'customer',
            'customer.tickets.bookings.seat.seatType',
            'customer.tickets.bookings.schedule.movie',
            'customer.tickets.bookings.schedule.room',
        ]);

        return view('customer.profile.show', compact('account'));
    }

    /**
     * Form chỉnh sửa thông tin cá nhân.
     * GET /profile/edit
     */
    public function edit()
    {
        /** @var Account $account */
        $account = Auth::guard('api')->user();

        if (! $account) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }

        $account->load('customer');

        return view('customer.profile.edit', compact('account'));
    }

    /**
     * Lưu chỉnh sửa thông tin cá nhân.
     * PUT /profile/edit
     */
    public function update(Request $request)
    {
        /** @var Account $account */
        $account = Auth::guard('api')->user();

        if (! $account) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'phonenumber'   => 'required|string|max:20',
            'address'       => 'nullable|string|max:500',
            'date_of_birth' => 'required|date|before:today',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required'          => 'Họ tên không được để trống.',
            'phonenumber.required'   => 'Số điện thoại không được để trống.',
            'date_of_birth.required' => 'Ngày sinh không được để trống.',
            'date_of_birth.before'   => 'Ngày sinh phải trước ngày hôm nay.',
            'avatar.image'           => 'File phải là hình ảnh.',
            'avatar.max'             => 'Ảnh không được vượt quá 2MB.',
        ]);

        if ($account->customer) {
            $avatarPath = $account->customer->avatar; // giữ ảnh cũ

            if ($request->hasFile('avatar')) {
                // Xóa ảnh cũ nếu có
                if ($avatarPath) {
                    Storage::delete('public/img/avatars/' . $avatarPath);
                }
                // Lưu ảnh mới
                $filename  = time() . '_avatar_' . $request->file('avatar')->getClientOriginalName();
                Storage::putFileAs('public/img/avatars', $request->file('avatar'), $filename);
                $avatarPath = $filename;
            }

            $account->customer->update(array_merge(
                collect($data)->except('avatar')->toArray(),
                ['avatar' => $avatarPath]
            ));
        }

        return redirect()->route('customer.profile.show')
            ->with('success', 'Cập nhật thông tin thành công!');
    }
}
