@extends('backend.layouts.app')

@section('title', 'Thêm vai trò')

@section('content')
    <div class="container-fluid">
        <h1>Thêm vai trò</h1>

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

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="RoleID" class="form-label">Mã vai trò <span class="text-danger">*</span></label>
                <input type="number" name="RoleID" class="form-control" value="{{ $suggestedRoleID }}" readonly>
                <small class="form-text text-muted">Mã vai trò được sinh tự động.</small>
            </div>
            <div class="mb-3">
                <label for="Tenrole" class="form-label">Tên vai trò <span class="text-danger">*</span></label>
                <input type="text" name="Tenrole" class="form-control" value="{{ old('Tenrole') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm vai trò</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection