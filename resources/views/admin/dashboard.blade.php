@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Dashboard | License Server</h2>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Clients</div>
                            <h3 class="m-0">{{ $stats['total_clients'] }}</h3>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">👥</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Active Licenses</div>
                            <h3 class="m-0">{{ $stats['active_clients'] }}</h3>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">✅</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Expired Licenses</div>
                            <h3 class="m-0">{{ $stats['expired_clients'] }}</h3>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">⏰</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Suspended</div>
                            <h3 class="m-0">{{ $stats['suspended_clients'] }}</h3>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">🚫</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Expiring Soon -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning bg-opacity-10">
                    <h5 class="m-0">⏳ Lisensi Akan Kadaluarsa (7 Hari)</h5>
                </div>
                <div class="card-body">
                    @if($expiringClients->count() > 0)
                        <div class="list-group">
                            @foreach($expiringClients as $client)
                                <a href="{{ route('admin.clients.show', $client) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $client->name }}</h6>
                                            <p class="mb-0 small text-muted">{{ $client->domain }}</p>
                                        </div>
                                        <span class="badge bg-warning">{{ $client->daysUntilExpiry() }} hari</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center m-3">Tidak ada lisensi yang akan kadaluarsa</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info bg-opacity-10">
                    <h5 class="m-0">📊 Aktivitas License Check Terakhir</h5>
                </div>
                <div class="card-body">
                    @if($recentChecks->count() > 0)
                        <div class="list-group">
                            @foreach($recentChecks as $check)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                {{ $check->client ? $check->client->name : 'Unknown' }}
                                            </h6>
                                            <p class="mb-0 small text-muted">
                                                {{ $check->domain }} • {{ $check->created_at->format('H:i:s') }}
                                            </p>
                                        </div>
                                        <span class="badge bg-{{ $check->status === 'active' ? 'success' : ($check->status === 'expired' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($check->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center m-3">Belum ada aktivitas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <a href="{{ route('admin.clients.index') }}" class="btn btn-primary">
                Kelola Clients →
            </a>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .border-left-primary { border-left: 3px solid #667eea; }
    .border-left-success { border-left: 3px solid #28a745; }
    .border-left-warning { border-left: 3px solid #ffc107; }
    .border-left-danger { border-left: 3px solid #dc3545; }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
