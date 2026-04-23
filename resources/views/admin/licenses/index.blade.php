@extends('layouts.admin')

@section('content')
    <div class="panel">
        <h2>Fitur Manajemen License</h2>
        <p class="muted">Halaman ini untuk membuat, memperbarui, suspend/aktifkan, perpanjang, dan memantau aktivitas validasi license.</p>
        <ul>
            <li><strong>Generate License:</strong> membuat license key baru untuk client aktif.</li>
            <li><strong>Edit:</strong> ubah nama paket, domain, IP lock, status, dan masa aktif.</li>
            <li><strong>Suspend/Active:</strong> blokir sementara atau aktifkan kembali license.</li>
            <li><strong>Perpanjang:</strong> tambah masa aktif license berdasarkan hari.</li>
            <li><strong>Riwayat Aktivasi:</strong> audit domain, IP, hasil valid/invalid, dan alasan kegagalan.</li>
        </ul>
    </div>

    <div class="panel">
        <h2>Manajemen License</h2>
        <form method="POST" action="{{ route('admin.licenses.store') }}" class="grid grid-2">
            @csrf
            <select name="client_id" required>
                <option value="">Pilih client</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
            <input name="name" placeholder="Nama paket license" required>
            <input name="domain" placeholder="Domain terkunci (contoh: client.com)" required>
            <input name="ip_lock" placeholder="IP lock (optional)">
            <select name="status"><option value="active">active</option><option value="expired">expired</option><option value="suspended">suspended</option></select>
            <input type="datetime-local" name="expires_at" required>
            <label><input type="checkbox" name="is_domain_locked" value="1" checked> Domain lock</label>
            <label><input type="checkbox" name="is_ip_locked" value="1"> IP lock</label>
            <button type="submit" class="btn btn-primary">Generate License</button>
        </form>
    </div>

    <div class="panel">
        <table>
            <thead><tr><th>Client</th><th>License Key</th><th>Domain</th><th>Status</th><th>Expired</th><th>QR Verify</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($licenses as $license)
                <tr>
                    <td>{{ $license->client->name }}</td>
                    <td>{{ $license->license_key }}</td>
                    <td>{{ $license->domain }}</td>
                    <td>{{ strtoupper($license->status) }}</td>
                    <td>{{ optional($license->expires_at)->format('d M Y H:i') }}</td>
                    <td>
                        <img
                            src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(90)->generate(route('license.verify', $license->license_key))) }}"
                            alt="QR verify"
                        >
                    </td>
                    <td>
                        <div class="actions">
                            <details>
                                <summary class="btn" role="button">Edit</summary>
                                <form method="POST" action="{{ route('admin.licenses.update', $license) }}" class="grid" style="min-width: 320px; margin-top: 8px;">
                                    @csrf
                                    @method('PUT')
                                    <input name="name" value="{{ $license->name }}" required>
                                    <input name="domain" value="{{ $license->domain }}" required>
                                    <input name="ip_lock" value="{{ $license->ip_lock }}" placeholder="IP lock (optional)">
                                    <select name="status" required>
                                        <option value="active" {{ $license->status === 'active' ? 'selected' : '' }}>active</option>
                                        <option value="expired" {{ $license->status === 'expired' ? 'selected' : '' }}>expired</option>
                                        <option value="suspended" {{ $license->status === 'suspended' ? 'selected' : '' }}>suspended</option>
                                    </select>
                                    <input type="datetime-local" name="expires_at" value="{{ optional($license->expires_at)->format('Y-m-d\\TH:i') }}" required>
                                    <label>
                                        <input type="hidden" name="is_domain_locked" value="0">
                                        <input type="checkbox" name="is_domain_locked" value="1" {{ $license->is_domain_locked ? 'checked' : '' }}>
                                        Domain lock
                                    </label>
                                    <label>
                                        <input type="hidden" name="is_ip_locked" value="0">
                                        <input type="checkbox" name="is_ip_locked" value="1" {{ $license->is_ip_locked ? 'checked' : '' }}>
                                        IP lock
                                    </label>
                                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                                </form>
                            </details>
                            <form method="POST" action="{{ route('admin.licenses.toggle-status', $license) }}">@csrf<button class="btn btn-warning" type="submit">Suspend/Active</button></form>
                            <form method="POST" action="{{ route('admin.licenses.extend', $license) }}">@csrf<input type="number" name="duration_days" min="1" placeholder="Hari" style="width:90px"><button class="btn" type="submit">Perpanjang</button></form>
                            <form method="POST" action="{{ route('admin.licenses.destroy', $license) }}">@csrf @method('DELETE')<button class="btn btn-danger" type="submit">Hapus</button></form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $licenses->links() }}
    </div>

    <div class="panel">
        <h3>Riwayat Aktivasi / Validasi License</h3>
        <table>
            <thead><tr><th>Waktu</th><th>License</th><th>Domain</th><th>IP</th><th>Valid</th><th>Reason</th></tr></thead>
            <tbody>
            @forelse($activationLogs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $log->license->license_key ?? '-' }}</td>
                    <td>{{ $log->domain }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->is_valid ? 'YES' : 'NO' }}</td>
                    <td>{{ $log->reason }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">Belum ada riwayat aktivasi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
