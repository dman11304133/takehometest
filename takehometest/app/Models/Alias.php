<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    protected $fillable = ['alias'];

    public function fund()
    {
        return $this->belongsTo(Fund::class, 'fund_id');
    }
}
