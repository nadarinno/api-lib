<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class BookService
{
    
public function getAll(): LengthAwarePaginator
{
    return QueryBuilder::for(Book::class)
        ->allowedFilters(
            'name',
            'release_year'
        )
        ->allowedSorts(
            'name',
            'release_year',
            'created_at'
        )
        ->defaultSort('-created_at')
        ->paginate(5)
        ->withQueryString();
}

    public function find(int $id): ?Book
    {
        return Book::find($id);
    }

    public function create(array $data): Book
    {
        $path = $data['file']->store('books', 'public');

        try {
            $data['file'] = $path;

            return Book::create($data);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }
    }

    public function delete(Book $book): void
    {
        $file = $book->file;

        $book->delete();

        if ($file) {
            Storage::disk('public')->delete($file);
        }
    }
}