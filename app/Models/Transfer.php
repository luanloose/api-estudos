<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    const STATUS_FAILED = 'failed';
    const STATUS_REFUSED = 'refused';
    const STATUS_SUCCESS = 'success';
    const STATUS_REFUNDED = 'refunded';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
        'status',
        'payer_id',
        'payee_id',
    ];

    protected $observables = [
        'transferUpdated',
    ];
}
