<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Danh sách tất cả khách hàng (Admin panel).
     */
    public function index()
    {
        $customers = Account::with('customer')
            ->where('role', UserRole::Customer)
            ->latest()
            ->paginate(10);

        return view('admin.Account.index-customer', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     * Không dùng trong Admin (customer tự đăng ký qua API).
     */
    public function create()
    {
        //
    }

    /**
     * POST /api/customers
     * Bước 2 đăng ký: điền thông tin cá nhân sau khi đã có account.
     * Yêu cầu auth:api (access token từ bước 1).
     */
    public function store(Request $request)
    {
        $account = auth()->user();

        // Mỗi account chỉ được tạo 1 customer
        if ($account->customer()->exists()) {
            return response()->json(['message' => 'Thông tin cá nhân đã được thiết lập.'], 409);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date',
        ]);

        $customer = $this->createCustomer($account, $data);

        return response()->json([
            'message' => 'Thiết lập thông tin cá nhân thành công.',
            'customer' => $customer,
        ], 201);
    }

    public function createCustomer(Account $account, array $data): Customer
    {
        return Customer::create([
            'account_id' => $account->id,
            'email' => $account->email,
            'name' => $data['name'],
            'phonenumber' => $data['phonenumber'],
            'address' => $data['address'] ?? null,
            'avatar' => $data['avatar'] ?? null,
            'date_of_birth' => $data['date_of_birth'],
        ]);
    }

    /**
     * Xem chi tiết khách hàng + lịch sử đặt vé (Admin panel).
     * $id = Account ID
     */
    public function show($id)
    {
        $customer = Account::with([
            'customer',
            'customer.tickets.bookings.seat.seatType',
            'customer.tickets.bookings.schedule.movie',
            'customer.tickets.bookings.schedule.room',
        ])->findOrFail($id);

        return view('admin.Account.show-customer', compact('customer'));
    }

    /**
     * Form chỉnh sửa khách hàng — nhận Account ID.
     */
    public function edit($id)
    {
        $customer = Account::with([
            'customer',
            'customer.tickets.bookings.seat.seatType',
            'customer.tickets.bookings.schedule.movie',
        ])->findOrFail($id);

        return view('admin.Account.edit-customer', compact('customer'));
    }

    /**
     * Cập nhật thông tin và is_active cho Account + Customer.
     */
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'required|date',
        ]);

        $isActive = $request->boolean('is_active');
        $account->update(['is_active' => $isActive]);

        if ($account->customer) {
            $account->customer->update(array_merge($data, ['is_active' => $isActive]));
        }

        return redirect()->back()->with('success', 'Cập nhật thông tin khách hàng thành công.');
    }

    /**
     * Soft delete: tắt is_active của Account + Customer.
     */
    public function destroy($id)
    {
        app(AccountController::class)->deactivateAccount($id);

        $customer = Customer::where('account_id', $id)->first();
        if ($customer) {
            $customer->update(['is_active' => false]);
        }

        return redirect()->route('admin.accounts.customer.index')
            ->with('success', 'Đã vô hiệu hoá tài khoản khách hàng.');
    }
}
