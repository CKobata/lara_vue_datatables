<?php

use Illuminate\Http\Request;

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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('/member', function (Request $request) {
    $members =  App\Member::all();
    $depts =  App\Dept::all();
    
    return response()->json(['members' => $members, 'depts' => $depts]);
});

Route::post('/member', 'MemberController@store');

Route::post('/member/add', 'MemberController@add');

Route::post('/member/delete', 'MemberController@delete');

Route::get('/member/init', 'MemberController@init');





