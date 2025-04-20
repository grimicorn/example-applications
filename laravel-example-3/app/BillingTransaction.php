<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;
use App\Support\User\BillingTransactionType;

class BillingTransaction extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'invoice_id',
        'label',
        'description',
        'amount',
        'type',
        'provider_id',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'invoice_id' => 'integer',
        'amount' => 'float',
        'type' => 'integer',
    ];

    /**
     * Get the invoice for the transaction.
     */
    public function user()
    {
        return $this->belongsTo('Laravel\Spark\LocalInvoice');
    }

    public function isMonthly()
    {
        return $this->type === BillingTransactionType::MONTHLY_SUBSCRIPTION;
    }

    public function getAmountForDisplayAttribute()
    {
        $debit = $this->type === BillingTransactionType::ACCOUNT_DEBIT;
        $price = price($this->amount, false, 2);

        return $debit ? "({$price})" : $price;
    }
}
