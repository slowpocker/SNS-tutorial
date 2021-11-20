<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tweet extends Model
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
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getUserTimeLine(Int $user_id)
    {
        //$user_idに一致するツイートを日付降順（新しい順）で50件ずつ取得する
        return $this->where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(50);
    }

    public function getTweetCount(Int $user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }

    // 一覧画面
    public function getTimeLines(Int $user_id, Array $follow_ids)
    {
        // 自身とフォローしているユーザIDを結合する
        $follow_ids[] = $user_id;
        //自身とフォローしているユーザのツイートを日付降順で50件ずつ取得する
        return $this->whereIn('user_id', $follow_ids)->orderBy('created_at', 'DESC')->paginate(50);
    }

    // 詳細画面
    public function getTweet(Int $tweet_id)
    {
        //first()の戻り値はTweetクラスのオブジェクト
        return $this->with('user')->where('id', $tweet_id)->first();
    }

    public function tweetStore(Int $user_id, Array $data)
    {
        //ツイートを保存する
        $this->user_id = $user_id;
        $this->text = $data['text'];
        $this->save();

        return;
    }

    public function getEditTweet(Int $user_id, Int $tweet_id)
    {
        return $this->where('user_id', $user_id)->where('id', $tweet_id)->first();
    }

    public function tweetUpdate(Int $tweet_id, Array $data)
    {
        $this->id = $tweet_id;
        $this->text = $data['text'];
        $this->update();

        return;
    }

    public function tweetDestroy(Int $user_id, $tweet_id)
    {
        //delete:影響を与えたレコード数が戻り値となる
        return $this->where('user_id', $user_id)->where('id', $tweet_id)->delete();
    }
}
