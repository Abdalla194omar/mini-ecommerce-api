<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    /**
     * Get all comments for a product
     */
    public function getProductComments(int $productId): Collection
    {
        return Comment::with('user')
            ->where('product_id', $productId)
            ->latest()
            ->get();
    }

    /**
     * Create new comment
     */
    public function createComment(int $productId, int $userId, string $content): Comment
    {
        $comment = Comment::create([
            'product_id' => $productId,
            'user_id'    => $userId,
            'content'    => $content,
        ]);

        return $comment->load('user');
    }

    /**
     * Update comment
     */
    public function updateComment(int $commentId, string $content): ?Comment
    {
        $comment = Comment::find($commentId);

        if (! $comment) {
            return null;
        }

        $comment->update([
            'content' => $content,
        ]);

        return $comment->load('user');
    }

    /**
     * Delete comment
     */
    public function deleteComment(int $commentId): bool
    {
        $comment = Comment::find($commentId);

        if (! $comment) {
            return false;
        }

        $comment->delete();

        return true;
    }

    /**
     * Get comment by ID
     */
    public function getCommentById(int $commentId): ?Comment
    {
        return Comment::with('user')->find($commentId);
    }
}
