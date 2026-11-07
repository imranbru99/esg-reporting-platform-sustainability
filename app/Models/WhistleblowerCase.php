<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhistleblowerCase extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $attributes = [
        'status' => 'submitted',
        'priority' => 'high',
    ];

    public function investigator()
    {
        return $this->belongsTo(User::class, 'assigned_investigator_id');
    }
}
