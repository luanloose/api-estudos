<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'products';

    protected $fillable = ['name', 'description', 'price', 'slug'];
}