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

    /**
     * 新規登録
     */
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
                'msg'  => 'Axiosによるデータの' . $mode . '処理が成功しました。', 
                'member_add'   => $return,
                ]);                
            
        } catch (\Exception $e) {
            // エラー時
            DB::rollback();
            return response()->json(['msg' => 'Axiosによるデータの登録が失敗しました。'
                                ]);
        }  
    }

    /**
     * 物理削除
     */
    public function delete(Request $request)
    {
        // トランザクション
        DB::beginTransaction();
        try {

            Member::destroy($request->ids);
            DB::commit();

            return response()->json([
                'mode' => 'DELETE',
                'msg'  => 'Axiosによるデータの削除が成功しました。', 
                'member_delete'   => $request->ids,
            ]);                
            
        } catch (\Exception $e) {
            // エラー時
            DB::rollback();
            return response()->json([
                'mode' => 'ERROR',
                'msg' => 'Axiosによるデータの削除が失敗しました。'
            ]);
        }  
    }

    /**
     * 保存処理
     */
    /*
     public function store(Request $request)
    {
        // トランザクション
        
        DB::beginTransaction();
        try { 
            $member = new Member;
            $member->fill($request->all())->save();            
            DB::commit();

            $members = Member::all();
            $depts   = Dept::all();

            return response()->json(['msg'  => 'Axiosによるデータの登録が成功しました。', 
                'members' => $members,
                'depts'   => $depts
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            
            $members = Member::all();
            $depts   = Dept::all();
            return response()->json(['msg'  => 'Axiosによるデータの登録が失敗しました。', 
                'members' => $members,
                'depts'   => $depts
           ]); 
        }
    }*/

    /**
     * 初期化：members_bkのテーブルを、memberに丸ごとコピー
     */
    public function init(Request $request)
    {
        Member::truncate();
        DB::insert("INSERT INTO members SELECT * FROM members_bk");

        $members = Member::all();
        $depts   = Dept::all();
        return response()->json(['msg'  => 'テーブルを初期化しました。',
            'members' => $members, 
            'depts'   => $depts
        ]); 
    }

}
