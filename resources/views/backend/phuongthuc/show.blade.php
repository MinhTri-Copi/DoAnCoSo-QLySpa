@extends('backend.layouts.app')

@section('title', 'Chi tiết phương thức')

@section('content')
<div class="container">
    <h1>Chi tiết phương thức</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Mã phương thức:</strong> {{ $phuongThuc->MaPT }}</p>
            <p><strong>Tên phương thức:</strong> {{ $phuongThuc->TenPT }}</p>
            <p><strong>Mô tả:</strong> {{ $phuongThuc->Mota ?? 'N/A' }}</p>
        </div>
    </div>

    <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection