<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPasswordNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $userId,
        public readonly string $plainPassword,
    ) {}

    /**
     * Execute the job.
     * TODO: Implement actual email + WhatsApp delivery here.
     * Consider using Kafka or a dedicated notification service.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);

        if (! $user) {
            return;
        }

        // --- Placeholder: Email delivery ---
        // Mail::to($user->email)->send(new NewUserPasswordMail($user, $this->plainPassword));

        // --- Placeholder: WhatsApp delivery ---
        // WhatsAppService::send($user->detailsUser?->phone_number, "Password Anda: {$this->plainPassword}");
    }
}
