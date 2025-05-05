@extends('backend.layouts.app')

@section('title', 'Sửa đánh giá')

@section('content')
<div class="container">
    <h1>Sửa đánh giá</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.danhgia.update', $danhGia->MaDG) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="MaDG" class="form-label">Mã đánh giá</label>
            <input type="text" class="form-control" id="MaDG" name="MaDG" value="{{ $danhGia->MaDG }}" readonly>
        </div>
        <div class="mb-3">
            <label for="Danhgiasao" class="form-label">Đánh giá sao (1-5)</label>
            <select class="form-control" id="Danhgiasao" name="Danhgiasao">
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('Danhgiasao', $danhGia->Danhgiasao) == $i ? 'selected' : '' }}>
                        {{ $i }} sao
                    </option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label for="Nhanxet" class="form-label">Nhận xét</label>
            <textarea class="form-control" id="Nhanxet" name="Nhanxet">{{ old('Nhanxet', $danhGia->Nhanxet) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="Manguoidung" class="form-label">Người dùng</label>
            <select class="form-control" id="Manguoidung" name="Manguoidung">
                @foreach ($users as $user)
                    <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung', $danhGia->Manguoidung) == $user->Manguoidung ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="MaHD" class="form-label">Hóa đơn</label>
            <select class="form-control" id="MaHD" name="MaHD">
                @foreach ($hoaDons as $hoaDon)
                    <option value="{{ $hoaDon->MaHD }}" {{ old('MaHD', $danhGia->MaHD) == $hoaDon->MaHD ? 'selected' : '' }}>
                        {{ $hoaDon->MaHD }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.danhgia.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection