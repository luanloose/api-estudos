<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
	protected $table = 'consumer';

    protected $fillable = [
        'username'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
