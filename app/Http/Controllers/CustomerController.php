<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * Không dùng trong API.
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
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
