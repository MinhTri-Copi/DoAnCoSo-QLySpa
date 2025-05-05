@extends('backend.layouts.app')

@section('title', 'Sửa Phương Thức Hỗ Trợ')

@section('content')
<div class="container">
    <h2 class="mb-4">Sửa Phương Thức Hỗ Trợ</h2>
    <form method="POST" action="{{ route('admin.pthotro.update', $pthotro->MaPTHT) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="MaPTHT" class="form-label">Mã Phương Thức Hỗ Trợ</label>
            <input id="MaPTHT" type="text" class="form-control @error('MaPTHT') is-invalid @enderror" name="MaPTHT" value="{{ $pthotro->MaPTHT }}" required>
            @error('MaPTHT')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="TenPT" class="form-label">Tên Phương Thức</label>
            <input id="TenPT" type="text" class="form-control @error('TenPT') is-invalid @enderror" name="TenPT" value="{{ $pthotro->TenPT }}" required>
            @error('TenPT')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="{{ route('admin.pthotro.index') }}" class="btn btn-secondary">Quay Lại</a>
    </form>
</div>
@endsection