<?php

namespace Tests\Unit\app\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use App\Author;
use App\Book;
use App\Category;
use Mockery;

class BookControllerTest extends TestCase
{
    private $categoryMock;
    private $categoryData = [
        [
            'id' => 1,
            'name' => 'PHP'
        ],
        [
            'id' => 2,
            'name' => 'Linux'
        ]
    ];
    private $bookMock;
    private $bookData = [
        [
            'id' => 1,
            'title' => 'Learning PHP, MySQL & JavaScript: With jQuery, CSS & HTML5',
            'isbn' => '978-1491918661',
            'price' => 9.99,
            'author' => [
                'id' => 1,
                'name' => 'Robin Nixon'
            ],
            'categories' => [
                'id' => 1,
                'name' => 'PHP'
            ]
        ],
        [
            'id' => 2,
            'title' => 'Ubuntu: Up and Running: A Power User\'s Desktop Guide',
            'isbn' => '978-0596804848',
            'price' => 12.99,
            'author' => [
                'id' => 1,
                'name' => 'Robin Nixon'
            ],
            'categories' => [
                'id' => 2,
                'name' => 'Linux'
            ]
        ]
    ];
    private $authorMock;
    private $authorData = [
        [
            'id' => 1,
            'name' => 'Robin Nixon'
        ],
        [
            'id' => 2,
            'name' => 'Christopher Negus'
        ]
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
        $this->categoryMock = Mockery::mock(Category::class);
        $this->authorMock = Mockery::mock(Author::class);
        $this->bookMock = Mockery::mock(Book::class);
        $this->requestMock = Mockery::mock(Request::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    public function testIndexBasic()
    {
         $this->bookMock
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->bookMock);
        $this->bookMock
            ->shouldReceive('get')
            ->once()
            ->andReturn((object)$this->bookData);

        $this->authorMock->allows()->with(Mockery::any())->andReturns($this->authorMock);
        $this->categoryMock->allows()->with(Mockery::any())->andReturns($this->categoryMock);
        $this->requestMock->allows()->has(Mockery::any())->andReturns(false);

        $bookController = new BookController($this->requestMock, $this->bookMock, $this->authorMock, $this->categoryMock);
        $returnData = $bookController->index();

        $this->assertJson($returnData->content());
        $this->assertEquals($returnData->content(), json_encode(['results' => (object)$this->bookData]));
    }

    public function testIndexSpecificAuthor()
    {
         $this->bookMock
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->bookMock);
        $this->bookMock
            ->shouldReceive('get')
            ->once()
            ->andReturn((object)$this->bookData);

        $this->authorMock->allows()->with(Mockery::any())->andReturns($this->authorMock);
        $this->categoryMock->allows()->with(Mockery::any())->andReturns($this->categoryMock);
        
        $this->requestMock->allows()->has('author')->andReturns(true);
        $this->requestMock->allows()->has(Mockery::any())->andReturns(false);
        $this->requestMock->shouldReceive('input')->with('author')->andReturn($this->authorData[0]['name']);

        $this->bookMock
            ->shouldReceive('whereHas')
            ->with('author', Mockery::any())
            ->once()
            ->andReturn($this->bookMock);

        $bookController = new BookController($this->requestMock, $this->bookMock, $this->authorMock, $this->categoryMock);
        $returnData = $bookController->index();

        $this->assertJson($returnData->content());
        $this->assertEquals($returnData->content(), json_encode(['results' => (object)$this->bookData]));
    }

    public function testIndexSpecificCategory()
    {
         $this->bookMock
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->bookMock);
        $this->bookMock
            ->shouldReceive('get')
            ->once()
            ->andReturn((object)$this->bookData);

        $this->authorMock->allows()->with(Mockery::any())->andReturns($this->authorMock);
        $this->categoryMock->allows()->with(Mockery::any())->andReturns($this->categoryMock);
        
        $this->requestMock->allows()->has('category')->andReturns(true);
        $this->requestMock->allows()->has(Mockery::any())->andReturns(false);
        $this->requestMock->shouldReceive('input')->with('category')->andReturn($this->categoryData[0]['name']);

        $this->bookMock
            ->shouldReceive('whereHas')
            ->with('categories', Mockery::any())
            ->once()
            ->andReturn($this->bookMock);

        $bookController = new BookController($this->requestMock, $this->bookMock, $this->authorMock, $this->categoryMock);
        $returnData = $bookController->index();

        $this->assertJson($returnData->content());
        $this->assertEquals($returnData->content(), json_encode(['results' => (object)$this->bookData]));
    }
}