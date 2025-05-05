@extends('backend.layouts.app')

@section('title', 'Thêm lịch sử điểm thưởng mới')

@section('content')
<div class="container">
    <h1>Thêm lịch sử điểm thưởng mới</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.lsdiemthuong.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="MaLSDT" class="form-label">Mã lịch sử điểm thưởng</label>
            <input type="text" class="form-control" id="MaLSDT" name="MaLSDT" value="{{ $suggestedMaLSDT }}" readonly>
        </div>
        <div class="mb-3">
            <label for="Manguoidung" class="form-label">Người dùng</label>
            <select class="form-control" id="Manguoidung" name="Manguoidung">
                @foreach ($users as $user)
                    <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung') == $user->Manguoidung ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="MaHD" class="form-label">Hóa đơn</label>
            <select class="form-control" id="MaHD" name="MaHD">
                @foreach ($hoaDons as $hoaDon)
                    <option value="{{ $hoaDon->MaHD }}" {{ old('MaHD') == $hoaDon->MaHD ? 'selected' : '' }}>
                        {{ $hoaDon->MaHD }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="Sodiem" class="form-label">Số điểm</label>
            <input type="number" class="form-control" id="Sodiem" name="Sodiem" value="{{ old('Sodiem') }}">
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admin.lsdiemthuong.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection