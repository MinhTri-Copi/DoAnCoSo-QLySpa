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
            'benefit' => 'Tặng 1 voucher, ưu đãi 5% dịch vụ.'
        ],
        [
            'name' => 'Thành viên Vàng',
            'min' => 100,
            'color' => '#ffd700',
            'icon' => 'fa-crown',
            'benefit' => 'Tặng 2 voucher, ưu đãi 10% dịch vụ.'
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
    
    // Set fixed positions for level markers in percentage (of total width)
    $markersPositions = [
        0 => 0,      // 0đ at 0% position
        100 => 20,   // 100đ at 20% position 
        1000 => 60,  // 1.000đ at 60% position
        5000 => 100  // 5.000đ at 100% position
    ];
    
    // Calculate point position based on its value relative to the fixed markers
    function calculatePosition($value, $markersPositions) {
        if ($value <= 0) return $markersPositions[0];
        if ($value >= 5000) return $markersPositions[5000];
        
        // Find the two closest markers
        $lowerMarker = 0;
        $upperMarker = 5000;
        
        foreach (array_keys($markersPositions) as $marker) {
            if ($marker <= $value && $marker > $lowerMarker) {
                $lowerMarker = $marker;
            }
            if ($marker > $value && $marker < $upperMarker) {
                $upperMarker = $marker;
            }
        }
        
        // Calculate position using linear interpolation between markers
        $valueDiff = $value - $lowerMarker;
        $markerDiff = $upperMarker - $lowerMarker;
        $positionDiff = $markersPositions[$upperMarker] - $markersPositions[$lowerMarker];
        
        return $markersPositions[$lowerMarker] + ($valueDiff / $markerDiff) * $positionDiff;
    }
    
    // Get point position as percentage
    $pointPosition = calculatePosition($total, $markersPositions);
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
                    <div class="mb-2" style="font-size: 1.1rem; color: #333;">Tổng điểm tích lũy: <span style="color: #FF6B6B; font-weight: 700;">{{ number_format($total) }}</span></div>
                    @if($nextRank)
                        <div class="mb-2" style="color: #555;">Còn <span style="color: #FF6B6B; font-weight: 600;">{{ number_format($nextRank['min'] - $total) }}</span> điểm để lên hạng <span style="color: {{ $nextRank['color'] }}; font-weight: 600;">{{ $nextRank['name'] }}</span></div>
                    @else
                        <div class="mb-2" style="color: #FF6B6B; font-weight: 600;">Bạn đã đạt hạng cao nhất!</div>
                    @endif

                    <!-- Progress section -->
                    <div class="mt-3">
                        <!-- Point indicator bubble -->
                        <div style="position: relative; height: 45px; max-width: 400px; margin: 0 auto;">
                            <div style="position: absolute; top: 0; left: {{ $pointPosition }}%; transform: translateX(-50%);">
                                <div style="background: #ff6b6b; color: white; padding: 3px 10px; border-radius: 20px; font-size: 0.9rem; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">{{ number_format($total) }} đ</div>
                                <div style="width: 0; height: 0; border-left: 8px solid transparent; border-right: 8px solid transparent; border-top: 8px solid #ff6b6b; margin: 0 auto;"></div>
                            </div>
                        </div>
                        
                        <!-- Progress bar with markers -->
                        <div style="position: relative; max-width: 400px; margin: 0 auto;">
                            <!-- Indicator dot -->
                            <div style="position: absolute; top: 10px; left: {{ $pointPosition }}%; transform: translateX(-50%); width: 14px; height: 14px; background: white; border: 3px solid #ff6b6b; border-radius: 50%; z-index: 5;"></div>
                            
                            <!-- Progress bar -->
                            <div style="height: 20px; background-color: #ffe3ea; border-radius: 10px; position: relative; overflow: hidden;">
                                <div style="position: absolute; top: 0; left: 0; height: 100%; width: {{ $pointPosition }}%; background: linear-gradient(90deg, var(--primary-color), var(--accent-color));"></div>
                            </div>
                            
                            <!-- Evenly positioned markers -->
                            <div style="position: relative; margin-top: 8px; height: 30px; max-width: 400px;">
                                <span style="position: absolute; left: 0; font-size: 0.95rem; color: #888; white-space: nowrap;">0 đ</span>
                                <span style="position: absolute; left: 20%; transform: translateX(-50%); font-size: 0.95rem; color: #888; white-space: nowrap;">100 đ</span>
                                <span style="position: absolute; left: 60%; transform: translateX(-50%); font-size: 0.95rem; color: #888; white-space: nowrap;">1.000 đ</span>
                                <span style="position: absolute; right: 0; font-size: 0.95rem; color: #888; white-space: nowrap;">5.000 đ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h5 class="fw-bold mb-3" style="color: #FF6B6B;">Các hạng thành viên & quyền lợi</h5>
            <div class="row g-4">
                @foreach($ranks as $rank)
                <div class="col-md-3">
                    <div class="card h-100 text-center border-0 shadow-sm" style="background: #fff5f7;">
                        <div class="card-body py-4">
                            <i class="fas {{ $rank['icon'] }} mb-2" style="font-size: 2.2rem; color: {{ $rank['color'] }};"></i>
                            <h6 class="fw-bold mb-1" style="color: {{ $rank['color'] }};">{{ $rank['name'] }}</h6>
                            <div class="mb-2" style="font-size: 0.98rem; color: #555;">Từ {{ number_format($rank['min']) }} đ</div>
                            <div class="mb-2" style="font-size: 0.97rem; color: #333; min-height: 40px;">{{ $rank['benefit'] }}</div>
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