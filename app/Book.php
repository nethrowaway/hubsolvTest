<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
        'author_id'
    ];

    protected $fillable = [
        'title',
        'author_id',
        'price',
        'isbn'
    ];

    public function author()
    {
        return $this->belongsTo('App\Author');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category')->withPivot('book_id', 'category_id');
    }
}
