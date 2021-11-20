<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\Comment;
use App\Models\Follower;

class TweetsController extends Controller
{

    public function index(Tweet $tweet, Follower $follower)
    {
        $user = auth()->user();
        //フォローしているユーザのID(followed_id)が取得される
        $follow_ids = $follower->followingIds($user->id);
        //pluck()は指定したキーの全コレクション値を取得する
        //toArray()はコレクションを配列に変換する
        //followed_idの配列が取得できる
        $following_ids = $follow_ids->pluck('followed_id')->toArray();

        $timelines = $tweet->getTimeLines($user->id, $following_ids);
        return view('tweets.index', [
            'user'      => $user,
            'timelines' => $timelines
        ]);
    }


    public function create()
    {
        $user = auth()->user();

        return view('tweets.create', [
            'user' => $user,
        ]);
    }


    public function store(Request $request, Tweet $tweet)
    {
        $user = auth()->user();
        $data = $request->validate([
            'text' => ['required', 'string', 'max:140']
        ]);
        $tweet->tweetStore($user->id, $data);
        // $data = $request->all();
        // $validator = Validator::make($data, [
        //     'text' => ['required', 'string', 'max:140']
        // ]);

        // $validator->validate();
        // $tweet->tweetStore($user->id, $data);

        return redirect('tweets');
    }

    public function show(Tweet $tweet, Comment $comment)
    {
        $user = auth()->user();
        $get_tweet = $tweet->getTweet($tweet->id);
        $comments = $comment->getComments($get_tweet->id);

        return view('tweets.show', [
            'user' => $user,
            'tweet' => $get_tweet,
            'comments' => $comments,
        ]);


    }

    public function edit(Tweet $tweet)
    {
        $user = auth()->user();
        $tweets = $tweet->getEditTweet($user->id, $tweet->id);

        if (!isset($tweets)) {
            return redirect('tweets');
        }

        return view('tweets.edit', [
            'user'   => $user,
            'tweets' => $tweets
        ]);
    }

    public function update(Request $request, Tweet $tweet)
    {
        $data = $request->validate([
            'text' => ['required', 'string', 'max:140'],
        ]);
        // $data = $request->all();
        // $validator = Validator::make($data, [
        //     'text' => ['required', 'string', 'max:140']
        // ]);

        // $validator->validate();
        $tweet->tweetUpdate($tweet->id, $data);

        return redirect('tweets');
    }


    public function destroy($id)
    {
        //
    }
}
