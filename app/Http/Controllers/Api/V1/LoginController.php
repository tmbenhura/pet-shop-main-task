<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Login into api
     */
    public function __invoke(Request $request)
    {
        return response()->json(
            [
                'success' => true,
            ]
        );
    }
}
