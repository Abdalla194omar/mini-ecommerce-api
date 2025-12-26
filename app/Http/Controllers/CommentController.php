<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    protected CommentService $commentService;
    protected ProductService $productService;

    public function __construct(
        CommentService $commentService,
        ProductService $productService
    ) {
        $this->commentService = $commentService;
        $this->productService = $productService;

        // Only authenticated users can create/update/delete comments
        $this->middleware('auth:api')->except(['index']);
    }

    /**
     * Get all comments for a product (Public)
     */
    public function index(int $productId): JsonResponse
    {
        if (! $this->productService->exists($productId)) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $comments = $this->commentService->getProductComments($productId);

        return response()->json(['data' => $comments], 200);
    }

    /**
     * Create comment (Authenticated users only)
     */
    public function store(Request $request, int $productId): JsonResponse
    {
        if (! $this->productService->exists($productId)) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'content' => 'required|string|min:3',
        ]);

        $comment = $this->commentService->createComment(
            $productId,
            auth('api')->user()->id,
            $validated['content']
        );

        return response()->json(['data' => $comment], 201);
    }

    /**
     * Update comment (Owner only)
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $comment = $this->commentService->getCommentById($id);

        if (! $comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|min:3',
        ]);

        $updatedComment = $this->commentService->updateComment(
            $id,
            $validated['content']
        );

        return response()->json(['data' => $updatedComment], 200);
    }

    /**
     * Delete comment (Owner or Admin)
     */
    public function destroy(int $id): JsonResponse
    {
        $comment = $this->commentService->getCommentById($id);

        if (! $comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $this->authorize('delete', $comment);

        $this->commentService->deleteComment($id);

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
