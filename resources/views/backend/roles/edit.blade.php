@extends('backend.layouts.app')

@section('title', 'Sửa vai trò')

@section('content')
    <div class="container-fluid">
        <h1>Sửa vai trò</h1>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.roles.update', $role->RoleID) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="RoleID" class="form-label">Mã vai trò</label>
                <input type="text" class="form-control" value="{{ $role->RoleID }}" disabled>
            </div>
            <div class="mb-3">
                <label for="Tenrole" class="form-label">Tên vai trò <span class="text-danger">*</span></label>
                <input type="text" name="RoleName" class="form-control" value="{{ old('Tenrole', $role->Tenrole) }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật vai trò</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection