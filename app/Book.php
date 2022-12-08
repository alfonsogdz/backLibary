<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';


    //relacion
    public function user(){
        return $this -> belongsTo('App\User', 'user_id');


    }
}


