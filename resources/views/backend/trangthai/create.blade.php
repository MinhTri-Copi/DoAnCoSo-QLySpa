@extends('backend.layouts.app')

@section('title', 'Thêm Trạng Thái')

@section('content')
<div class="container">
    <h2 class="mb-4">Thêm Trạng Thái</h2>
    <form method="POST" action="{{ route('admin.trangthai.store') }}">
        @csrf
        <div class="mb-3">
            <label for="Matrangthai" class="form-label">Mã Trạng Thái</label>
            <input id="Matrangthai" type="number" class="form-control @error('Matrangthai') is-invalid @enderror" name="Matrangthai" value="{{ $suggestedMatrangthai }}" required readonly>
            @error('Matrangthai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Tentrangthai" class="form-label">Tên Trạng Thái</label>
            <input id="Tentrangthai" type="text" class="form-control @error('Tentrangthai') is-invalid @enderror" name="Tentrangthai" value="{{ old('Tentrangthai') }}" required>
            @error('Tentrangthai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admin.trangthai.index') }}" class="btn btn-secondary">Quay Lại</a>
    </form>
</div>
@endsection