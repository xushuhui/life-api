<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreUsers extends Model
{
    protected $guarded = ['id'];
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
