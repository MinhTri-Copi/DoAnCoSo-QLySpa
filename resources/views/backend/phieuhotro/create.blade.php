@extends('backend.layouts.app')

@section('title', 'Thêm Phiếu Hỗ Trợ')

@section('content')
<div class="container">
    <h2 class="mb-4">Thêm Phiếu Hỗ Trợ</h2>
    <form method="POST" action="{{ route('admin.phieuhotro.store') }}">
        @csrf
        <div class="mb-3">
            <label for="MaphieuHT" class="form-label">Mã Phiếu Hỗ Trợ</label>
            <input id="MaphieuHT" type="number" class="form-control @error('MaphieuHT') is-invalid @enderror" name="MaphieuHT" value="{{ $suggestedMaphieuHT }}" required readonly>
            @error('MaphieuHT')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Noidungyeucau" class="form-label">Nội Dung Yêu Cầu</label>
            <textarea id="Noidungyeucau" class="form-control @error('Noidungyeucau') is-invalid @enderror" name="Noidungyeucau" required>{{ old('Noidungyeucau') }}</textarea>
            @error('Noidungyeucau')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Matrangthai" class="form-label">Trạng Thái</label>
            <select id="Matrangthai" class="form-control @error('Matrangthai') is-invalid @enderror" name="Matrangthai" required>
                <option value="">Chọn trạng thái</option>
                @foreach ($trangThais as $trangThai)
                    <option value="{{ $trangThai->Matrangthai }}" {{ old('Matrangthai') == $trangThai->Matrangthai ? 'selected' : '' }}>{{ $trangThai->Tentrangthai }}</option>
                @endforeach
            </select>
            @error('Matrangthai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="Manguoidung" class="form-label">Người Dùng</label>
            <select id="Manguoidung" class="form-control @error('Manguoidung') is-invalid @enderror" name="Manguoidung" required>
                <option value="">Chọn người dùng</option>
                @foreach ($users as $user)
                    <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung') == $user->Manguoidung ? 'selected' : '' }}>{{ $user->Hoten }}</option>
                @endforeach
            </select>
            @error('Manguoidung')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="MaPTHT" class="form-label">Phương Thức Hỗ Trợ</label>
            <select id="MaPTHT" class="form-control @error('MaPTHT') is-invalid @enderror" name="MaPTHT" required>
                <option value="">Chọn phương thức hỗ trợ</option>
                @foreach ($pthotros as $pthotro)
                    <option value="{{ $pthotro->MaPTHT }}" {{ old('MaPTHT') == $pthotro->MaPTHT ? 'selected' : '' }}>{{ $pthotro->TenPT }}</option>
                @endforeach
            </select>
            @error('MaPTHT')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admin.phieuhotro.index') }}" class="btn btn-secondary">Quay Lại</a>
    </form>
</div>
@endsection