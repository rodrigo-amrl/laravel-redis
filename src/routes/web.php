<?php

use App\Models\Article;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

use function Psy\debug;

Route::get('/', function () {
    return view('welcome');
});


/**
 * Get Number of video downloads
 */
Route::get('/videos/{id}', function ($id) {
    $downloads = Redis::get("videos.{$id}.downloads");
    return view('welcome')->with('downloads', $downloads);
});
/**
 * Set increment of video download
 */
Route::get('/videos/{id}/download', function ($id) {
    //incr: Increments the number stored at key by one
    Redis::incr("videos.{$id}.downloads");
    return redirect()->back();
});


/**
 * Get Trending Articles
 */
Route::get("articles/trending", function () {
    //zrange: Returns the specified range of elements in the sorted set stored at <key>.
    $trending = Redis::zrange("trending_articles", 0, 2, ["REV"]);

    $trending = Article::hydrate(array_map('json_decode', $trending));

    return $trending;
});
/**
 * Get Article and add to trending list
 */
Route::get("articles/{article}", function (Article $article) {
    //zincrby: Increments the score of in the sorted set stored at by
    Redis::zincrby("trending_articles", 1, $article->toJson());

    return $article;
});



/**
 * Get User Stats
 */
Route::get("users/{user}/stats", function ($user) {
    //hgetall: Returns all fields and values of the hash stored at key.
    $stats = Redis::hgetall("users.{$user}.stats");

    return $stats;
});
/**
 * Set Favorite Video stats
 */

