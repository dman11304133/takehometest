<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $fillable = [
        'name',
        'start_year',
        'manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(FundManager::class, 'manager_id');
    }

    public function aliases()
    {
        return $this->hasMany(Alias::class, 'fund_id');
    }

    public function fundCompanyInvestments()
    {
        return $this->hasMany(FundCompanyInvestment::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'fund_company_investments');
    }

}

