@extends('customer.layouts.app')

@section('content')
@php
    // Dữ liệu mẫu, cập nhật lại mốc điểm các hạng thành viên
    $ranks = [
        [
            'name' => 'Thành viên Bạc',
            'min' => 0,
            'color' => '#b0c4de',
            'icon' => 'fa-certificate',
            'benefit' => 'Tặng 1 voucher sinh nhật, ưu đãi 5% dịch vụ.'
        ],
        [
            'name' => 'Thành viên Vàng',
            'min' => 100,
            'color' => '#ffd700',
            'icon' => 'fa-crown',
            'benefit' => 'Tặng 2 voucher sinh nhật, ưu đãi 10% dịch vụ.'
        ],
        [
            'name' => 'Thành viên Bạch Kim',
            'min' => 1000,
            'color' => '#e5e4e2',
            'icon' => 'fa-gem',
            'benefit' => 'Tặng 3 voucher, ưu đãi 12%, quà tặng đặc biệt.'
        ],
        [
            'name' => 'Thành viên Kim Cương',
            'min' => 5000,
            'color' => '#00bfff',
            'icon' => 'fa-diamond',
            'benefit' => 'Tặng 4 voucher, ưu đãi 15%, quà tặng VIP.'
        ],
    ];
    $user = auth()->user()->user ?? null;
    $total = $user ? $user->lsDiemThuong()->sum('Sodiem') : 0;
    $levels = [0, 100, 1000, 5000];
    $currentRank = $ranks[0];
    $nextRank = null;
    foreach ($ranks as $i => $rank) {
        if ($total >= $rank['min']) {
            $currentRank = $rank;
            $nextRank = $ranks[$i+1] ?? null;
        }
    }
    $progress = $nextRank ? min(100, ($total - $currentRank['min']) / ($nextRank['min'] - $currentRank['min']) * 100) : 100;
@endphp
<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('customer.diemthuong.index') }}" class="btn btn-outline-primary btn-lg fw-bold" style="border-radius: 30px; background: #fff5f7; color: var(--accent-color); border: 2px solid var(--primary-color); box-shadow: 0 2px 8px rgba(255,154,158,0.08);">
                    <i class="fas fa-history me-2"></i> Lịch sử điểm thưởng
                </a>
            </div>
            <div class="card shadow-sm text-center mb-4" style="border: none;">
                <div class="card-body py-4">
                    <div class="mb-2">
                        <i class="fas {{ $currentRank['icon'] }}" style="font-size: 2.5rem; color: {{ $currentRank['color'] }};"></i>
                    </div>
                    <h4 class="fw-bold mb-1" style="color: {{ $currentRank['color'] }};">Bạn đang là: {{ $currentRank['name'] }}</h4>
                    <div class="mb-2" style="font-size: 1.1rem; color: var(--primary-color);">Tổng điểm tích lũy: <span style="color: var(--accent-color); font-weight: 700;">{{ number_format($total) }}</span></div>
                    @if($total > 0)
                        @if($nextRank)
                            <div class="mb-2" style="color: #888;">Còn <span style="color: var(--accent-color); font-weight: 600;">{{ number_format($nextRank['min'] - $total) }}</span> điểm để lên hạng <span style="color: {{ $nextRank['color'] }}; font-weight: 600;">{{ $nextRank['name'] }}</span></div>
                        @else
                            <div class="mb-2" style="color: var(--accent-color); font-weight: 600;">Bạn đã đạt hạng cao nhất!</div>
                        @endif
                        <div class="progress mx-auto" style="height: 18px; background: #ffe3ea; max-width: 400px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%; background: linear-gradient(90deg, var(--primary-color), var(--accent-color)); font-weight: 600;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1" style="font-size: 0.95rem; color: #ff6b6b; max-width: 400px; margin: 0 auto;">
                            <span>0 đ</span>
                            <span>100 đ</span>
                            <span>1.000 đ</span>
                            <span>5.000 đ</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h5 class="fw-bold mb-3" style="color: var(--primary-color);">Các hạng thành viên & quyền lợi</h5>
            <div class="row g-4">
                @foreach($ranks as $rank)
                <div class="col-md-3">
                    <div class="card h-100 text-center border-0 shadow-sm" style="background: #fff5f7;">
                        <div class="card-body py-4">
                            <i class="fas {{ $rank['icon'] }} mb-2" style="font-size: 2.2rem; color: {{ $rank['color'] }};"></i>
                            <h6 class="fw-bold mb-1" style="color: {{ $rank['color'] }};">{{ $rank['name'] }}</h6>
                            <div class="mb-2" style="font-size: 0.98rem; color: #888;">Từ {{ number_format($rank['min']) }} đ</div>
                            <div class="mb-2" style="font-size: 0.97rem; color: var(--accent-color); min-height: 40px;">{{ $rank['benefit'] }}</div>
                            @if($currentRank['name'] === $rank['name'])
                                <span class="badge rounded-pill" style="background: var(--primary-color); color: #fff; font-size: 0.95rem;">Hạng hiện tại</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress-bar {
        font-size: 1rem;
    }
    .card {
        border-radius: 16px;
    }
    .card .fa-medal, .card .fa-certificate, .card .fa-crown, .card .fa-gem, .card .fa-diamond {
        filter: drop-shadow(0 2px 6px rgba(255, 154, 158, 0.15));
    }
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
</style>
@endpush 