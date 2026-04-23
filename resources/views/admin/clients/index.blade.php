@extends('layouts.admin')

@section('content')
    <div class="panel">
        <h2>Fitur Data Client</h2>
        <p class="muted">Halaman ini untuk mengelola identitas client yang dipakai saat pembuatan license.</p>
        <ul>
            <li><strong>Tambah Client:</strong> simpan data client baru.</li>
            <li><strong>Edit:</strong> perbarui nama, perusahaan, email, telepon, status aktif, dan catatan.</li>
            <li><strong>Hapus:</strong> menghapus client yang tidak lagi digunakan.</li>
        </ul>
    </div>

    <div class="panel">
        <h2>Data Client</h2>
        <form method="POST" action="{{ route('admin.clients.store') }}" class="grid grid-2">
            @csrf
            <input name="name" placeholder="Nama client" required>
            <input name="company_name" placeholder="Nama perusahaan">
            <input name="email" type="email" placeholder="Email">
            <input name="phone" placeholder="No HP">
            <textarea name="notes" placeholder="Catatan"></textarea>
            <div>
                <label><input type="checkbox" name="is_active" value="1" checked> Aktif</label>
                <div><button type="submit" class="btn btn-primary">Tambah Client</button></div>
            </div>
        </form>
    </div>

    <div class="panel">
        <table>
            <thead><tr><th>Nama</th><th>Email</th><th>Company</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->company_name }}</td>
                    <td>{{ $client->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <div class="actions">
                            <details>
                                <summary class="btn" role="button">Edit</summary>
                                <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="grid" style="min-width: 280px; margin-top: 8px;">
                                    @csrf
                                    @method('PUT')
                                    <input name="name" value="{{ $client->name }}" required>
                                    <input name="company_name" value="{{ $client->company_name }}" placeholder="Nama perusahaan">
                                    <input name="email" type="email" value="{{ $client->email }}" placeholder="Email">
                                    <input name="phone" value="{{ $client->phone }}" placeholder="No HP">
                                    <textarea name="notes" placeholder="Catatan">{{ $client->notes }}</textarea>
                                    <label><input type="checkbox" name="is_active" value="1" {{ $client->is_active ? 'checked' : '' }}> Aktif</label>
                                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                                </form>
                            </details>
                            <form method="POST" action="{{ route('admin.clients.destroy', $client) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $clients->links() }}
    </div>
@endsection
