<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::all();
        return view('backend.customers.index', compact('customers'));
    }

    public function create()
    {
        $maxManguoidung = User::max('Manguoidung') ?? 0;
        $suggestedManguoidung = $maxManguoidung + 1;

        $accounts = Account::all(); // Lấy danh sách tài khoản để chọn MaTK
        return view('backend.customers.create', compact('suggestedManguoidung', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Manguoidung' => 'required|integer|unique:USER,Manguoidung',
            'MaTK' => 'required|exists:ACCOUNT,MaTK',
            'Hoten' => 'required|string|max:255',
            'SDT' => 'required|string|max:15',
            'DiaChi' => 'required|string|max:255',
            'Email' => 'required|email|unique:USER,Email',
            'Ngaysinh' => 'required|date',
            'Gioitinh' => 'required|in:Nam,Nữ',
        ]);

        User::create([
            'Manguoidung' => $request->Manguoidung,
            'MaTK' => $request->MaTK,
            'Hoten' => $request->Hoten,
            'SDT' => $request->SDT,
            'DiaChi' => $request->DiaChi,
            'Email' => $request->Email,
            'Ngaysinh' => $request->Ngaysinh,
            'Gioitinh' => $request->Gioitinh,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Thêm khách hàng thành công!');
    }

    public function show($id)
    {
        $customer = User::findOrFail($id);
        return view('backend.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = User::findOrFail($id);
        $accounts = Account::all();
        return view('backend.customers.edit', compact('customer', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'MaTK' => 'required|exists:ACCOUNT,MaTK',
            'Hoten' => 'required|string|max:255',
            'SDT' => 'required|string|max:15',
            'DiaChi' => 'required|string|max:255',
            'Email' => 'required|email|unique:USER,Email,' . $customer->Manguoidung . ',Manguoidung',
            'Ngaysinh' => 'required|date',
            'Gioitinh' => 'required|in:Nam,Nữ',
        ]);

        $customer->update([
            'MaTK' => $request->MaTK,
            'Hoten' => $request->Hoten,
            'SDT' => $request->SDT,
            'DiaChi' => $request->DiaChi,
            'Email' => $request->Email,
            'Ngaysinh' => $request->Ngaysinh,
            'Gioitinh' => $request->Gioitinh,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Cập nhật khách hàng thành công!');
    }

    public function confirmDestroy($id)
    {
        $customer = User::findOrFail($id);
        return view('backend.customers.destroy', compact('customer'));
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Xóa khách hàng thành công!');
    }
}