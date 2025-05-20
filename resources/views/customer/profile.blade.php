@extends('customer.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Left: Thông tin tổng quan -->
        <div class="col-md-3">
            <div class="card text-center" style="background: var(--light-bg);">
                <div class="card-body">
                    @php
                        $user = auth()->user()->user ?? null;
                        $total = $user ? $user->lsDiemThuong()->sum('Sodiem') : 0;
                        $levels = [0, 2000, 4000];
                        $percent = min(100, $total >= 4000 ? 100 : ($total >= 2000 ? 50 + ($total-2000)/20 : $total/20));
                    @endphp
                    <h5 class="mb-2" style="color: var(--accent-color); font-weight: 700;">{{ $user ? $user->Hoten : '' }}</h5>
                    <div class="mb-3">
                        <span style="font-size: 1.1rem; color: var(--primary-color); font-weight: 500;">Điểm thưởng</span>
                        <div style="font-size: 2.2rem; color: var(--accent-color); font-weight: 800;">{{ number_format($total) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Content: Tabs -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Thông Tin Cá Nhân</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="points-tab" data-bs-toggle="tab" href="#points" role="tab" aria-controls="points" aria-selected="false">Lịch Sử Điểm Thưởng</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Tab Thông Tin Cá Nhân -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                            <form action="{{ route('customer.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" name="name" value="{{ auth()->user()->Hoten }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="{{ auth()->user()->Email }}" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control" name="phone" value="{{ auth()->user()->SDT }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control" name="birthday" value="{{ auth()->user()->Ngaysinh }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea class="form-control" name="address" rows="2">{{ auth()->user()->DiaChi }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Giới tính</label>
                                    <div>
                                        <label class="me-3"><input type="radio" name="gender" value="1" {{ auth()->user()->Gioitinh == 1 ? 'checked' : '' }}> Nam</label>
                                        <label><input type="radio" name="gender" value="0" {{ auth()->user()->Gioitinh == 0 ? 'checked' : '' }}> Nữ</label>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                        <!-- Tab Lịch Sử Điểm Thưởng -->
                        <div class="tab-pane fade" id="points" role="tabpanel" aria-labelledby="points-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Thời gian</th>
                                            <th>Số điểm</th>
                                            <th>Mã hóa đơn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($user ? $user->lsDiemThuong()->orderByDesc('Thoigian')->get() : []) as $ls)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($ls->Thoigian)->format('d/m/Y H:i') }}</td>
                                                <td style="color: {{ $ls->Sodiem > 0 ? 'var(--accent-color)' : '#888' }}; font-weight: 600;">{{ $ls->Sodiem > 0 ? '+' : '' }}{{ $ls->Sodiem }}</td>
                                                <td>{{ $ls->MaHD ? 'HD-' . $ls->MaHD : '' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="text-center text-muted">Chưa có lịch sử điểm thưởng</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link.active {
        color: var(--accent-color) !important;
        border-bottom: 2.5px solid var(--accent-color) !important;
        font-weight: 600;
        background: #fff5f7;
    }
    .nav-tabs .nav-link {
        color: var(--primary-color);
        font-weight: 500;
    }
    .progress-bar {
        font-size: 1rem;
    }
    .btn-primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-primary:hover {
        background: var(--accent-color);
        border-color: var(--accent-color);
    }
    .card {
        border: none;
        box-shadow: 0 0 15px rgba(255, 154, 158, 0.08);
    }
    .table thead th {
        background: #fff5f7;
        color: var(--accent-color);
        font-weight: 600;
    }
    .table-bordered > :not(caption) > * > * {
        border-color: #ffe3ea;
    }
</style>
@endpush 