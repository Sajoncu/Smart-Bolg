<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //reletationship with user
    public function user(){
    	return $this->belongsTo('App\User');
    }

    //relationship with Category
    public function categories(){
    	return $this->belongsToMany('App\Category')->withTimestamps();
    }

    //relationship with tag
    public function tags(){
    	return $this->belongsToMany('App\Tag')->withTimestamps();
    }
}
