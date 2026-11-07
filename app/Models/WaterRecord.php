<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterRecord extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'withdrawal_volume_m3' => 'decimal:4',
        'discharge_volume_m3' => 'decimal:4',
        'recycled_volume_m3' => 'decimal:4',
        'effluent_bod_mg_l' => 'decimal:2',
        'effluent_cod_mg_l' => 'decimal:2',
        'effluent_ph' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
