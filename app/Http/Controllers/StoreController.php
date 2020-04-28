<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        return $this->setData();
    }

    public function search()
    {
        return $this->setData();
    }

    public function filter()
    {
        return $this->setData();
    }

    public function show(int $id)
    {
        return $this->setData();
    }
}
