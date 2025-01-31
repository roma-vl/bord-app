<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{

    public function index()
    {
        $users = UserResource::collection(User::withTrashed()->paginate(2));
        return Inertia::render('Admin/Users/Index', compact('users'));
    }

    public function create()
    {
        return redirect()->route('admin.users.index')
            ->with('info', 'Розділ успішно створено.');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back();
    }
}
