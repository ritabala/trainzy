<?php

namespace Database\Seeders;

use App\Models\Gym;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceDetailTax;
use App\Models\Product;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            $users = User::whereHas('roles', function ($query) use ($gym) {
                $query->where('name', 'member-' . $gym->id);
            })->limit(2)->get();

            $products = Product::where('gym_id', $gym->id)->get();
            $taxes = Tax::where('gym_id', $gym->id)->get();

            foreach ($users as $user) {
                $activeMembership = $user->latestMembership && $user->latestMembership->membership_status === 'active'
                    ? $user->latestMembership
                    : null;

                if ($activeMembership) {
                    $invoice = $this->createEmptyInvoice($user->id, $gym->id, 'Membership invoice', $activeMembership->id);

                    // Add membership detail
                    $membershipFrequency = $activeMembership->membershipFrequency;
                    $amount = $membershipFrequency->price;

                    $detail = InvoiceDetail::create([
                        'invoice_id' => $invoice->id,
                        'gym_id' => $gym->id,
                        'user_membership_id' => $activeMembership->id,
                        'membership_frequency_id' => $membershipFrequency->id,
                        'product_id' => null,
                        'name' => $activeMembership->membership->name . ' (' . $membershipFrequency->frequency->name . ')',
                        'description' => "Services: " . $activeMembership->membership->services->pluck('name')->implode(', '),
                        'quantity' => 1,
                        'unit_price' => $amount,
                        'amount' => $amount,
                    ]);

                    // Add taxes
                    $selectedTaxes = $taxes->random(rand(0, 2));
                    foreach ($selectedTaxes as $tax) {
                        InvoiceDetailTax::create([
                            'invoice_detail_id' => $detail->id,
                            'gym_id' => $gym->id,
                            'tax_id' => $tax->id,
                        ]);
                    }

                    $this->finalizeInvoice($invoice);
                }

                // Product invoices
                for ($i = 0; $i < rand(1, 3); $i++) {
                    $invoice = $this->createEmptyInvoice($user->id, $gym->id, 'Product/Custom item invoice');

                    $itemCount = rand(1, 3);
                    for ($j = 0; $j < $itemCount; $j++) {
                        $product = $products->random();
                        $quantity = rand(1, 3);
                        $amount = $product->price * $quantity;

                        $detail = InvoiceDetail::create([
                            'invoice_id' => $invoice->id,
                            'gym_id' => $gym->id,
                            'product_id' => $product->id,
                            'name' => $product->name,
                            'description' => 'Product purchase',
                            'quantity' => $quantity,
                            'unit_price' => $product->price,
                            'amount' => $amount,
                        ]);

                        $selectedTaxes = $taxes->random(rand(0, 2));
                        foreach ($selectedTaxes as $tax) {
                            InvoiceDetailTax::create([
                                'invoice_detail_id' => $detail->id,
                                'gym_id' => $gym->id,
                                'tax_id' => $tax->id,
                            ]);
                        }
                    }

                    $this->finalizeInvoice($invoice);
                }
            }
        }
    }

    protected function createEmptyInvoice($userId, $gymId, $notes, $membershipId = null): Invoice
    {
        $invoiceDate = now()->subDays(rand(1, 30));
        $dueDate = $invoiceDate->copy()->addDays(7);

        return Invoice::create([
            'user_id' => $userId,
            'gym_id' => $gymId,
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate,
            'invoice_prefix' => 'INV-',
            'invoice_number' => Invoice::max('id') + 1,
            'status' => 'unpaid',
            'sub_total' => 0,
            'discount_type' => '%',
            'discount_value' => 0,
            'discount_amount' => 0,
            'total_amount' => 0,
            'notes' => $notes,
            'user_membership_id' => $membershipId,
        ]);
    }

    protected function finalizeInvoice(Invoice $invoice): void
    {
        $subTotal = $invoice->details->sum('amount');

        // Random discount
        $discountType = '%';
        $discountValue = rand(5, 20);
        $discountAmount = ($discountType === '%' && $subTotal > 0)
            ? ($subTotal * $discountValue) / 100
            : 0;

        $taxAmount = 0;

        foreach ($invoice->details as $detail) {
            $proportion = $subTotal > 0 ? $detail->amount / $subTotal : 0;
            $discountedItemAmount = $detail->amount - ($discountAmount * $proportion);

            foreach ($detail->taxes as $taxItem) {
                $taxRate = $taxItem->tax->tax_percent;
                $itemTax = ($discountedItemAmount * $taxRate) / 100;
                $taxAmount += $itemTax;
            }
        }

        $totalAmount = ($subTotal - $discountAmount) + $taxAmount;

        $invoice->update([
            'sub_total' => round($subTotal, 2),
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_amount' => round($discountAmount, 2),
            'total_amount' => round($totalAmount, 2),
        ]);
    }
}
