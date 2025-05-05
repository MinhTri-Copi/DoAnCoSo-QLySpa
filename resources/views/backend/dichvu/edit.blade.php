@extends('backend.layouts.app')

@section('title', 'Sửa Dịch Vụ')

@section('content')
<div class="container">
    <h2 class="mb-4">Sửa Dịch Vụ</h2>
    <form method="POST" action="{{ route('admin.dichvu.update', $dichVu->MaDV) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="MaDV" class="form-label">Mã Dịch Vụ</label>
            <input id="MaDV" type="text" class="form-control @error('MaDV') is-invalid @enderror" name="MaDV" value="{{ $dichVu->MaDV }}" required>
            @error('MaDV')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Tendichvu" class="form-label">Tên Dịch Vụ</label>
            <input id="Tendichvu" type="text" class="form-control @error('Tendichvu') is-invalid @enderror" name="Tendichvu" value="{{ $dichVu->Tendichvu }}" required>
            @error('Tendichvu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Gia" class="form-label">Giá</label>
            <input id="Gia" type="number" step="0.01" class="form-control @error('Gia') is-invalid @enderror" name="Gia" value="{{ $dichVu->Gia }}" required>
            @error('Gia')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="MoTa" class="form-label">Mô Tả</label>
            <textarea id="MoTa" class="form-control @error('MoTa') is-invalid @enderror" name="MoTa">{{ $dichVu->MoTa }}</textarea>
            @error('MoTa')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="{{ route('admin.dichvu.index') }}" class="btn btn-secondary">Quay Lại</a>
    </form>
</div>
@endsection@extends('layouts.app')

@section('title', 'Sửa Dịch Vụ')

@section('content')
<div class="container">
    <h2 class="mb-4">Sửa Dịch Vụ</h2>
    <form method="POST" action="{{ route('admin.dichvu.update', $dichVu->MaDV) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="MaDV" class="form-label">Mã Dịch Vụ</label>
            <input id="MaDV" type="text" class="form-control @error('MaDV') is-invalid @enderror" name="MaDV" value="{{ $dichVu->MaDV }}" required>
            @error('MaDV')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Tendichvu" class="form-label">Tên Dịch Vụ</label>
            <input id="Tendichvu" type="text" class="form-control @error('Tendichvu') is-invalid @enderror" name="Tendichvu" value="{{ $dichVu->Tendichvu }}" required>
            @error('Tendichvu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Gia" class="form-label">Giá</label>
            <input id="Gia" type="number" step="0.01" class="form-control @error('Gia') is-invalid @enderror" name="Gia" value="{{ $dichVu->Gia }}" required>
            @error('Gia')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="MoTa" class="form-label">Mô Tả</label>
            <textarea id="MoTa" class="form-control @error('MoTa') is-invalid @enderror" name="MoTa">{{ $dichVu->MoTa }}</textarea>
            @error('MoTa')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="{{ route('admin.dichvu.index') }}" class="btn btn-secondary">Quay Lại</a>
    </form>
</div>
@endsection