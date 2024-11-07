<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function adminDashboard(Request $request)
    {
        echo "Hi";
        if (!Gate::allows('user')) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return response()->json(['message' => 'Welcome to the admin dashboard'], 200);
    }
}

