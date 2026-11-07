<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierScope3Request extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'spend_amount_cents' => 'integer',
        'allocated_emissions_tco2e' => 'decimal:4',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
