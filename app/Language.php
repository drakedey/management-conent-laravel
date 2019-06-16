<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{

    use SoftDeletes;

    public const SPANISH = "spanish";

    protected $fillable = [
        'name', 'user_id'
    ];

    public function User() {
        return $this->belongsTo('App\User');
    }

    public function productTypes() {
        return $this->hasMany('App\ProducTypeLanguages');
    }




    public function tagContent() {
        $this->belongsTo('App\TagContent');
    }
}
