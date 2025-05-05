@extends('backend.layouts.app')

@section('title', 'Chi tiết đánh giá')

@section('content')
<div class="container">
    <h1>Chi tiết đánh giá</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Mã đánh giá:</strong> {{ $danhGia->MaDG }}</p>
            <p><strong>Đánh giá sao:</strong> {{ $danhGia->Danhgiasao }} sao</p>
            <p><strong>Nhận xét:</strong> {{ $danhGia->Nhanxet ?? 'N/A' }}</p>
            <p><strong>Ngày đánh giá:</strong> {{ $danhGia->Ngaydanhgia }}</p>
            <p><strong>Người dùng:</strong> {{ $danhGia->user->name ?? 'N/A' }}</p>
            <p><strong>Hóa đơn:</strong> {{ $danhGia->hoaDon->MaHD ?? 'N/A' }}</p>
        </div>
    </div>

    <a href="{{ route('admin.danhgia.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection