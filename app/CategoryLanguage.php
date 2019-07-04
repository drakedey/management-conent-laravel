<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryLanguage extends Model
{
    protected $table = "category_contents";

    protected $fillable = ['name', 'category_id', 'language_id'];
    protected $hidden = ['language_id'];

//    public function toArray()
//    {
//        $array = parent::toArray();
//        $array['language'] = $this->language->name;
//        return $array;
//    }

    public function category() {
        return $this->belongsTo('App\Category', "category_id");
    }

    public function language() {
        return $this->belongsTo('App\Language', "language_id");
    }
}
