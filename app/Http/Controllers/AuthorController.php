<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\AuthorRequest;

class AuthorController extends Controller
{
    public function __construct(Author $author)
    {
        $this->author = $author;
    }

    /**
     * Display a listing of the users
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('authors.index', ['authors'=> $this->author->get()]);
    }

    /**
     * Display a detail of the author
     *
     * @param  \App\Models\Author $author
     * @return \Illuminate\View\View
     */
    public function show(Author $author)
    {
        return json_encode($author->findOrFail($author->id));
    }

    /**
     * Store the user
     *
     * @param  \App\Http\Requests\AuthorRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AuthorRequest $request)
    {
        $this->author->create($request->validated());

        return redirect()->route('authors.index')->withUserStatus(__('Author successfully added.'));
    }

    /**
     * Update the author
     *
     * @param  \App\Http\Requests\AuthorRequest  $request
     * @param  \App\Models\Author $author
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AuthorRequest $request, Author $author)
    {
        if ($author->id == 1) {
            return back()->withErrors(['not_allow_profile' => __('You are not allowed to change data for a default user.')]);
        }

        $author->update($request->all());

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Delete the author
     *
     * @param  \App\Models\Author $author
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Author $author)
    {
        if ($author->id == 1) {
            return back()->withErrors(['not_allow_profile' => __('You are not allowed to change data for a default user.')]);
        }

        $author->delete();

        return back()->withUserStatus(__('Author successfully deleted.'));
    }
}
