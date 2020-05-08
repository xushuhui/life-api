<?php

namespace App\Models;

use Overtrue\LaravelLike\Traits\Likeable;

class Store extends Model
{
    use Likeable;
    
    public function coupon()
    {
        return $this->hasMany(Coupons::class);
    }
}
