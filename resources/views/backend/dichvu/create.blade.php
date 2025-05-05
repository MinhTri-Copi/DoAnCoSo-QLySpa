@extends('backend.layouts.app')

@section('title', 'Thêm Dịch Vụ')

@section('content')
<div class="container">
    <h2 class="mb-4">Thêm Dịch Vụ</h2>
    <form method="POST" action="{{ route('admin.dichvu.store') }}">
        @csrf
        <div class="mb-3">
            <label for="MaDV" class="form-label">Mã Dịch Vụ</label>
            <input id="MaDV" type="text" class="form-control @error('MaDV') is-invalid @enderror" name="MaDV" value="{{ old('MaDV') }}" required>
            @error('MaDV')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Tendichvu" class="form-label">Tên Dịch Vụ</label>
            <input id="Tendichvu" type="text" class="form-control @error('Tendichvu') is-invalid @enderror" name="Tendichvu" value="{{ old('Tendichvu') }}" required>
            @error('Tendichvu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Gia" class="form-label">Giá</label>
            <input id="Gia" type="number" step="0.01" class="form-control @error('Gia') is-invalid @enderror" name="Gia" value="{{ old('Gia') }}" required>
            @error('Gia')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="MoTa" class="form-label">Mô Tả</label>
            <textarea id="MoTa" class="form-control @error('MoTa') is-invalid @enderror" name="MoTa">{{ old('MoTa') }}</textarea>
            @error('MoTa')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admin.dichvu.index') }}" class="btn btn-secondary">Quay Lại</a>
    </form>
</div>
@endsection