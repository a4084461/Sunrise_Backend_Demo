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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api', 'cors']], function () {
// 取得問題清單
Route::get('/Get_Question_List/{key_word?}','QA@Get_Question_List');
// 取得答案
Route::get('/Get_Answer/{q_id?}','QA@Get_Answer');
// 取得類型清單
Route::get('/Get_Type_List','QA@Get_Type_List');
// 新增問答
Route::post('/Insert_QA','QA@Insert_QA');
// 編輯問答
Route::patch('/Update_QA','QA@Update_QA');
// 刪除問答(直接刪除資料)
Route::delete('/Delete_Hard','QA@Delete_Hard');
// 刪除問答(更改資料狀態)
Route::patch('/Delete_Soft','QA@Delete_Soft');
});
