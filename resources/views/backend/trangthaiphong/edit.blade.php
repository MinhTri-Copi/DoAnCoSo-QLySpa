@extends('backend.layouts.app')

@section('title', 'Sửa Trạng Thái Phòng')

@section('content')
<div class="container">
    <h2 class="mb-4">Sửa Trạng Thái Phòng</h2>
    <form method="POST" action="{{ route('admin.trangthaiphong.update', $trangThaiPhong->MatrangthaiP) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="MatrangthaiP" class="form-label">Mã Trạng Thái Phòng</label>
            <input id="MatrangthaiP" type="text" class="form-control" name="MatrangthaiP" value="{{ $trangThaiPhong->MatrangthaiP }}" readonly>
        </div>

        <div class="mb-3">
            <label for="Tentrangthai" class="form-label">Tên Trạng Thái</label>
            <input id="Tentrangthai" type="text" class="form-control @error('Tentrangthai') is-invalid @enderror" name="Tentrangthai" value="{{ $trangThaiPhong->Tentrangthai }}" required>
            @error('Tentrangthai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="{{ route('admin.trangthaiphong.index') }}" class="btn btn-secondary">Quay Lại</a>
    </form>
</div>
@endsection