<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends baseController
{
    public function index()
    {
        return response()->json([
            'version' => config('api.version')
        ]);
    }
}