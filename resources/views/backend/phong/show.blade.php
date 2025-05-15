@extends('backend.layouts.app')

@section('title', 'Chi tiết phòng')

@section('content')
<div class="container">
    <h1>Chi tiết phòng</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Mã phòng:</strong> {{ $phong->Maphong }}</p>
            <p><strong>Tên phòng:</strong> {{ $phong->Tenphong }}</p>
            <p><strong>Loại phòng:</strong> {{ $phong->Loaiphong }}</p>
            <p><strong>Trạng thái:</strong> {{ $phong->trangThaiPhong->Tentrangthai ?? 'N/A' }}</p>
        </div>
    </div>

    <a href="{{ route('admin.phong.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection