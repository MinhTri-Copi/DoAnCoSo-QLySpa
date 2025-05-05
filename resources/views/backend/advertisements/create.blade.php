@extends('backend.layouts.app')

@section('title', 'Thêm quảng cáo')

@section('content')
    <div class="container-fluid">
        <h1>Thêm quảng cáo</h1>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="MaQC" class="form-label">Mã quảng cáo <span class="text-danger">*</span></label>
                <input type="number" name="MaQC" class="form-control" value="{{ $suggestedMaQC }}" readonly>
                <small class="form-text text-muted">Mã quảng cáo được sinh tự động.</small>
            </div>
            <div class="mb-3">
                <label for="Tieude" class="form-label">Tiêu đề quảng cáo <span class="text-danger">*</span></label>
                <input type="text" name="Tieude" class="form-control" value="{{ old('Tieude') }}" required>
            </div>
            <div class="mb-3">
                <label for="Noidung" class="form-label">Nội dung quảng cáo <span class="text-danger">*</span></label>
                <textarea name="Noidung" class="form-control" rows="5" required>{{ old('Noidung') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="Image" class="form-label">Hình ảnh</label>
                <input type="file" name="Image" class="form-control">
                <small class="form-text text-muted">Chấp nhận các định dạng: jpeg, png, jpg, gif. Dung lượng tối đa: 2MB.</small>
            </div>
            <div class="mb-3">
                <label for="Manguoidung" class="form-label">Người dùng <span class="text-danger">*</span></label>
                <select name="Manguoidung" class="form-control" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung') == $user->Manguoidung ? 'selected' : '' }}>
                            {{ $user->Hoten }} (Mã: {{ $user->Manguoidung }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="Loaiquangcao" class="form-label">Loại quảng cáo <span class="text-danger">*</span></label>
                <select name="Loaiquangcao" class="form-control" required>
                    @foreach ($adTypes as $type)
                        <option value="{{ $type }}" {{ old('Loaiquangcao') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="MaTTQC" class="form-label">Trạng thái quảng cáo <span class="text-danger">*</span></label>
                <select name="MaTTQC" class="form-control" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->MaTTQC }}" {{ old('MaTTQC') == $status->MaTTQC ? 'selected' : '' }}>
                            {{ $status->TenTT }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="Ngaybatdau" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                <input type="date" name="Ngaybatdau" class="form-control" value="{{ old('Ngaybatdau') }}" required>
            </div>
            <div class="mb-3">
                <label for="Ngayketthuc" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                <input type="date" name="Ngayketthuc" class="form-control" value="{{ old('Ngayketthuc') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm quảng cáo</button>
            <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection