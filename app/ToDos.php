<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToDos extends Model
{
    protected $fillable = [ 'name', 'status', ];
}
