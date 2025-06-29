<?php

namespace Controllers;

use App\Models\User;
use App\Services\OpenRouteService\Dto\LocationFeatureDTO;
use App\Services\OpenRouteService\Dto\RouteDataDTO;
use App\Services\OpenRouteService\Services\Directions;
use App\Services\OpenRouteService\Services\GeoCode;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OpenRouteServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private GeoCode $geoCodeMock;
    private Directions $directionMock;
    private array $geoCodeResult;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['pseudo' => 'axel']);
        $this->geoCodeMock = Mockery::mock(GeoCode::class);
        $this->directionMock = Mockery::mock(Directions::class);
        $this->geoCodeResult = [
            'features' => [
                [
                    'properties' => [
                        'name' => 'Lyon',
                        'label' => 'Lyon, France',
                        'country' => 'France',
                        'region' => 'Auvergne-Rhône-Alpes',
                        'locality' => 'Lyon',
                    ],
                    'geometry' => [
                        'coordinates' => [4.8357, 45.7640],
                    ],
                ],
            ]
        ];
    }

    public function test_user_can_search_place(): void
    {
        // Data
        $place = "Lyon";
        $expectedDtoCollection = collect($this->geoCodeResult['features'])
            ->map(fn ($feature) => LocationFeatureDTO::fromArray($feature));

        // Mock
        $this->geoCodeMock->shouldReceive('search')
            ->once()
            ->with($place)
            ->andReturn($expectedDtoCollection);
        $this->app->instance(GeoCode::class, $this->geoCodeMock);

        // Test
        $response = $this->actingAs($this->user)
            ->get("/api/ors/search/$place");

        // Assertions
        $response->assertStatus(200);
        $response->assertJson([
            [
                'name' => 'Lyon',
                'label' => 'Lyon, France',
                'longitude' => 4.8357,
                'latitude' => 45.7640,
                'country' => 'France',
                'region' => 'Auvergne-Rhône-Alpes',
                'locality' => 'Lyon',
            ]
        ]);
    }

    public function test_user_can_reverse_search_place(): void
    {
        // Data
        $coordinates = ['lon' => 4.8357, 'lat' => 45.7640];
        $expectedDtoCollection = collect($this->geoCodeResult['features'])
            ->map(fn ($feature) => LocationFeatureDTO::fromArray($feature));

        // Mock
        $this->geoCodeMock->shouldReceive('reverseSearch')
            ->once()
            ->with($coordinates)
            ->andReturn($expectedDtoCollection);
        $this->app->instance(GeoCode::class, $this->geoCodeMock);

        // Test
        $response = $this->actingAs($this->user)
            ->post('/api/ors/reverse-search', $coordinates);

        // Assertions
        $response->assertStatus(200);
        $response->assertJson([
            [
                'name' => 'Lyon',
                'label' => 'Lyon, France',
                'longitude' => 4.8357,
                'latitude' => 45.7640,
                'country' => 'France',
                'region' => 'Auvergne-Rhône-Alpes',
                'locality' => 'Lyon',
            ]
        ]);
    }

    public function test_user_can_get_route(): void
    {
        $coordinates = [[8.681495, 49.41461], [8.686507, 49.41943]];
        $routeResult = [
            'features' => [
                [
                    'properties' => [
                        'summary' => [
                            'distance' => 0.891,
                            'duration' => 189.6,
                        ],
                    ],
                    'geometry' => [
                        'coordinates' => [
                            [8.681495, 49.414599],
                            [8.68147, 49.414599]
                        ]
                    ],
                ],
            ],
        ];

        $expectedDtoCollection = collect($routeResult['features'])
            ->map(fn ($feature) => RouteDataDTO::fromArray($feature));

        $this->directionMock->shouldReceive('route')
            ->once()
            ->with($coordinates)
            ->andReturn($expectedDtoCollection);
        $this->app->instance(Directions::class, $this->directionMock);

        $response = $this->actingAs($this->user)
            ->post("/api/ors/route", ['coordinates' => $coordinates]);

        $response->assertStatus(200);

        $response->assertJson([
            [
                'distance' => 0.891,
                'duration' => 189.6,
                'coordinates' => [
                    [8.681495, 49.414599],
                    [8.68147, 49.414599]
                ],
            ]
        ]);
    }
}
