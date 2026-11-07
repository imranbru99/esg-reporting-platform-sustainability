<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalControl extends Model
{
    use BelongsToOrganization, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'last_tested_date' => 'date',
    ];

    public function signOffUser()
    {
        return $this->belongsTo(User::class, 'sign_off_user_id');
    }
}
