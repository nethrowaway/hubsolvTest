<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksTest extends TestCase
{
    use RefreshDatabase;

    public function testGetBooksEndpoint()
    {
        $this->json('GET', 'books')
            ->assertStatus(200)
            ->assertJsonStructure([
                'results' => [
                    [
                        'id',
                        'title',
                        'isbn',
                        'price',
                        'author' => [
                            'id',
                            'name'
                        ],
                        'categories' => [
                            [
                                'id',
                                'name'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testGetBooksEndpointWithAuthorRobinNixon()
    {
        $result = $this->json('GET', 'books?author=Robin Nixon')
            ->assertStatus(200)
            ->assertJsonStructure([
                'results' => [
                    [
                        'id',
                        'title',
                        'isbn',
                        'price',
                        'author' => [
                            'id',
                            'name'
                        ],
                        'categories' => [
                            [
                                'id',
                                'name'
                            ]
                        ]
                    ]
                ]
            ]);

        $decodedResults = json_decode($result->content(), true);
        $this->assertEquals(2, count($decodedResults['results']));
        $this->assertContains('978-1491918661', $result->content());
        $this->assertContains('978-0596804848', $result->content());
    }

    public function testGetBooksEndpointWithAuthorChristopherNegus()
    {
        $result = $this->json('GET', 'books?author=Christopher Negus')
            ->assertStatus(200)
            ->assertJsonStructure([
                'results' => [
                    [
                        'id',
                        'title',
                        'isbn',
                        'price',
                        'author' => [
                            'id',
                            'name'
                        ],
                        'categories' => [
                            [
                                'id',
                                'name'
                            ]
                        ]
                    ]
                ]
            ]);

        $decodedResults = json_decode($result->content(), true);
        $this->assertEquals(1, count($decodedResults['results']));
        $this->assertContains('978-1118999875', $result->content());
    }

    public function testGetBooksEndpointWithCategoryLinux()
    {
        $result = $this->json('GET', 'books?category=Linux')
            ->assertStatus(200)
            ->assertJsonStructure([
                'results' => [
                    [
                        'id',
                        'title',
                        'isbn',
                        'price',
                        'author' => [
                            'id',
                            'name'
                        ],
                        'categories' => [
                            [
                                'id',
                                'name'
                            ]
                        ]
                    ]
                ]
            ]);

        $decodedResults = json_decode($result->content(), true);
        $this->assertEquals(2, count($decodedResults['results']));
        $this->assertContains('978-0596804848', $result->content());
        $this->assertContains('978-1118999875', $result->content());
    }

    public function testGetBooksEndpointWithCategoryPHP()
    {
        $result = $this->json('GET', 'books?category=PHP')
            ->assertStatus(200)
            ->assertJsonStructure([
                'results' => [
                    [
                        'id',
                        'title',
                        'isbn',
                        'price',
                        'author' => [
                            'id',
                            'name'
                        ],
                        'categories' => [
                            [
                                'id',
                                'name'
                            ]
                        ]
                    ]
                ]
            ]);

        $decodedResults = json_decode($result->content(), true);
        $this->assertEquals(1, count($decodedResults['results']));
        $this->assertContains('978-1491918661', $result->content());
    }

    public function testGetBooksEndpointWithAuthorRobinNixonAndCategoryLinux()
    {
        $result = $this->json('GET', 'books?author=Robin Nixon&category=Linux')
            ->assertStatus(200)
            ->assertJsonStructure([
                'results' => [
                    [
                        'id',
                        'title',
                        'isbn',
                        'price',
                        'author' => [
                            'id',
                            'name'
                        ],
                        'categories' => [
                            [
                                'id',
                                'name'
                            ]
                        ]
                    ]
                ]
            ]);

        $decodedResults = json_decode($result->content(), true);
        $this->assertEquals(1, count($decodedResults['results']));
        $this->assertContains('978-0596804848', $result->content());
    }

    public function testCreateBookEndpoint()
    {
        $result = $this->json('POST', 'books/_new', [
                'title' => 'Modern PHP: New Features and Good Practices',
                'isbn' => '978-1491905012',
                'price' => 18.99,
                'author' => 'Josh Lockhart',
                'categories' => [
                    'PHP'
                ]
            ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'results' => [
                    [
                        'id',
                        'title',
                        'isbn',
                        'price',
                        'author' => [
                            'id',
                            'name'
                        ],
                        'categories' => [
                            [
                                'id',
                                'name'
                            ]
                        ]
                    ]
                ]
            ]);

        $this->assertContains('978-1491905012', $result->content());
        $this->assertContains('Modern PHP: New Features and Good Practices', $result->content());
        $this->assertContains('Josh Lockhart', $result->content());
        $this->assertContains('PHP', $result->content());
        $this->assertContains('18.99', $result->content());
    }

    public function testCreateBookEndpointError()
    {
        $result = $this->json('POST', 'books/_new', [
                'title' => 'Modern PHP: New Features and Good Practices',
                'isbn' => '978-INVALID-ISBN-1491905012',
                'price' => 18.99,
                'author' => 'Josh Lockhart',
                'categories' => [
                    'PHP'
                ]
            ])
            ->assertStatus(422);

        $this->assertContains('The isbn format is invalid.', $result->content());
    }
}