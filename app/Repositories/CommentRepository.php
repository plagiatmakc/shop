<?php

namespace App\Repositories;

use App\Product;
use App\User;
use App\Comment;

class CommentRepository
{
    public function storeCommentForProduct(Product $product, $data, int $userId)
    {
        try{
            $product->comments()->create([
                'body' => $data['comment_body'],
                'user_id' => $userId
            ]);
        }catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    public function storeCommentForComment(Comment $comment, $data, int $userId)
    {
        try{
            $comment->comments()->create([
                'body' => $data['comment_body'],
                'user_id' => $userId
            ]);
        }catch (\Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    public function destroyComment(Comment $comment)
    {
        if($comment->has('comments')){
            $this->destroyCommentsRecursive($comment);
        }else {
            try{
                $result = $comment->delete();
            }catch (\Exception $e) {

                return $e->getMessage();
            }

            return $result;
        }
    }

    public function destroyCommentsRecursive(Comment $comment)
    {
        foreach ($comment->comments as $subcomment) {
            if($subcomment->has('comments')){
                $this->destroyCommentsRecursive($subcomment);
            }else {
                try{
                    $result = $subcomment->delete();
                }catch (\Exception $e) {

                    return $e->getMessage();
                }

                return $result;
            }
        }

        try{
            $result = $comment->delete();
        }catch (\Exception $e) {

            return $e->getMessage();
        }

        return $result;
    }
}