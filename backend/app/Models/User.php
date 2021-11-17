<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'screen_name',
        'name',
        'profile_image',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function followers()
    {
        //多対多のリレーションの定義をする
        //belongsToMany('関係するモデル', '中間テーブルのテーブル名', '中間テーブル内で対応しているID名', '関係するモデルで対応しているID名');
        //自分をフォローしているuserを取得する
        return $this->belongsToMany(self::class, 'followers', 'followed_id', 'following_id');
    }

    public function follows()
    {
        //自分がフォローしているuserを取得する
        return $this->belongsToMany(self::class, 'followers', 'following_id', 'followed_id');
    }

    public function getAllUsers(Int $user_id)
    {
        //引数で受け取ったユーザを１ページにつき5人取得する(クエリビルダ)
        return $this->Where('id', '<>', $user_id)->paginate(5);
    }

    //フォローする
    public function follow(Int $user_id)
    {
        //attachは完全重複を含め、全てのデータが中間テーブル''に保存される
        return $this->follows()->attach($user_id);
    }

    // フォロー解除する
    public function unfollow(Int $user_id)
    {
        //detachで削除する
        return $this->follows()->detach($user_id);
    }

    // 自分が相手をフォローしているか判定する
    public function isFollowing(Int $user_id)
    {
        //フォローしているuserを取得して、followed_idと$user_idが一致するものがあればtrue,なければfalseが戻り値となる
        return (boolean) $this->follows()->where('followed_id', $user_id)->first(['id']);
    }

    // 自分が相手からフォローされているか判定する
    public function isFollowed(Int $user_id)
    {
        return (boolean) $this->followers()->where('following_id', $user_id)->first(['id']);
    }

    public function updateProfile(Array $data)
    {
        if (isset($data['profile_image'])) {
            //'profile_image'が空でなければプロフ画像を保存する
            $file_name = $data['profile_image']->store('public/profile_image/');

            $this::where('id', $this->id)
                ->update([
                    'screen_name'   => $data['screen_name'],
                    'name'          => $data['name'],
                    //basename($path)でパスからファイル名を取得する
                    'profile_image' => basename($file_name),
                    'email'         => $data['email'],
                ]);
        } else {
            //空だった場合、image以外の情報を更新する
            $this::where('id', $this->id)
                ->update([
                    'screen_name'   => $data['screen_name'],
                    'name'          => $data['name'],
                    'email'         => $data['email'],
                ]);
        }

        return;
    }
}
