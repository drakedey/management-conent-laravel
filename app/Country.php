<?php


namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected  $table = 'countries';

    protected $fillable = [
        'name',
        'uri_param',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function divisions() {
        return $this->belongsToMany('App\Division');
    }

    public function categories() {
        return $this->belongsToMany('App\Category', "category_country");
    }

}