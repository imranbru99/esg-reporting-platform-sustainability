<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

class Invoice extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function generatePdf(): string
    {
        $preset = $this->currency === 'BDT' ? 'bengali' : 'default';

        $document = UnicodePdf::preset($preset)
            ->loadView('invoices.invoice_pdf', [
                'invoice' => $this->load(['items', 'organization']),
            ])
            ->a4()
            ->title("Invoice #{$this->invoice_number}");

        $binary = $document->output();
        $path = "invoices/{$this->organization_id}/inv-{$this->invoice_number}.pdf";
        Storage::disk('public')->put($path, $binary);

        $this->update(['pdf_path' => $path]);

        return $path;
    }
}
