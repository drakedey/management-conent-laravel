<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name'
    ];

    protected $table = 'branch_types';

    public function branches() {
        return $this->hasMany('App\Branch', 'branch_type_id');
    }
}
