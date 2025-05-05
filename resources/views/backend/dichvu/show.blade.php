@extends('backend.layouts.app')

@section('title', 'Chi Tiết Dịch Vụ')

@section('content')
<div class="container">
    <h2 class="mb-4">Chi Tiết Dịch Vụ</h2>
    <div class="mb-3">
        <label class="form-label">Mã Dịch Vụ:</label>
        <p>{{ $dichVu->MaDV }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Tên Dịch Vụ:</label>
        <p>{{ $dichVu->Tendichvu }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Giá:</label>
        <p>{{ number_format($dichVu->Gia, 0, ',', '.') }} VNĐ</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Mô Tả:</label>
        <p>{{ $dichVu->MoTa ?? 'N/A' }}</p>
    </div>

    <a href="{{ route('admin.dichvu.index') }}" class="btn btn-secondary">Quay Lại</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Về Dashboard</a>
</div>
@endsection