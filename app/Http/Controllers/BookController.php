<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use App\Category;
use App\Author;

class BookController extends baseController
{

    private $bookRelationships = ['categories', 'author'];

    public function __construct(Request $request, Book $bookModel, Author $authorModel, Category $categoryModel)
    {
        $this->request = $request;
        $this->bookModel = $bookModel;
        $this->authorModel = $authorModel;
        $this->categoryModel = $categoryModel;
    }

    public function index()
    {
        // get book data
        $books = $this->bookModel->with($this->bookRelationships);
        
        // filter by author if required
        if ($this->request->has('author')) {
            $authorName = $this->request->input('author');
            $books = $books->whereHas('author', function ($q) use ($authorName) {
                $q->where('name', 'like', '%' . $authorName . '%');
            });
        }
        
        // filter by category if required
        if ($this->request->has('category')) {
            $categoryName = $this->request->input('category');
            $books = $books->whereHas('categories', function ($q) use ($categoryName) {
                $q->where('name', 'like', '%' . $categoryName . '%');
            });
        }

        // get results
        $results = $books->get();

        return $this->output($results);
    }

    public function create()
    {
        // validate input
        $bookData = $this->request->validate([
            'title' => 'required|max:255',
            'isbn' => 'required|regex:/^[0-9\-]+$/|unique:books',
            'price' => 'required|numeric',
            'author' => 'required|string',
            'categories' => 'nullable|array'
        ]);

        // create record
        $book = $this->createOrUpdate(null, $bookData);

        return $this->output($book, 201);
    }

    public function update($id)
    {
        // validate input
        $bookData = $this->request->validate([
            'title' => 'max:255',
            'isbn' => 'regex:/^[0-9\-]+$/|unique:books',
            'price' => 'numeric',
            'author' => 'string',
            'categories' => 'nullable|array'
        ]);

        // update record
        $book = $this->createOrUpdate($id, $bookData);

        return $this->output($book);
    }

    private function createOrUpdate($id, $bookData)
    {
        // create author if it doesn't already exist
        $author = $this->authorModel->firstOrCreate([
            'name' => $bookData['author']
        ]);
        $bookData['author_id'] = $author->id;
        unset($bookData['author']);

        // create categories if they were provided and don't already exist
        $categoryIds = [];
        if (!empty($bookData['categories'])) {
            foreach ($bookData['categories'] as $bookCategory) {
                $category = $this->categoryModel->firstOrCreate([
                    'name' => $bookCategory
                ]);
                $categoryIds[] = $category->id;
            }
        }
        unset($bookData['categories']);

        // if id was passed in, update that record, otherwise create a new record
        if (is_null($id)) {
            $book = $this->bookModel->create($bookData);
        } else {
            $book = $this->bookModel->where('id', '=', $id)->update($bookData);
        }

        // add the book to the correct categories
        $book->categories()->sync($categoryIds);

        return $book;
    }
}