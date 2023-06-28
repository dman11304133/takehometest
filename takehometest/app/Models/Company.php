<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
    ];

    public function investments()
    {
        return $this->hasMany(FundCompanyInvestment::class, 'company_id');
    }
}
