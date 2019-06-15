<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $table = 'type';

    public function Products() {
        return $this->hasMany('App\Product');
    }
}
