<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the users
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('users.index', ['users'=> $this->user->get()]);
    }

    /**
     * Display a detail of the user
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return json_encode($user->findOrFail($user->id));
    }

    /**
     * Store the user
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        $this->user->create($request->validated());

        return redirect()->route('users.index')->withUserStatus(__('Profile successfully added.'));
    }

    /**
     * Update the user
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        if ($user->id == 1) {
            return back()->withErrors(['not_allow_profile' => __('You are not allowed to change data for a default user.')]);
        }

        $user->update($request->all());

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Delete the user
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if ($user->id == 1) {
            return back()->withErrors(['not_allow_profile' => __('You are not allowed to change data for a default user.')]);
        }

        $user->delete();

        return back()->withUserStatus(__('User successfully deleted.'));
    }
}
