@extends('backend.layouts.app')

@section('title', 'Thêm trạng thái quảng cáo')

@section('content')
    <div class="container-fluid">
        <h1>Thêm trạng thái quảng cáo</h1>

        <!-- Thông báo lỗi -->
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

        <!-- Form thêm trạng thái -->
        <form action="{{ route('ad-statuses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="MaTTQC" class="form-label">Mã trạng thái <span class="text-danger">*</span></label>
                <input type="number" name="MaTTQC" class="form-control" value="{{ $suggestedMaTTQC }}" readonly>
                <small class="form-text text-muted">Mã trạng thái được sinh tự động.</small>
            </div>
            <div class="mb-3">
                <label for="TenTT" class="form-label">Tên trạng thái <span class="text-danger">*</span></label>
                <input type="text" name="TenTT" class="form-control" value="{{ old('TenTT') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm trạng thái</button>
            <a href="{{ route('ad-statuses.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection