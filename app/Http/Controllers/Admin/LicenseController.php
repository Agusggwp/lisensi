<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\License;
use App\Models\LicenseActivationLog;
use App\Services\LicenseService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View as ViewResponse;
use Illuminate\View\View;

class LicenseController extends Controller
{
    public function __construct(private readonly LicenseService $licenseService)
    {
    }

    public function index(): View
    {
        return view('admin.licenses.index', [
            'licenses' => License::query()->with('client')->latest()->paginate(10),
            'clients' => Client::query()->where('is_active', true)->orderBy('name')->get(),
            'activationLogs' => LicenseActivationLog::query()
                ->with('license.client')
                ->latest()
                ->limit(15)
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255'],
            'ip_lock' => ['nullable', 'ip'],
            'status' => ['required', 'in:active,expired,suspended'],
            'expires_at' => ['required', 'date'],
            'is_domain_locked' => ['nullable', 'boolean'],
            'is_ip_locked' => ['nullable', 'boolean'],
        ]);

        $license = License::query()->create([
            ...$validated,
            'license_key' => $this->licenseService->generateLicenseKey(),
            'issued_at' => now(),
            'is_domain_locked' => $request->boolean('is_domain_locked', true),
            'is_ip_locked' => $request->boolean('is_ip_locked', false),
        ]);

        $license->update([
            'encrypted_token' => $this->licenseService->generateEncryptedToken($license),
        ]);

        return redirect()->route('admin.licenses.index')->with('success', 'License berhasil dibuat.');
    }

    public function update(Request $request, License $license): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255'],
            'ip_lock' => ['nullable', 'ip'],
            'status' => ['required', 'in:active,expired,suspended'],
            'expires_at' => ['required', 'date'],
            'is_domain_locked' => ['nullable', 'boolean'],
            'is_ip_locked' => ['nullable', 'boolean'],
        ]);

        $license->update([
            ...$validated,
            'is_domain_locked' => $request->boolean('is_domain_locked', true),
            'is_ip_locked' => $request->boolean('is_ip_locked', false),
        ]);

        $license->update([
            'encrypted_token' => $this->licenseService->generateEncryptedToken($license),
        ]);

        return redirect()->route('admin.licenses.index')->with('success', 'License berhasil diperbarui.');
    }

    public function destroy(License $license): RedirectResponse
    {
        $license->delete();

        return redirect()->route('admin.licenses.index')->with('success', 'License berhasil dihapus.');
    }

    public function toggleStatus(License $license): RedirectResponse
    {
        $newStatus = $license->status === License::STATUS_SUSPENDED
            ? License::STATUS_ACTIVE
            : License::STATUS_SUSPENDED;

        $license->update(['status' => $newStatus]);

        return redirect()->route('admin.licenses.index')->with('success', 'Status license diperbarui.');
    }

    public function extend(Request $request, License $license): RedirectResponse
    {
        $data = $request->validate([
            'duration_days' => ['required', 'integer', 'min:1', 'max:3650'],
        ]);

        $durationDays = (int) $data['duration_days'];

        $currentExpiry = $license->expires_at instanceof Carbon
            ? $license->expires_at
            : ($license->expires_at ? Carbon::parse($license->expires_at) : null);

        $baseDate = $currentExpiry && $currentExpiry->isFuture()
            ? $currentExpiry
            : now();

        $license->update([
            'expires_at' => $baseDate->copy()->addDays($durationDays),
            'status' => License::STATUS_ACTIVE,
        ]);

        return redirect()->route('admin.licenses.index')->with('success', 'Masa aktif license berhasil diperpanjang.');
    }

    public function verify(string $licenseKey): ViewResponse
    {
        $license = License::query()->where('license_key', $licenseKey)->with('client')->first();

        return view('license.verify', [
            'license' => $license,
        ]);
    }
}
