<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'name',
        'acronym'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
