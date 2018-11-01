<?php

namespace App\Http\Controllers;

use App\Helpers\DbHelper;
use App\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $result = [];
        $query = $request->get('query');
        $by = $request->get('by');
        /**@var \App\User $currentUser*/
        $currentUser = auth()->user();

        $users = User::where('users.id', '<>', $currentUser->id)
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id');

        if(!is_null($query) && !is_null($by))
        {
            $query = DbHelper::escapeLike($query);

            switch ($by)
            {
                case 'last_name':
                case 'first_name':
                case 'email':
                    $users = $users->whereRaw($by." like ?", ['%'.$query.'%']);
            }

        }

        if(!$currentUser->isAdmin())
        {
            $users = $users->where('role', 'user');
        }

        $users = $users->get(['users.id', 'first_name', 'last_name', 'email', 'phone', 'role']);

        foreach ($users as $user)
        {
            $result[] = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone,
                'email' => $user->email,
                'role' => $user->role
            ];
        }

        return response()->json(['users'=> $result]);
    }

    public function show(User $user)
    {
        /**@var \App\User $currentUser*/
        $currentUser = auth()->user();

        if(!$currentUser->isAdmin() && $currentUser->id != $user->id)
        {
            return response()->json(['error' => 'Access forbidden' ], 403);
        }

        $profile = $user->profile;

        return response()->json([
            'id' => $user->id,
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'phone' => $profile->phone,
            'email' => $user->email,
            'role' => $user->role
        ]);

    }

    public function store(Request $request)
    {
        /**@var \App\User $currentUser*/
        $currentUser = auth()->user();

        if($currentUser->isAdmin())
        {
            $request->validate([
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'role' => 'required|in:admin,user',
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:20',
                'phone' => 'required|max:14'
            ]);

            $user = new User([
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'role' => $request->get('role')
                ]);

            $user->save();

            $user->profile()->create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'phone' => $request->get('phone'),
            ]);

            return response()->json();
        }

        return response()->json(['error' => 'Access forbidden' ], 403);
    }

    public function update(Request $request, User $user)
    {
        /**@var \App\User $currentUser*/
        $currentUser = auth()->user();

        if($currentUser->isAdmin() || (!$currentUser->isAdmin() && $user->id == $currentUser->id))
        {
            $request->validate([
                'email' => 'required|email|unique:users,email,'.$user->id,
                'password' => is_null($request->get('password')) ? 'present' : 'min:6',
                'role' => 'sometimes|required|in:admin,user',
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:20',
                'phone' => 'required|max:14'
            ]);

            $userFields = [
                'email' => $request->get('email'),
            ];

            if(!is_null($request->get('password')))
            {
                $userFields['password'] = bcrypt($request->get('password'));
            }

            if ($currentUser->isAdmin())
            {
                $userFields['role'] = $request->get('role');
            }

            $user->fill($userFields)->save();

            $user->profile()->update([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'phone' => $request->get('phone'),
            ]);

            return response()->json(['role' => $user->role]);
        }

        return response()->json(['error' => 'Access forbidden' ], 403);
    }

    public function destroy(User $user)
    {
        /**@var \App\User $currentUser*/
        $currentUser = auth()->user();

        if($currentUser->isAdmin() && !$user->isAdmin())
        {
            $user->delete();

            return response()->json([], 204);
        }

        return response()->json(['error' => 'You can\'t delete user with admin role!' ], 403);
    }
}
