<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('backend.roles.index', compact('roles'));
    }

    public function create()
    {
        $maxRoleID = Role::max('RoleID') ?? 0;
        $suggestedRoleID = $maxRoleID + 1;
        return view('backend.roles.create', compact('suggestedRoleID'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'RoleID' => 'required|integer|unique:ROLE,RoleID',
            'Tenrole' => 'required|string|max:50',
        ]);

        Role::create([
            'RoleID' => $request->RoleID,
            'Tenrole' => $request->Tenrole,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Thêm vai trò thành công!');
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return view('backend.roles.show', compact('role'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('backend.roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'Tenrole' => 'required|string|max:50',
        ]);

        $role->update([
            'Tenrole' => $request->Tenrole,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Cập nhật vai trò thành công!');
    }

    public function confirmDestroy($id)
    {
        $role = Role::findOrFail($id);
        return view('backend.roles.destroy', compact('role'));
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->accounts()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', 'Không thể xóa vai trò này vì đang có tài khoản sử dụng!');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Xóa vai trò thành công!');
    }
}