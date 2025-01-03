<?php

namespace Tests\Feature;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class HotelControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

    /** @test */
    public function it_can_list_all_hotels()
    {
        Hotel::factory()->count(15)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/hotel/all');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'location' , 'rating']
            ]
        ]);
    }


    /** @test */
    public function it_can_store_a_hotel()
    {
        $hotelData = [
            'name' => 'Test Hotel',
            'location' => '123 Test St',
            'rating' => 3,
        ];


        $response = $this->postJson('/api/hotel/store', $hotelData);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Hotel created successfully']);
        $this->assertDatabaseHas('hotels', $hotelData);
    }

    /** @test */
    public function it_fails_to_store_a_hotel_with_invalid_data()
    {
        $hotelData = [
            'name' => '', // Name is required
            'location' => '', // location is required
        ];

        $response = $this->postJson('/api/hotel/store', $hotelData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'location']);
    }


    /** @test */
    public function it_can_get_hotel_by_id()
    {
        $hotel = Hotel::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/hotel/{$hotel->id}");

        $response->assertStatus(200);
        $response->assertJson(['hotel' => ['id' => $hotel->id]]);
    }

    /** @test */
    public function it_returns_404_if_hotel_not_found()
    {
        $response = $this->getJson('/api/hotel/9999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Hotel not found']);
    }


    /** @test */
    public function it_can_get_all_rooms_by_hotel_id()
    {
        $hotel = Hotel::factory()->create(['user_id' => $this->user->id]);
        $rooms = $hotel->rooms()->createMany([
            ['price' => 100 , 'size' => "big"],
            ['price' => 200 , 'size' => "big"],
        ]);

        $response = $this->getJson("/api/hotel/{$hotel->id}/rooms");

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }


    /** @test */
    public function it_returns_404_if_hotel_has_no_rooms_or_not_found()
    {
        $response = $this->getJson('/api/hotel/9999/rooms');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Hotel not found']);
    }

}
