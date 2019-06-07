<?php

use Illuminate\Database\Seeder;
use App\Author;
use App\Category;
use App\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::truncate();
        Category::truncate();
        Book::truncate();

        $authors = $categories = $books = [];

        $authors[] = Author::create([
            'name' => 'Robin Nixon'
        ]);
        $authors[] = Author::create([
            'name' => 'Christopher Negus'
        ]);
        $authors[] = Author::create([
            'name' => 'Douglas Crockford'
        ]);

        $categories[] = Category::create([
            'name' => 'PHP'
        ]);
        $categories[] = Category::create([
            'name' => 'Javascript'
        ]);
        $categories[] = Category::create([
            'name' => 'Linux'
        ]);

        $books[] = Book::create([
            'title' => 'Learning PHP, MySQL & JavaScript: With jQuery, CSS & HTML5',
            'isbn' => '978-1491918661',
            'author_id' => 1,
            'price' => 9.99,
        ])->categories()->attach([$categories[0]->id, $categories[1]->id]);
        $books[] = Book::create([
            'title' => 'Ubuntu: Up and Running: A Power User\'s Desktop Guide',
            'isbn' => '978-0596804848',
            'author_id' => 1,
            'price' => 12.99,
        ])->categories()->attach([$categories[2]->id]);
        $books[] = Book::create([
            'title' => 'Linux Bible',
            'isbn' => '978-1118999875',
            'author_id' => 2,
            'price' => 19.99,
        ])->categories()->attach([$categories[2]->id]);
        $books[] = Book::create([
            'title' => 'Javascript: The Good Parts',
            'isbn' => '978-0596517748',
            'author_id' => 3,
            'price' => 8.99,
        ])->categories()->attach([$categories[1]->id]);
    }
}
