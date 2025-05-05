<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('role')->get();
        return view('backend.accounts.index', compact('accounts'));
    }

    public function create()
    {
        $maxMaTK = Account::max('MaTK') ?? 0;
        $suggestedMaTK = $maxMaTK + 1;
        $roles = Role::all();
        return view('backend.accounts.create', compact('suggestedMaTK', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaTK' => 'required|integer|unique:ACCOUNT,MaTK',
            'Tendangnhap' => 'required|string|max:255|unique:ACCOUNT,Tendangnhap',
            'Matkhau' => 'required|string|min:6',
            'RoleID' => 'required|exists:ROLE,RoleID',
        ]);

        Account::create([
            'MaTK' => $request->MaTK,
            'Tendangnhap' => $request->Tendangnhap,
            'Matkhau' => Hash::make($request->Matkhau),
            'RoleID' => $request->RoleID,
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Thêm tài khoản thành công!');
    }

    public function show($id)
    {
        $account = Account::with('role')->findOrFail($id);
        return view('backend.accounts.show', compact('account'));
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $roles = Role::all();
        return view('backend.accounts.edit', compact('account', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $request->validate([
            'Tendangnhap' => 'required|string|max:255|unique:ACCOUNT,Tendangnhap,' . $account->MaTK . ',MaTK',
            'Matkhau' => 'nullable|string|min:6',
            'RoleID' => 'required|exists:ROLE,RoleID',
        ]);

        $data = [
            'Tendangnhap' => $request->Tendangnhap,
            'RoleID' => $request->RoleID,
        ];

        if ($request->filled('Matkhau')) {
            $data['Matkhau'] = Hash::make($request->Matkhau);
        }

        $account->update($data);

        return redirect()->route('admin.accounts.index')->with('success', 'Cập nhật tài khoản thành công!');
    }

    public function confirmDestroy($id)
    {
        $account = Account::findOrFail($id);
        return view('backend.accounts.destroy', compact('account'));
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);

        if ($account->MaTK == auth()->user()->MaTK) {
            return redirect()->route('admin.accounts.index')->with('error', 'Không thể xóa tài khoản đang đăng nhập!');
        }

        $account->delete();

        return redirect()->route('admin.accounts.index')->with('success', 'Xóa tài khoản thành công!');
    }
}