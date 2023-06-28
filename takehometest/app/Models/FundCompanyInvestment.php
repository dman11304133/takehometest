<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundCompanyInvestment extends Model
{
    protected $fillable = [
        'fund_id',
        'company_id',
    ];

    public function fund()
    {
        return $this->belongsTo(Fund::class, 'fund_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
