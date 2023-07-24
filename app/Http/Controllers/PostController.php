<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\ViewServiceProvider;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')
            ->only(['create', 'edit', 'store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // DB::connection()->enableQueryLog();
        // $posts = BlogPost::with('comments')->get();
        // foreach ($posts as $post) {
        //     foreach ($post->comments as $comment) {
        //         echo $comment->content;
        //     }
        // }

        // dd(DB::getQueryLog());

        $mostComment = Cache::remember('blog-post-commented', 60, function () {
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActive = Cache::remember('users-most-active', 60, function () {
            return User::withMostBlogPosts()->take(5)->get();
        });

        $mostActiveLastMonth = Cache::remember('users-most-active-last-month', 60, function () {
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });

        return view(
            'posts.index',
            [
                'posts' => BlogPost::latest()->withCount('comments')->with('user')->get(),
                'mostCommented' => $mostComment,
                'mostActive' => $mostActive,
                'mostActiveLastMonth' => $mostActiveLastMonth
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $blogPost = Cache::remember("blog-post-{$id}", 60, function () use ($id) {
            return BlogPost::with('comments')->findOrFail($id);
        });

        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $userKey = "blog-post-{$id}-users";

        $users = Cache::get($userKey, []);
        $userUpdate = [];
        $difference = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $difference--;
            } else {
                $userUpdate[$session] = $lastVisit;
            }
        }

        if (!array_key_exists($sessionId, $users) || $now->diffInMinutes($users[$sessionId]) >= 1) {
            $difference++;
        }

        $userUpdate[$sessionId] = $now;
        Cache::forever($userKey, $userUpdate);
        if (!Cache::has($counterKey)) {
            Cache::forever($counterKey, 1);
        } else {
            Cache::increment($counterKey, $difference);
        }

        $counter = Cache::get($counterKey);

        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter

        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        // dd($request->title);
        $vaildatedData = $request->validated();

        $vaildatedData['user_id'] = $request->user()->id;
        // dd($vaildatedData);
        $blogpost = BlogPost::create($vaildatedData);
        session()->flash('status', 'Blog Post was Created');
        return redirect()->route('posts.show', $blogpost->id);
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        // if (Gate::denies('update-post', $post)) {
        //     abort(403, 'You cannot edit this blogPost');
        // }
        $this->authorize($post);
        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        // if (Gate::denies('update-post', $post)) {
        //     abort(403, 'You cannot edit this blogPost');
        // }
        $this->authorize($post);
        $vaildatedData = $request->validated();
        $post->update($vaildatedData);
        session()->flash('status', 'Blog Post was Updated');
        return redirect()->route('posts.show', $post->id);
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        // if (Gate::denies('delete-post', $post)) {
        //     abort(403, 'You cannot delete this post');
        // }
        $this->authorize($post);
        $post->delete();
        session()->flash('status', 'Blog Post was Deleted');
        return redirect()->route('posts.index');
    }
}
