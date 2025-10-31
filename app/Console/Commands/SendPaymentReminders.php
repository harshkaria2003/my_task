<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReminderMail;
use Carbon\Carbon;

class SendPaymentReminders extends Command
{
    protected $signature = 'payments:send-reminders';
    protected $description = 'Send this  emails to students who did not complete payment within 24 hours';

    public function handle()
    {
        $cutoff = Carbon::now()->subDay();

        $pendingEnrollments = Enrollment::where('payment_completed', false)
            ->where('created_at', '<=', $cutoff)
            ->get();

        foreach ($pendingEnrollments as $enrollment) {
            Mail::to($enrollment->student->email)->queue(new PaymentReminderMail($enrollment));

            \Log::info("Reminder sent to: " . $enrollment->student->email);
        }

        $this->info('Payment reminders sent successfully.');
    }
}
