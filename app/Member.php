<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable =[
        'member_number',
        'last_name',
        'first_name',
        'sex',
        'birthday',
        'email',
        'dept_id',
        'tel',
        'join_date'
    ];
    protected $guarded = ['id'];

    //belongsTo設定
    public function depts()
    {
        return $this->belongTo('AppDept', 'dept_id', 'dept_id');
    }
}