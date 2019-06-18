<?php

namespace App;

use App\News;
use App\Tag;
use Illuminate\Database\Eloquent\Model;

class News_Tag extends Model
{
    protected $table = 'new_tag';

    protected $fillable = [
        'new_id',
        'tag_id'
    ];

    public function new() {
        return $this->belongsTo(News::class);
    }

    public function tag() {
        return $this->belongsTo(Tag::class);
    }
}
