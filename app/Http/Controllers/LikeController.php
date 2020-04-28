<?php

namespace App\Http\Controllers;


class LikeController extends Controller
{
    public function stores()
    {
        return $this->setData();
    }
    public function store()
    {
        return $this->succeed();
    }

    public function coupon()
    {
        return $this->succeed();
    }

    public function coupons()
    {
        return $this->setData();
    }
}
