<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Member;
use App\Dept;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    /*
     public function index()
    {
        return view('home');
    }*/

    public function add(Request $request)
    {
        // パラメータ
        $param = [
            'member_number' => $request->member_number,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'sex' => $request->sex,
            'birthday' => $request->birthday,
            'email' => $request->email,
            'dept_id' => $request->dept_id,
            'tel' => $request->tel,
            'join_date' => $request->join_date,
            'leave_date' => $request->leave_date,
        ];
        /*if ($request->id) {
            $param['id'] = $request->id
        };*/
        
        // トランザクション
        DB::beginTransaction();
        try { 
            
            if ($request->id) {
                $mode = 'UPDATE';
                $member = Member::find($request->id);        
            } else {
                $member = new Member;
                $mode = 'INSERT';
            }
            $member->fill($param)->save();
            DB::commit();
            
            $insertedId = $member->id;
            $return = $member->where('id',$insertedId)->first();

            return response()->json([
                'mode' => $mode,
                'msg'  => 'Ajaxによるデータの登録が成功しました。', 
                'member_add'   => $return,
                ]);                
            
        } catch (\Exception $e) {
            // エラー時
            DB::rollback();
            return response()->json(['msg' => 'Ajaxによるデータの登録が失敗しました。'
                                ]);
        }  
    }

    public function delete(Request $request)
    {
        // トランザクション
        DB::beginTransaction();
        try {

            \App\Member::destroy($request->ids);
            DB::commit();

            return response()->json([
                'mode' => 'DELETE',
                'msg'  => 'Ajaxによるデータの削除が成功しました。', 
                'member_delete'   => $request->ids,
            ]);                
            
        } catch (\Exception $e) {
            // エラー時
            DB::rollback();
            return response()->json([
                'mode' => 'ERROR',
                'msg' => 'Ajaxによるデータの削除が失敗しました。'
            ]);
        }  


    }

    public function store(Request $request)
    {
        // パラメータ
        // 結局、回さないといけないのか？
        /*foreach ($attributes as $key => $value) {

        }*/

        /*$params = [];
        foreach ($posts as $key => $value) {
            $params[] = [
                'id' => $value['id'],
                'member_number' => $value['member_number'],
                'last_name' => $value['last_name'],
                'first_name' => $value['first_name'],
                'sex' => $value['sex'],
                'birthday' => $value['birthday'],
                'email' => $value['email'],
                'dept_id' => $value['dept_id'],
                'tel' => $value['tel'],
                'join_date' => $value['join_date'],
                'leave_date' => $value['leave_date']
            ];
        }
        */


        
        // トランザクション
        
        DB::beginTransaction();
        try { 
            $member = new Member;
            //$member->fill($attributes)->save($options);
            $member->fill($request->all())->save();            
            DB::commit();

            $members =  App\Member::all();
            $depts =  App\Dept::all();


            return response()->json(['msg'  => 'Ajaxによるデータの登録が成功しました。', 
                'members'   => $members,
                'depts' => $depts
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            
            $members =  App\Member::all();
            $depts =  App\Dept::all();
            return response()->json(['msg'  => 'Ajaxによるデータの登録が失敗しました。', 
                'members'   => $members,
                'depts' => $depts
           ]); 
        }
        
         
    }
}
