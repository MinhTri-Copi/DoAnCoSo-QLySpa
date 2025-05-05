@extends('backend.layouts.app')

@section('title', 'Chi Tiết Đặt Lịch')

@section('content')
<div class="container">
    <h2 class="mb-4">Chi Tiết Đặt Lịch</h2>
    <div class="mb-3">
        <label class="form-label">Mã Đặt Lịch:</label>
        <p>{{ $datLich->MaDL }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Người Dùng:</label>
        <p>{{ $datLich->user->Hoten ?? 'N/A' }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Thời Gian Đặt Lịch:</label>
        <p>{{ $datLich->Thoigiandatlich }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Trạng Thái:</label>
        <p>{{ $datLich->Trangthai_ }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Dịch Vụ:</label>
        <p>{{ $datLich->dichVu->Tendichvu ?? 'N/A' }}</p>
    </div>

    <a href="{{ route('admin.datlich.index') }}" class="btn btn-secondary">Quay Lại</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Về Dashboard</a>
</div>
@endsection