<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment = new Comment();
        $comment->content = $validatedData['content'];
        $comment->user_id = auth()->id();
        $comment->movie_id = $request['movie_id'];

        $comment->created_at = Carbon::now();
        $comment->updated_at = Carbon::now();

        $comment->save();

        return redirect()->back()->with('success', 'Comment created successfully.');
    }

    public function reply(Request $request, $commentId)
    {
        $request->validate([
            'reply_content' => 'required|string|max:1000',
        ]);

        $comment = Comment::findOrFail($commentId);

        $reply = new Reply();
        $reply->content = $request->reply_content;
        $reply->user_id = Auth::id(); // Assuming the user is authenticated
        $reply->comment_id = $comment->id; // Assuming 'comment_id' is the foreign key in the replies table
        $reply->save();

        return redirect()->back()->with('success', 'Reply added successfully!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $movieId = $comment->movie_id;
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.', 'movie_id' => $movieId]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:4000',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->content = $request->input('content');
        $comment->save();
        $movieId = $comment->movie_id;

        return response()->json(['message' => 'Comment updated successfully.', 'movie_id' => $movieId]);
    }
    public function updateReply(Request $request, Reply $reply)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $reply->content = $request->input('content');
        $reply->save();

        return response()->json(['message' => 'Reply updated successfully.']);
    }

    public function deleteReply(Reply $reply)
    {
        $reply->delete();

        return response()->json(['message' => 'Reply deleted successfully.']);
    }
}
