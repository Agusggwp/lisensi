@extends('layouts.admin')

@section('content')
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
                            <form method="POST" action="{{ route('admin.clients.update', $client) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="name" value="{{ $client->name }}">
                                <input type="hidden" name="company_name" value="{{ $client->company_name }}">
                                <input type="hidden" name="email" value="{{ $client->email }}">
                                <input type="hidden" name="phone" value="{{ $client->phone }}">
                                <input type="hidden" name="notes" value="{{ $client->notes }}">
                                <input type="hidden" name="is_active" value="{{ $client->is_active ? 0 : 1 }}">
                                <button class="btn" type="submit">Toggle</button>
                            </form>
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
