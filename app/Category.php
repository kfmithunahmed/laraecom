<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name', 'description', 'url', 'status'
    ];

    public function categories()
    {
        return $this->hasMany('App\Category','parent_id');
    }
}
