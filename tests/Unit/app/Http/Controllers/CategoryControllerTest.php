<?php

namespace Tests\Unit\app\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\CategoryController;
use App\Category;
use Mockery;

class categoryControllerTest extends TestCase
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

    public function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
        $this->categoryMock = Mockery::mock(Category::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    public function testIndex()
    {
         $this->categoryMock
            ->shouldReceive('all')
            ->once()
            ->andReturn((object)$this->categoryData);

        $categoryController = new CategoryController($this->categoryMock);
        $returnData = $categoryController->index();

        $this->assertJson($returnData->content());
        $this->assertEquals($returnData->content(), json_encode(['results' => (object)$this->categoryData]));
    }
}
