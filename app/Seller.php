<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
	protected $table = 'seller';

    protected $fillable = [
        'razao_social', 'nome_fantasia', 'cnpj', 'username'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
