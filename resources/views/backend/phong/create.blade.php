@extends('backend.layouts.app')

@section('title', 'Thêm phòng mới')

@section('content')
<div class="container">
    <h1>Thêm phòng mới</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.phong.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="Maphong" class="form-label">Mã phòng</label>
            <input type="text" class="form-control" id="Maphong" name="Maphong" value="{{ $suggestedMaphong }}" readonly>
        </div>
        <div class="mb-3">
            <label for="Tenphong" class="form-label">Tên phòng</label>
            <input type="text" class="form-control" id="Tenphong" name="Tenphong" value="{{ old('Tenphong') }}">
        </div>
        <div class="mb-3">
            <label for="Loaiphong" class="form-label">Loại phòng</label>
            <select class="form-control" id="Loaiphong" name="Loaiphong">
                <option value="Thường" {{ old('Loaiphong') == 'Thường' ? 'selected' : '' }}>Thường</option>
                <option value="Cao cấp" {{ old('Loaiphong') == 'Cao cấp' ? 'selected' : '' }}>Cao cấp</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="MatrangthaiP" class="form-label">Trạng thái phòng</label>
            <select class="form-control" id="MatrangthaiP" name="MatrangthaiP">
                @foreach ($trangThaiPhongs as $trangThai)
                    <option value="{{ $trangThai->MatrangthaiP }}" {{ old('MatrangthaiP') == $trangThai->MatrangthaiP ? 'selected' : '' }}>
                        {{ $trangThai->Tentrangthai }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admin.phong.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection