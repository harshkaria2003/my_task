<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Enrollment;

class PaymentReceipt extends Component
{
    public $enrollment;
    public $payment;

    public function mount($enrollmentId)
    {
        $this->enrollment = Enrollment::with(['course', 'student', 'payment'])->findOrFail($enrollmentId);
        $this->payment = $this->enrollment->payment;

        if (!$this->payment || !$this->enrollment->payment_completed) {
            session()->flash('error', 'Payment not found or not completed.');
        }
    }

    public function render()
    {
        return view('livewire.payment-receipt');
    }
}
