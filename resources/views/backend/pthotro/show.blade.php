@extends('backend.layouts.app')

@section('title', 'Chi Tiết Phương Thức Hỗ Trợ')

@section('content')
<div class="container">
    <h2 class="mb-4">Chi Tiết Phương Thức Hỗ Trợ</h2>
    <div class="mb-3">
        <label class="form-label">Mã Phương Thức Hỗ Trợ:</label>
        <p>{{ $pthotro->MaPTHT }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Tên Phương Thức:</label>
        <p>{{ $pthotro->TenPT }}</p>
    </div>

    <a href="{{ route('admin.pthotro.index') }}" class="btn btn-secondary">Quay Lại</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Về Dashboard</a>
</div>
@endsection