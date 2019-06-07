<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class baseController extends Controller
{
    protected function output($results, $httpCode = 200)
    {
        return response()->json([
            'results' => $results
        ], $httpCode);
    }
}