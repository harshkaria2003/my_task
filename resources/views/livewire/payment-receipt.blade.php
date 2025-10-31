

<div class="container mt-5">
    <h2 class="mb-4 text-center fw-bold">Payment Receipt</h2>

    <div class="card shadow-sm p-4">
        <div class="row mb-2">
            <div class="col-12 col-md-6 mb-2">
                <strong>Course:</strong> {{ $enrollment->course->title }}
            </div>
            <div class="col-12 col-md-6 mb-2">
                <strong>Student:</strong> {{ $enrollment->student->name }}
            </div>
            <div class="col-12 col-md-6 mb-2">
                <strong>Amount Paid:</strong> ${{ number_format($payment->amount, 2) }}
            </div>
            <div class="col-12 col-md-6 mb-2">
                <strong>Payment Status:</strong> 
                <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
            <div class="col-12 col-md-6 mb-2">
                <strong>Transaction ID:</strong> {{ $payment->transaction_id }}
            </div>
            <div class="col-12 col-md-6 mb-2">
                <strong>Paid At:</strong> {{ $payment->paid_at->format('d M Y, h:i A') }}
            </div>
        </div>

        <div class="text-center mt-3 text-muted">
            Thank you for your payment!
        </div>
    </div>

    <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <a href="{{ route('student.courses.index') }}" class="btn btn-secondary w-100 w-md-auto text-center">
            <i class="bi bi-arrow-left me-1"></i> Back to Courses
        </a>

        <a href="{{ route('student.payment.receipt.pdf', $enrollment->id) }}" target="_blank" class="btn btn-primary w-100 w-md-auto text-center">
            <i class="bi bi-download me-1"></i> Download PDF
        </a>
    </div>
</div>


