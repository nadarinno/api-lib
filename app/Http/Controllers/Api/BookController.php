<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Resources\BookResource;
use App\Services\BookService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Throwable;

class BookController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private BookService $bookService
    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $books = $this->bookService->getAll();

            return $this->paginatedResponse(
                $books,
                BookResource::class,
                __('messages.books_retrieved')
            );

        } catch (Throwable $exception) {
            report($exception);

            return $this->errorResponse(
                __('messages.server_error'),
                500
            );
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $book = $this->bookService->find($id);

            if (!$book) {
                return $this->errorResponse(
                    __('messages.book_not_found'),
                    404
                );
            }

            return $this->successResponse(
                (new BookResource($book))->resolve(),
                __('messages.book_retrieved')
            );

        } catch (Throwable $exception) {
            report($exception);

            return $this->errorResponse(
                __('messages.server_error'),
                500
            );
        }
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        return response()->json([
        'all' => $request->all(),
        'files' => $request->allFiles(),
        'has_file' => $request->hasFile('file'),
        'file_valid' => $request->hasFile('file')
            ? $request->file('file')->isValid()
            : false,
    ]);
        try {
            $book = $this->bookService->create(
                $request->validated()
            );

            return $this->successResponse(
                (new BookResource($book))->resolve(),
                __('messages.book_created'),
                201
            );

        } catch (Throwable $exception) {
            report($exception);

            return $this->errorResponse(
                __('messages.server_error'),
                500
            );
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $book = $this->bookService->find($id);

            if (!$book) {
                return $this->errorResponse(
                    __('messages.book_not_found'),
                    404
                );
            }

            $this->bookService->delete($book);

            return $this->successResponse(
                null,
                __('messages.book_deleted')
            );

        } catch (Throwable $exception) {
            report($exception);

            return $this->errorResponse(
                __('messages.server_error'),
                500
            );
        }
    }
}