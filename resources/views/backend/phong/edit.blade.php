@extends('backend.layouts.app')

@section('title', 'Sửa phòng')

@section('content')
<div class="container">
    <h1>Sửa phòng</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.phong.update', $phong->Maphong) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="Maphong" class="form-label">Mã phòng</label>
            <input type="text" class="form-control" id="Maphong" name="Maphong" value="{{ $phong->Maphong }}" readonly>
        </div>
        <div class="mb-3">
            <label for="Tenphong" class="form-label">Tên phòng</label>
            <input type="text" class="form-control" id="Tenphong" name="Tenphong" value="{{ old('Tenphong', $phong->Tenphong) }}">
        </div>
        <div class="mb-3">
            <label for="Loaiphong" class="form-label">Loại phòng</label>
            <select class="form-control" id="Loaiphong" name="Loaiphong">
                <option value="Thường" {{ old('Loaiphong', $phong->Loaiphong) == 'Thường' ? 'selected' : '' }}>Thường</option>
                <option value="Cao cấp" {{ old('Loaiphong', $phong->Loaiphong) == 'Cao cấp' ? 'selected' : '' }}>Cao cấp</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="MatrangthaiP" class="form-label">Trạng thái phòng</label>
            <select class="form-control" id="MatrangthaiP" name="MatrangthaiP">
                @foreach ($trangThaiPhongs as $trangThai)
                    <option value="{{ $trangThai->MatrangthaiP }}" {{ old('MatrangthaiP', $phong->MatrangthaiP) == $trangThai->MatrangthaiP ? 'selected' : '' }}>
                        {{ $trangThai->Tentrangthai }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.phong.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection