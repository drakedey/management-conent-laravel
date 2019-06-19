<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'type', 'url', 'product_id', 'branch_id', 'new_id'
    ];

    public function branch() {
        return $this->belongsTo('App\Branch');
    }
}
