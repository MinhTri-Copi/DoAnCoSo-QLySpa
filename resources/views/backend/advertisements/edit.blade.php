@extends('backend.layouts.app')

@section('title', 'Sửa quảng cáo')

@section('styles')
<style>
    /* Custom Select Styling */
    .custom-select-wrapper {
        position: relative;
    }
    
    .custom-select-trigger {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .custom-select-trigger:after {
        content: '\f078';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        font-size: 0.8rem;
        color: #e83e8c;
        margin-left: 5px;
    }
    
    .custom-select-wrapper .dropdown-menu {
        width: 100%;
        padding: 0;
        margin-top: 5px;
        border-radius: 8px;
        border: 1px solid rgba(232, 62, 140, 0.2);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .custom-select-wrapper .dropdown-item {
        padding: 12px 15px;
        color: #333;
        transition: all 0.3s;
    }
    
    .custom-select-wrapper .dropdown-item:hover,
    .custom-select-wrapper .dropdown-item:focus {
        background-color: rgba(232, 62, 140, 0.1);
        color: #e83e8c;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <h1>Sửa quảng cáo</h1>

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

        <form action="{{ route('admin.advertisements.update', $advertisement->MaQC) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="MaQC" class="form-label">Mã quảng cáo</label>
                <input type="text" class="form-control" value="{{ $advertisement->MaQC }}" disabled>
            </div>
            <div class="mb-3">
                <label for="Tieude" class="form-label">Tiêu đề quảng cáo <span class="text-danger">*</span></label>
                <input type="text" name="Tieude" class="form-control" value="{{ old('Tieude', $advertisement->Tieude) }}" required>
            </div>
            <div class="mb-3">
                <label for="Noidung" class="form-label">Nội dung quảng cáo <span class="text-danger">*</span></label>
                <textarea name="Noidung" class="form-control" rows="5" required>{{ old('Noidung', $advertisement->Noidung) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="Image" class="form-label">Hình ảnh</label>
                <input type="file" name="Image" class="form-control">
                @if ($advertisement->Image)
                    <img src="{{ route('storage.image', ['path' => $advertisement->Image]) }}" alt="{{ $advertisement->Tieude }}" style="max-width: 200px; margin-top: 10px;">
                @endif
                <small class="form-text text-muted">Chấp nhận các định dạng: jpeg, png, jpg, gif. Dung lượng tối đa: 5MB.</small>
            </div>
            <div class="mb-3">
                <label for="Manguoidung" class="form-label">Người dùng <span class="text-danger">*</span></label>
                <select name="Manguoidung" class="form-control" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung', $advertisement->Manguoidung) == $user->Manguoidung ? 'selected' : '' }}>
                            {{ $user->Hoten }} (Mã: {{ $user->Manguoidung }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="Loaiquangcao" class="form-label">Loại quảng cáo <span class="text-danger">*</span></label>
                @php
                    $loaiQuangCaoMapping = [
                        'Khuyenmai' => 'Khuyến mãi',
                        'Sukien' => 'Sự kiện',
                        'Thongbao' => 'Thông báo'
                    ];
                    $selectedType = old('Loaiquangcao', $advertisement->Loaiquangcao);
                    $selectedDisplayText = $loaiQuangCaoMapping[$selectedType] ?? $selectedType;
                @endphp
                <div class="custom-select-wrapper">
                    <input type="hidden" name="Loaiquangcao" id="selected-type" value="{{ $selectedType }}">
                    <div class="form-control custom-select-trigger" id="selected-display" data-bs-toggle="dropdown">
                        {{ $selectedDisplayText }}
                    </div>
                    <div class="dropdown-menu w-100">
                        @foreach ($adTypes as $type)
                            <a class="dropdown-item type-option" href="#" 
                               data-value="{{ $type }}" 
                               data-display="{{ $loaiQuangCaoMapping[$type] ?? $type }}">
                                {{ $loaiQuangCaoMapping[$type] ?? $type }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="MaTTQC" class="form-label">Trạng thái quảng cáo <span class="text-danger">*</span></label>
                <select name="MaTTQC" class="form-control" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->MaTTQC }}" {{ old('MaTTQC', $advertisement->MaTTQC) == $status->MaTTQC ? 'selected' : '' }}>
                            {{ $status->TenTT }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="Ngaybatdau" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                <input type="date" name="Ngaybatdau" class="form-control" value="{{ old('Ngaybatdau', $advertisement->Ngaybatdau) }}" required>
            </div>
            <div class="mb-3">
                <label for="Ngayketthuc" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                <input type="date" name="Ngayketthuc" class="form-control" value="{{ old('Ngayketthuc', $advertisement->Ngayketthuc) }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật quảng cáo</button>
            <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Xử lý cho dropdown tùy chỉnh loại quảng cáo
            const typeOptions = document.querySelectorAll('.type-option');
            const selectedTypeInput = document.getElementById('selected-type');
            const selectedDisplay = document.getElementById('selected-display');
            
            typeOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const value = this.dataset.value;
                    const display = this.dataset.display;
                    
                    selectedTypeInput.value = value;
                    selectedDisplay.textContent = display;
                });
            });
        });
    </script>
@endsection