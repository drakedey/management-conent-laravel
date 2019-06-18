<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
      'state',
      'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }


}
