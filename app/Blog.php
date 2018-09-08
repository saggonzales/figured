<?php

// namespace App;

// use Illuminate\Database\Eloquent\Model;

// class Blog extends Model
// {
//     //
// }


namespace App;


use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


class Blog extends Eloquent
{
	protected $connection = 'mongodb';
	protected $collection = 'books';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'detail'
    ];
}