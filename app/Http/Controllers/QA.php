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

    /*
    * 新增問答
    * @param string $question 問題
    * @param string $type 類型
    * @param string $answer 答案
    * @param string $auth 使用者名稱
    * @return json ['code' => 200, 'error' =>'', 'data'=>[]]
    */
    public function Insert_QA(Request $request)
    {
        //驗證
        $validator = Validator::make(
            [
                'question' => $request->question,
                'type'     => $request->type,
                'answer'   => $request->answer,
                'auth'     => $request->auth,
            ],
            [
                'question' => 'string||required',
                'type'     => 'string||required',
                'answer'   => 'string||required',
                'auth'     => 'string||required',
            ],
            [
                'question.string'   => '必須為字串',
                'question.required' => '必須填寫',
                'type.string'       => '必須為字串',
                'type.required'     => '必須填寫',
                'answer.string'     => '必須為字串',
                'answer.required'   => '必須填寫',
                'auth.string'       => '必須為字串',
                'auth.required'     => '必須填寫',
            ]
        );
        // 驗證失敗
        if ($validator->fails()) {
            return ['code'=>100,'error'=>'參數錯誤','data'=>$validator->errors()];
        }

        try{
            $qa_insert = DB::insert('insert into question (question,type,answer,auth,state,cDate,uDate) values (?,?,?,?,?,?,?)',
            [
                $request->question,
                $request->type,
                $request->answer,
                $request->auth,
                1,
                Carbon::now('Asia/Taipei'),
                Carbon::now('Asia/Taipei')
            ]);
            // $qa_insert = DB::table('question')
            // ->insert([
            //     'question'=>$request->question,
            //     'type'=>$request->type,
            //     'answer'=>$request->answer,
            //     'auth'=>$request->auth,
            //     'state'=>1,
            //     'cDate'=>Carbon::now('Asia/Taipei'),
            //     'uDate'=>Carbon::now('Asia/Taipei'),
            // ]);
            return ['code'=>101,'error'=>'新增成功','data'=>$qa_insert];

        }catch(Exception $e){
            return ['code'=>100,'error'=>$e->getMessage(),'data'=>[]];
        }
    }

    /*
    * 編輯問答
    * @param integer $q_id 問題id
    * @param string $question 問題
    * @param string $type 類型
    * @param string $answer 答案
    * @param string $auth 使用者名稱
    * @return json ['code' => 200, 'error' =>'', 'data'=>[]]
    */
    public function Update_QA(Request $request)
    {
        //驗證
        $validator = Validator::make(
            [
                'q_id'     => $request->q_id,
                'question' => $request->question,
                'type'     => $request->type,
                'answer'   => $request->answer,
                'auth'     => $request->auth,
            ],
            [
                'q_id'     => 'integer||required',
                'question' => 'string||required',
                'type'     => 'string||required',
                'answer'   => 'string||required',
                'auth'     => 'string||required',
            ],
            [
                'q_id.integer'      => '必須為數字',
                'q_id.required'     => '必須填寫',
                'question.string'   => '必須為字串',
                'question.required' => '必須填寫',
                'type.string'       => '必須為字串',
                'type.required'     => '必須填寫',
                'answer.string'     => '必須為字串',
                'answer.required'   => '必須填寫',
                'auth.string'       => '必須為字串',
                'auth.required'     => '必須填寫',
            ]
        );
        // 驗證失敗
        if ($validator->fails()) {
            return ['code'=>200,'error'=>'參數錯誤','data'=>$validator->errors()];
        }

        try{
            $update_qa = DB::update('update question set question = ?,type = ?,answer = ?,auth = ?,uDate = ? where q_id = ? and state = 1',
            [
                $request->question,
                $request->type,
                $request->answer,
                $request->auth,
                Carbon::now('Asia/Taipei'),
                $request->q_id
            ]);
            // $update_qa = DB::table('question')
            // ->where('q_id',$request->q_id)
            // ->where('state',1)
            // ->update([
            //     'question'=>$request->question,
            //     'type'=>$request->type,
            //     'answer'=>$request->answer,
            //     'auth'=>$request->auth,
            //     'uDate'=>Carbon::now('Asia/Taipei'),
            // ]);
            return ['code'=>101,'error'=>'編輯成功','data'=>$update_qa];

        }catch(Exception $e){
            return ['code'=>100,'error'=>$e->getMessage(),'data'=>[]];
        }
    }
}
