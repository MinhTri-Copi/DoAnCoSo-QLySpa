@extends('backend.layouts.app')

@section('title', 'Chi tiết trạng thái quảng cáo')

@section('content')
    <div class="container-fluid">
        <h1>Chi tiết trạng thái quảng cáo</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Trạng thái: {{ $status->TenTT }}</h5>
                <p class="card-text">
                    <strong>Mã trạng thái:</strong> {{ $status->MaTTQC }}<br>
                    <strong>Tên trạng thái:</strong> {{ $status->TenTT }}<br>
                    <strong>Số lượng quảng cáo sử dụng:</strong> {{ $status->quangCao()->count() }}
                </p>
                <a href="{{ route('ad-statuses.index') }}" class="btn btn-secondary">Quay lại</a>
                <a href="{{ route('ad-statuses.edit', $status->MaTTQC) }}" class="btn btn-warning">Sửa</a>
                <a href="{{ route('ad-statuses.confirm-destroy', $status->MaTTQC) }}" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
@endsection