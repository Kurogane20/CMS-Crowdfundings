<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Update extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function get_image_url(){
        if ($this->image){
            $img_url = url('/storage/uploads/updates/'.$this->image);
        }else{
            $img_url = asset('assets/images/placeholder.png');
        }
        return $img_url;
    }
}
