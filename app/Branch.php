<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'user_id', 'branch_type_id', 'name'
        ];


    public function branchType() {
        return $this->belongsTo('App\Branch');
    }

    public function images() {
        return $this->hasMany('App\Image');
    }
}
