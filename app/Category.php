<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //relatinship with Post
    public function posts(){
    	return $this->belongsToMany('App\Post')->withTimestamps();
    }
}
