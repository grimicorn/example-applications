<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $board = Board::first();
        abort_unless($board, 404);

        return redirect(
            route('boards.show', ['board' => $board])
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Board $board)
    {
        return view('boards.show', ['board' => $board]);
    }
}
