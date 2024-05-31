<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Update extends Model
{
    protected $fillable = [
        'title',
        'description',
        'campaign_id',
        'user_id',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function getImageUrls()
    {
        $images = $this->images;

        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        return array_map(function($image) {
            return asset('storage/uploads/updates/' . $image);
        }, $images ?: []);
    }
}
