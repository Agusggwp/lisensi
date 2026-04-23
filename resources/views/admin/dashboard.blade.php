@extends('layouts.admin')

@section('content')
    <div class="panel">
        <h2>Dashboard License Server</h2>
        <p class="muted">Monitoring status lisensi multi website client.</p>
    </div>

    <div class="grid grid-4">
        <div class="stat"><div class="label">Total License</div><div class="value">{{ $stats['total'] }}</div></div>
        <div class="stat"><div class="label">Active</div><div class="value">{{ $stats['active'] }}</div></div>
        <div class="stat"><div class="label">Expired</div><div class="value">{{ $stats['expired'] }}</div></div>
        <div class="stat"><div class="label">Suspended</div><div class="value">{{ $stats['suspended'] }}</div></div>
    </div>

    <div class="panel">
        <h3>Aktivasi / Validasi Terbaru</h3>
        <table>
            <thead>
            <tr>
                <th>Waktu</th>
                <th>Client</th>
                <th>License</th>
                <th>Domain</th>
                <th>IP</th>
                <th>Valid</th>
                <th>Reason</th>
            </tr>
            </thead>
            <tbody>
            @forelse($latestActivations as $log)
                <tr>
                    <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $log->license->client->name ?? '-' }}</td>
                    <td>{{ $log->license->license_key ?? '-' }}</td>
                    <td>{{ $log->domain }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->is_valid ? 'YES' : 'NO' }}</td>
                    <td>{{ $log->reason }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="muted">Belum ada log validasi lisensi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
