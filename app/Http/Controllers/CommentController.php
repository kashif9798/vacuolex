<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Microbe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends ApiController
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Microbe $microbe)
    {
        $rules = [
            "comment" => ["required", "string", "max:255"],
        ];

        $this->validate($request, $rules);


        $commentCreated = new Comment();        

        $commentCreated->comment = $request->comment;
        $commentCreated->microbe_id = $microbe->id;
        $commentCreated->user_id = Auth::guard('sanctum')->user()->id;

        $commentCreated->save();


        $comment = Comment::where("id", $commentCreated->id)
        ->with(["user:id,name,image"])
        ->first();

        return $this->showOne($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $rules = [
            "comment" => ["required", "string", "max:255"],
        ];

        $this->validate($request, $rules);

        $comment->update([
            "comment" => $request->comment,
        ]);

        return $this->showMessage("Comment updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return $this->showMessage("Comment Successfully Deleted", 200);
    }
}
