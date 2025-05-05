@extends('backend.layouts.app')

@section('title', 'Sửa hạng thành viên')

@section('content')
    <div class="container-fluid">
        <h1>Sửa hạng thành viên</h1>

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

        <form action="{{ route('admin.membership_ranks.update', $rank->Mahang) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="Mahang" class="form-label">Mã hạng</label>
                <input type="text" class="form-control" value="{{ $rank->Mahang }}" disabled>
            </div>
            <div class="mb-3">
                <label for="Tenhang" class="form-label">Tên hạng <span class="text-danger">*</span></label>
                <select name="Tenhang" class="form-control" required>
                    @foreach ($rankTypes as $type)
                        <option value="{{ $type }}" {{ old('Tenhang', $rank->Tenhang) == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="Manguoidung" class="form-label">Người dùng <span class="text-danger">*</span></label>
                <select name="Manguoidung" class="form-control" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->Manguoidung }}" {{ old('Manguoidung', $rank->Manguoidung) == $user->Manguoidung ? 'selected' : '' }}>
                            {{ $user->Hoten }} (Mã: {{ $user->Manguoidung }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật hạng thành viên</button>
            <a href="{{ route('admin.membership_ranks.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection