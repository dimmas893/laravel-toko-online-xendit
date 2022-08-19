<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Category, Tag, Post};

class FrontController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        $allCategory = Category::withCount('posts')->get(); 
        $recent = Post::latest()->limit(5)->get();
        $allTag=Tag::all();
        return view('blog.blog', compact('posts','allCategory','recent','allTag'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $allCategory = Category::withCount('posts')->get(); 
        $recent = Post::latest()->limit(5)->get();
        $allTag=Tag::all();
        return view('blog.blog-detail', compact('post','allCategory','recent','allTag'));
    }

    public function category(Category $category)
    {
        $posts = $category->posts()->latest()->get();
        return view ('welcome',compact('category','posts'));
    }

    public function tag(Tag $tag)
    {
        $posts = $tag->posts()->latest()->get();
        return view ('welcome',compact('tag','posts'));
    }

}