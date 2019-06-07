<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use App\Category;
use App\Author;

class BookController extends baseController
{

    private $bookRelationships = ['categories', 'author'];

    public function index(Request $request, Book $bookModel)
    {
        // get book data
        $books = $bookModel->with($this->bookRelationships);
        
        // filter by author if required
        if ($request->has('author')) {
            $authorName = $request->input('author');
            $books = $books->whereHas('author', function ($q) use ($authorName) {
                $q->where('name', 'like', '%' . $authorName . '%');
            });
        }
        
        // filter by category if required
        if ($request->has('category')) {
            $categoryName = $request->input('category');
            $books = $books->whereHas('categories', function ($q) use ($categoryName) {
                $q->where('name', 'like', '%' . $categoryName . '%');
            });
        }

        // get results
        $results = $books->get();

        return $this->output($results);
    }

    public function create(Request $request, Book $bookModel, Author $authorModel, Category $categoryModel)
    {
        // validate input
        $bookData = $request->validate([
            'title' => 'required|max:255',
            'isbn' => 'required|regex:/^[0-9\-]+$/|unique:books',
            'price' => 'required|numeric',
            'author' => 'required|string',
            'categories' => 'nullable|array'
        ]);

        // create record
        $book = $this->createOrUpdate(null, $bookData, $bookModel, $authorModel, $categoryModel);

        return $this->output([$book], 201);
    }

    public function update($id, Request $request, Book $bookModel, Author $authorModel, Category $categoryModel)
    {
        // validate input
        $bookData = $request->validate([
            'title' => 'max:255',
            'isbn' => 'regex:/^[0-9\-]+$/|unique:books',
            'price' => 'numeric',
            'author' => 'string',
            'categories' => 'nullable|array'
        ]);

        // update record
        $book = $this->createOrUpdate($id, $bookData, $bookModel, $authorModel, $categoryModel);

        return $this->output([$book]);
    }

    private function createOrUpdate($id, $bookData, $bookModel, $authorModel, $categoryModel)
    {
        // create author if it doesn't already exist
        $author = $authorModel->firstOrCreate([
            'name' => $bookData['author']
        ]);
        $bookData['author_id'] = $author->id;
        unset($bookData['author']);

        // create categories if they were provided and don't already exist
        $categoryIds = [];
        if (!empty($bookData['categories'])) {
            foreach ($bookData['categories'] as $bookCategory) {
                $category = $categoryModel->firstOrCreate([
                    'name' => $bookCategory
                ]);
                $categoryIds[] = $category->id;
            }
        }
        unset($bookData['categories']);

        // if id was passed in, update that record, otherwise create a new record
        if (is_null($id)) {
            $book = $bookModel->create($bookData);
        } else {
            $book = $bookModel->where('id', '=', $id)->update($bookData);
        }

        // add the book to the correct categories
        $book->categories()->sync($categoryIds);

        return $book;
    }
}