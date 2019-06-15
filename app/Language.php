<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name', 'user_id'
    ];

    public function User() {
        $this->belongsTo('App\User');
    }
}
