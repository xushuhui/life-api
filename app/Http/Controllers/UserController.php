<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use EasyWeChatComposer\EasyWeChat;
use Illuminate\Support\Facades\Cache;

/**
 * @OA\Info(title="My First API", version="0.1")
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/resource.json",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function store(UserRequest $request)
    {



    }
}
