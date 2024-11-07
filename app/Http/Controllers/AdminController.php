<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function adminDashboard(Request $request)
    {
        if (!Gate::allows('admin')) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return response()->json(['message' => 'Welcome to the admin dashboard'], 200);
    }
}

