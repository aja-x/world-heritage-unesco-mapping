<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/world-heritage-list', function () {
//    $response = Http::get('https://examples.opendatasoft.com/explore/dataset/world-heritage-unesco-list/download?format=geojson');
//
//    if ($response->successful()) {
//        return response()->json($response->json());
//    }
//
//    return response()->json(['message' => 'Failed'], 404);

    $file = File::get(public_path('assets/geojson/world-heritage-unesco-list.geojson'));

    return response()->json(json_decode($file, true, 512, JSON_THROW_ON_ERROR));
})->name('api.world-heritage-list');
