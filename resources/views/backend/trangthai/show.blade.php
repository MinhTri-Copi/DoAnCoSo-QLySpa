@extends('backend.layouts.app')

@section('title', 'Chi Tiết Trạng Thái')

@section('content')
<div class="container">
    <h2 class="mb-4">Chi Tiết Trạng Thái</h2>
    <div class="mb-3">
        <label class="form-label">Mã Trạng Thái:</label>
        <p>{{ $trangThai->Matrangthai }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Tên Trạng Thái:</label>
        <p>{{ $trangThai->Tentrangthai }}</p>
    </div>

    <a href="{{ route('admin.trangthai.index') }}" class="btn btn-secondary">Quay Lại</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Về Dashboard</a>
</div>
@endsection