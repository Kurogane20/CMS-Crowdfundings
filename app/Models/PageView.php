<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = ['url', 'count'];

    // Dapat diisi sesuai kebutuhan, misalnya validasi atau relasi dengan model lain
}
