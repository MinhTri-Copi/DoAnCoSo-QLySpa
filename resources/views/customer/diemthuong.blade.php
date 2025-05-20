@extends('customer.layouts.app')

@section('content')
@php
    $user = auth()->user()->user ?? null;
    $lsDiemThuong = $user ? $user->lsDiemThuong()->orderByDesc('Thoigian')->get() : collect();
@endphp
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white d-flex align-items-center justify-content-between" style="border-radius: 16px 16px 0 0; border-bottom: 2px solid var(--primary-color);">
                    <h4 class="mb-0 fw-bold" style="color: var(--primary-color); letter-spacing: 1px;"><i class="fas fa-history me-2"></i>Lịch sử điểm thưởng</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(90deg, #FF9A9E 0%, #FECFEF 100%); color: #fff;">
                                <tr>
                                    <th class="text-center">Mã lịch sử</th>
                                    <th class="text-center">Thời gian</th>
                                    <th class="text-center">Số điểm</th>
                                    <th class="text-center">Mã người dùng</th>
                                    <th class="text-center">Mã hóa đơn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lsDiemThuong as $ls)
                                    <tr style="transition: background 0.2s;">
                                        <td class="text-center fw-semibold">#{{ $ls->MaLSDT }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($ls->Thoigian)->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <span class="badge px-3 py-2" style="font-size: 1.1rem; background: {{ $ls->Sodiem > 0 ? 'var(--primary-color)' : '#ffe3ea' }}; color: {{ $ls->Sodiem > 0 ? '#fff' : '#888' }}; font-weight: 600;">
                                                <i class="fas fa-gift me-1"></i>{{ $ls->Sodiem > 0 ? '+' : '' }}{{ $ls->Sodiem }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $ls->Manguoidung }}</td>
                                        <td class="text-center">{{ $ls->MaHD ? 'HD-' . $ls->MaHD : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Chưa có lịch sử điểm thưởng</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 16px;
        box-shadow: 0 0 18px rgba(255, 154, 158, 0.10);
    }
    .table thead th {
        font-weight: 700;
        font-size: 1.08rem;
        letter-spacing: 0.5px;
        border: none;
    }
    .table-hover tbody tr:hover {
        background: #fff5f7;
        transition: background 0.2s;
    }
    .badge {
        border-radius: 8px;
        font-size: 1.08rem;
        letter-spacing: 0.5px;
    }
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.97rem;
        }
        .card-header h4 {
            font-size: 1.1rem;
        }
    }
</style>
@endpush 