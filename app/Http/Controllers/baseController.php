<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class baseController extends Controller
{
    public function index()
    {
        return response()->json([
            'version' => config('api.version')
        ]);
    }
}