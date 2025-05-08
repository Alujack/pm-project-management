<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Get all comments
    public function index()
    {
        return Comment::with(['user', 'issue'])->get();
    }

    // Create a new comment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'value' => 'required|string',
            'userID' => 'required|exists:users,id',
            'issueID' => 'required|exists:issues,id',
        ]);

        return Comment::create($validated);
    }

    // Get specific comment
    public function show(Comment $comment)
    {
        return $comment->load(['user', 'issue']);
    }

    // Update a comment
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'value' => 'sometimes|string',
            'userID' => 'sometimes|exists:users,id',
            'issueID' => 'sometimes|exists:issues,id',
        ]);

        $comment->update($validated);
        return $comment;
    }

    // Delete a comment
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }
}