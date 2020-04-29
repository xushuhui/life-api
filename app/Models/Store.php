<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelLike\Traits\Likeable;

class Store extends Model
{
    protected $guarded = ['id'];
    use Likeable;
}
