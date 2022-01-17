<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    public function __construct(Book $book, Author $author)
    {
        $this->book = $book;
        $this->author = $author;
    }

    /**
     * Display a listing of the users
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('books.index', ['books'=> $this->book->get(), 'authors' => $this->author->get()]);
    }

    /**
     * Display a detail of the book
     *
     * @param  \App\Models\Book $book
     * @return \Illuminate\View\View
     */
    public function show(Book $book)
    {
        return json_encode($book->findOrFail($book->id));
    }

    /**
     * Store the user
     *
     * @param  \App\Http\Requests\BookRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BookRequest $request)
    {
        $this->book->create($request->validated());

        return redirect()->route('books.index')->withUserStatus(__('Book successfully added.'));
    }

    /**
     * Update the book
     *
     * @param  \App\Http\Requests\BookRequest  $request
     * @param  \App\Models\Book $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BookRequest $request, Book $book)
    {
        $book->update($request->all());

        return back()->withStatus(__('Book successfully updated.'));
    }

    /**
     * Delete the book
     *
     * @param  \App\Models\Book $book
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return back()->withUserStatus(__('Book successfully deleted.'));
    }
}