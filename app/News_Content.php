<?php

namespace App;

use App\Language;
use Illuminate\Database\Eloquent\Model;

class News_Content extends Model
{
    protected $table = 'news_content';

    protected $fillable = [
      'title',
      'body',
      'language_id'
    ];

    public function language() {
        return $this->belongsTo(Language::class);
    }
}
