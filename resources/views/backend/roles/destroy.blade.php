@extends('backend.layouts.app')

@section('title', 'Xóa vai trò')

@section('content')
    <div class="container-fluid">
        <h1>Xóa vai trò</h1>

        <div class="alert alert-warning">
            Bạn có chắc chắn muốn xóa vai trò <strong>{{ $role->RoleName }}</strong> (Mã: {{ $role->RoleID }}) không? Hành động này không thể hoàn tác.
        </div>

        <form action="{{ route('admin.roles.destroy', $role->RoleID) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection