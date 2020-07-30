<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Request $request, Category $category, Topic $topic, User $user, Link $link)
    {
        $topics = $topic->withOrder($request->order)
            ->where('category_id', $category->id)
            ->paginate(20);

        $activeUsers = $user->getActiveUsers();

        $links = $link->getAllCached();

        return view('topics.index', [
            'topics' => $topics,
            'category' => $category,
            'activeUsers' => $activeUsers,
            'links' => $links,
        ]);
    }

}
