<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class QA extends Controller
{

    public function __construct()
    {

    }

    /*
    * 取得問題清單
    * @param string $key_word 關鍵字
    * @return json ['code' => 200, 'error' =>'', 'data'=>[]]
    */
    public function Get_Question_List($key_word = '')
    {
        //驗證
        $validator = Validator::make(
            [
                'key_word'  => $key_word,
            ],
            [
                'key_word'  => 'string||nullable',
            ],
            [
                'key_word.string'    => '必須為字串',
            ]
        );
        // 驗證失敗
        if ($validator->fails()) {
            return ['code'=>200,'error'=>'參數錯誤','data'=>$validator->errors()];
        }

        try{
            // 如果有key_word
            if(isset($key_word)){
                $question_list = DB::select('select question,cDate from question where question like ?',['%'.$key_word.'%']);
                // $question_list = DB::table('question')
                // ->select('question','cDate')
                // ->where('question','like','%'.$key_word.'%')
                // ->where('state',1)
                // ->get();
            }

            return ['code'=>101,'error'=>'','data'=>$question_list];

        }catch(Exception $e){
            return ['code'=>100,'error'=>'查詢錯誤','data'=>$e->getMessage()];
        }
    }

    /*
    * 取得答案
    * @param string $q_id 問題id
    * @return json ['code' => 200, 'error' =>'', 'data'=>[]]
    */
    public function Get_Answer($q_id = 0)
    {
        //驗證
        $validator = Validator::make(
            [
                'q_id'         => $q_id,
            ],
            [
                'q_id'         => 'integer||nullable',
            ],
            [
                'q_id.integer' => '必須為數字',
            ]
        );
        // 驗證失敗
        if ($validator->fails()) {
            return ['code'=>200,'error'=>'參數錯誤','data'=>$validator->errors()];
        }
        
        try{
            // 如果q_id不等於0
            if($q_id != 0){
                $answer = DB::select('select * from question where q_id = ? and state = ?',[$q_id,1]);
                // $answer = DB::table('question')
                // ->select('*')
                // ->where('q_id',$q_id)
                // ->wehre('state',1)
                // ->get();
            } else {

                return ['code'=>100,'error'=>'查詢錯誤','data'=>'查無此問題'];
            }

            return ['code'=>101,'error'=>'','data'=>$answer];

        }catch(Exception $e){
            return ['code'=>100,'error'=>'查詢錯誤','data'=>$e->getMessage()];
        }
    }

    /*
    * 取得類型清單
    * @return json ['code' => 200, 'error' =>'', 'data'=>[]]
    */
    public function Get_Type_List()
    {
        try{
            $type_list = DB::select('select type from question where state = 1 group by type');
            // $type_list = DB::table('question')
            // ->select('type')
            // ->groupBy('type')
            // ->wehre('state',1)
            // ->get();
            return ['code'=>101,'error'=>'','data'=>$type_list];

        }catch(Exception $e){
            return ['code'=>100,'error'=>'查詢錯誤','data'=>$e->getMessage()];
        }
    }
}
