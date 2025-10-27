<?php
namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Events\CourseEnrolled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Storage;
use App\Models\PaymentReceipt;

use Barryvdh\DomPDF\Facade\Pdf;  


class PaymentController extends Controller
{
    public function checkout($enrollmentId)
    {
        $enrollment = Enrollment::with('course')->findOrFail($enrollmentId);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $enrollment->course->title,
                    ],
                    'unit_amount' => $enrollment->course->price * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
          'success_url' => route('student.payment.success', ['enrollment' => $enrollment->id]) . '?session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' => route('student.payment.cancel', $enrollment->id),

        ]);

        return redirect($session->url);
    } 

    public function success($enrollmentId, Request $request)
{
    $enrollment = Enrollment::with('course')->findOrFail($enrollmentId);

    $sessionId = $request->query('session_id');

    if (!$sessionId) {
        return redirect()->route('student.courses.index')->with('error', 'Invalid payment session.');
    }

  
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    $session = \Stripe\Checkout\Session::retrieve($sessionId);

    $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);

    $enrollment->update(['payment_completed' => true]);

   Payment::updateOrCreate(
    ['transaction_id' => $paymentIntent->id],
    [
        'enrollment_id' => $enrollment->id,
        'payment_gateway' => 'stripe',
        'amount' => $paymentIntent->amount_received / 100,
        'status' => $paymentIntent->status,
        'paid_at' => now(),
    ]
);


  
    event(new CourseEnrolled($enrollment));

return redirect()->route('student.payment.receipt', $enrollment->id)
                 ->with('success', 'Payment completed successfully! Download your receipt below.');


}


    public function cancel($enrollmentId)
    {
        return redirect()->route('student.courses.index')->with('error', 'Payment cancelled.');
    } 

     public function storeTransaction(Request $request, $enrollmentId)
    {
    
        $data = $request->validate([
            'payment_gateway' => 'required|string',
            'transaction_id' => 'required|string|unique:payments,transaction_id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        $enrollment = Enrollment::findOrFail($enrollmentId);

     
        $payment = Payment::create([
            'enrollment_id' => $enrollment->id,
            'payment_gateway' => $data['payment_gateway'],
            'transaction_id' => $data['transaction_id'],
            'amount' => $data['amount'],
            'status' => $data['status'],
            'paid_at' => $data['status'] === 'success' ? Carbon::now() : null,
        ]);

        
        if ($data['status'] === 'success') {
            $enrollment->payment_completed = true;
            $enrollment->save();
        }

        return redirect()->route('student.enrollments')->with('success', 'Payment processed successfully!');
    } 

public function handleWebhook(Request $request)
{
  
    $payload = $request->getContent();
    $signature = $request->header('Stripe-Signature');
    $webhookSecret = config('services.stripe.webhook_secret');

  
    try {
        $event = \Stripe\Webhook::constructEvent($payload, $signature, $webhookSecret);
    } catch (\UnexpectedValueException $e) {
   
        return response('Invalid payload', 400);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
      
        return response('Invalid signature', 400);
    }

   
    if ($event->type == 'payment_intent.succeeded') {
       
        $paymentIntent = $event->data->object;

  
        $existingPayment = \App\Models\Payment::where('transaction_id', $paymentIntent->id)->first();

        if (!$existingPayment) {
       
            $metadata = $paymentIntent->metadata;
            $enrollmentId = $metadata->enrollment_id ?? null;

            if ($enrollmentId) {
                
                $enrollment = \App\Models\Enrollment::find($enrollmentId);

                if ($enrollment) {
                 
                    $enrollment->payment_completed = true;
                    $enrollment->save();

    
                    \App\Models\Payment::create([
                        'enrollment_id' => $enrollment->id,
                        'payment_gateway' => 'stripe',
                        'transaction_id' => $paymentIntent->id,
                        'amount' => $paymentIntent->amount_received / 100,  
                        'status' => $paymentIntent->status,
                        'paid_at' => now(),
                    ]);

              
                    event(new \App\Events\CourseEnrolled($enrollment));
                }
            }
        }
    }

  
    return response('Webhook handled', 200);
}




public function showReceipt($enrollmentId)
{
    $enrollment = Enrollment::with(['course', 'student', 'payment'])->findOrFail($enrollmentId);
    $payment = $enrollment->payment;

    if (!$payment || !$enrollment->payment_completed) {
        return redirect()->route('student.enrollments')->with('error', 'Payment not found or not completed.');
    }

    return view('receipts.payments', compact('enrollment', 'payment'));
}


public function downloadReceiptPdf($enrollmentId)
{
    $enrollment = Enrollment::with(['course', 'student', 'payment'])->findOrFail($enrollmentId);
    $payment = $enrollment->payment;

    if (!$payment || !$enrollment->payment_completed) {
        return redirect()->route('student.enrollments')->with('error', 'Payment not found or not completed.');
    }

    $pdf = PDF::loadView('receipts.payments_pdf', compact('enrollment', 'payment'));

    $fileName = 'receipt_' . $enrollment->id . '.pdf';
    $filePath = 'receipts/' . $fileName;

   
    Storage::put($filePath, $pdf->output());

    
    PaymentReceipt::updateOrCreate(
        ['enrollment_id' => $enrollment->id],
        ['file_path' => $filePath]
    );

    
    return Storage::download($filePath);
}



}
