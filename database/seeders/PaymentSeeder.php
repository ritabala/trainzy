<?php

namespace Database\Seeders;

use App\Models\Gym;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            $invoices = Invoice::where('gym_id', $gym->id)->get();
            $users = User::where('gym_id', $gym->id)->get();
            $paymentModes = ['cash', 'bank_transfer', 'cheque', 'credit_card', 'debit_card', 'mobile_money', 'other'];
            // $statuses = ['pending', 'completed', 'failed', 'partially_paid', 'cancelled', 'refunded'];

            foreach ($invoices as $invoice) {
                // Randomly decide if this invoice should be unpaid (30% chance), partially paid (40% chance), or fully paid (30% chance)
                $status = rand(1, 10);
                
                if ($status <= 3) {
                    // Unpaid - no payments
                    $invoice->update(['status' => 'unpaid']);
                    continue;
                }
                
                // For partially paid and fully paid invoices
                $paymentCount = ($status <= 7) ? 1 : rand(1, 2); // Partially paid gets 1 payment, fully paid gets 1-2 payments
                $remainingAmount = $invoice->total_amount;
                
                for ($i = 0; $i < $paymentCount; $i++) {
                    $paymentMode = $paymentModes[rand(0, 6)];
                    
                    // For partially paid invoices, pay 30-70% of the total amount
                    // For fully paid invoices, last payment covers remaining amount
                    if ($status <= 7 && $i === 0) {
                        $amountPaid = max(0, $invoice->total_amount * (rand(30, 70) / 100));
                    } else {
                        $amountPaid = ($i === $paymentCount - 1) 
                            ? max(0, $remainingAmount) 
                            : max(0, min(rand(100, 1000), $remainingAmount));
                    }
                    
                    // Skip if amount is 0 or negative
                    if ($amountPaid <= 0) {
                        continue;
                    }
                    
                    $remainingAmount -= $amountPaid;

                    Payment::create([
                        'user_id' => $invoice->user_id,
                        'gym_id' => $gym->id,
                        'invoice_id' => $invoice->id,
                        'amount_paid' => $amountPaid,
                        'transaction_no' => 'TRX-' . fake()->unique()->numberBetween(1000, 9999),
                        'payment_date' => now()->subDays(rand(1, 30))->format('Y-m-d H:i:s'),
                        'status' => 'completed',
                        'payment_mode' => $paymentMode,
                        'remarks' => 'Sample payment remarks',
                    ]);
                }

                // Update invoice status based on remaining amount
                if ($remainingAmount <= 0) {
                    $invoice->update(['status' => 'paid']);
                } elseif ($remainingAmount < $invoice->total_amount) {
                    $invoice->update(['status' => 'partially_paid']);
                } else {
                    $invoice->update(['status' => 'unpaid']);
                }
            }
        }
    }
} 