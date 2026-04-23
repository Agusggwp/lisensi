@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Manajemen Clients</h2>
            <p class="text-muted m-0">Total: {{ $clients->total() }} | Aktif: {{ $stats['active_clients'] }} | Kadaluarsa: {{ $stats['expired_clients'] }}</p>
        </div>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">+ Tambah Client</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, email, domain..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Dibekukan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary w-100">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Domain</th>
                        <th>Status</th>
                        <th>Kadaluarsa</th>
                        <th>Sisa Hari</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>
                                <strong>{{ $client->name }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">{{ $client->email }}</small>
                            </td>
                            <td>
                                <code>{{ $client->domain }}</code>
                            </td>
                            <td>
                                @if($client->status === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($client->status === 'expired')
                                    <span class="badge bg-warning">Kadaluarsa</span>
                                @else
                                    <span class="badge bg-danger">Dibekukan</span>
                                @endif
                            </td>
                            <td>{{ $client->expired_at->format('d M Y') }}</td>
                            <td>
                                @if($client->isExpired())
                                    <span class="badge bg-danger">Hangus</span>
                                @else
                                    <span>{{ $client->daysUntilExpiry() }} hari</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.clients.show', $client) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Lihat">👁</a>
                                    <a href="{{ route('admin.clients.edit', $client) }}" 
                                       class="btn btn-sm btn-outline-secondary" title="Edit">✏️</a>
                                    <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Yakin ingin menghapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $clients->links() }}
    </div>
</div>

<style>
    code { background: #f5f5f5; padding: 2px 6px; border-radius: 3px; }
</style>
@endsection
