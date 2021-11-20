<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Comment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text'
    ];

    public function user()
    {
        //userとcommentは1対多の関係
        return $this->belongsTo(User::class);
    }

    public function getComments(Int $tweet_id)
    {
        //userとcommentは1対多の関係
        //where条件に合致するcommentと、それに紐づくuserのデータを取得
        return $this->with('user')->where('tweet_id', $tweet_id)->get();
    }

    //コメントのデータを＄dataとして受け取る
    public function commentStore(Int $user_id, Array $data)
    {
        $this->user_id = $user_id;
        $this->tweet_id = $data['tweet_id'];
        $this->text = $data['text'];
        $this->save();

        return;
    }
}
