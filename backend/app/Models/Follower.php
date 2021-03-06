<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected $primaryKey = [
        'following_id',
        'followed_id'
    ];
    protected $fillable = [
        'following_id',
        'followed_id'
    ];
    public $timestamps = false;
    public $incrementing = false;

    public function getFollowCount($user_id)
    {
        //フォローしている数を取得する
        return $this->where('following_id', $user_id)->count();
    }

    public function getFollowerCount($user_id)
    {
        //フォローされている数を取得する
        return $this->where('followed_id', $user_id)->count();
    }

    // フォローしているユーザのIDを取得
    public function followingIds(Int $user_id)
    {
        //getメソッドでクエリの結果を含むコレクションインスタンスを取得する
        //コレクションの中身はFollowerModelのインスタンス
        //[['followed_id' => $followed_id ], ... ]
        return $this->where('following_id', $user_id)->get('followed_id');
    }
}
