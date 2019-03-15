<?php

namespace App\Http\Controllers;

use App\Product;
use App\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function storeComment(CommentRequest $request, Product $product, CommentRepository $commentRepository)
    {
        $result = $commentRepository->storeCommentForProduct($product, $request, Auth::id());

        return response()->json($result);
    }

    public function replyComment(CommentRequest $request, Comment $comment, CommentRepository $commentRepository)
    {
        $result = $commentRepository->storeCommentForComment($comment, $request, Auth::id());

        return response()->json($result);
    }

    public function deleteComment(Comment $comment, CommentRepository $commentRepository)
    {
        $result = $commentRepository->destroyComment($comment);

        return response()->json($result);
    }
}
