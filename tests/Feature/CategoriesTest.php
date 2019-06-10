<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function testCategoriesEndpoint()
    {
        $result = $this->json('GET', 'categories')
            ->assertStatus(200)
            ->assertJsonStructure([
                'results' => [
                    [
                        'id',
                        'name',
                    ]
                ]
            ]);


        $decodedResults = json_decode($result->content(), true);
        $this->assertEquals(3, count($decodedResults['results']));
        $this->assertContains('PHP', $result->content());
        $this->assertContains('Linux', $result->content());
        $this->assertContains('Javascript', $result->content());
    }
}