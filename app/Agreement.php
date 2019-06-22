<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agreement extends Model
{
    protected $table="agreements";

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'id_contact',
        'person_contact'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function products() {
        return $this->hasMany('App\Products');
    }

}
