<?php

namespace App\Console\Commands;

use App\Mail\LicenseExpiringReminderMail;
use App\Models\License;
use App\Services\LicenseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class LicensesSyncStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'licenses:sync-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto suspend expired licenses and send reminder email H-7';

    /**
     * Execute the console command.
     */
    public function handle(LicenseService $licenseService): int
    {
        $licenseService->syncExpiredStatuses();

        $days = (int) config('services.license_server.reminder_days', 7);

        $licenses = License::query()
            ->with('client')
            ->where('status', License::STATUS_ACTIVE)
            ->whereNotNull('expires_at')
            ->whereDate('expires_at', now()->addDays($days)->toDateString())
            ->where(function ($query): void {
                $query->whereNull('last_reminder_at')
                    ->orWhereDate('last_reminder_at', '<', now()->toDateString());
            })
            ->get();

        $sent = 0;

        foreach ($licenses as $license) {
            if (! $license->client?->email) {
                continue;
            }

            Mail::to($license->client->email)
                ->queue(new LicenseExpiringReminderMail($license));

            $license->update([
                'last_reminder_at' => now(),
            ]);

            $sent++;
        }

        $this->info("Sync selesai. Reminder terkirim: {$sent}");

        return self::SUCCESS;
    }
}
