<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundManager extends Model
{
    protected $fillable = [
        'name',
    ];

    public function funds()
    {
        return $this->hasMany(Fund::class, 'manager_id');
    }
}
