<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
     public function index()
    {
        $usersCount = User::count();
        $postsCount = Post::count();

        return view('admin.admindashboard', compact('usersCount', 'postsCount'));
    }
}
