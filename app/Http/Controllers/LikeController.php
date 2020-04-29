<?php

namespace App\Http\Controllers;


use App\Models\Coupons;
use App\Models\Store;
use App\Models\User;

class LikeController extends Controller
{
    public function stores()
    {
        $likes = request()->user()->likes()->with('likeable')->paginate(20);
        return $this->setData($likes);
    }
    public function store(int $id)
    {
        $store = Store::query()->find($id);
        request()->user()->like($store);
        return $this->succeed();
    }

    public function coupon(int $id)
    {
        $coupon = Coupons::query()->find($id);
        request()->user()->like($coupon);
        return $this->succeed();
    }

    public function coupons()
    {
        $likes = request()->user()->likes()->with('likeable')->paginate(20);
        return $this->setData($likes);
    }
}
