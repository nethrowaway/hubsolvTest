<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'name'
    ];
}
