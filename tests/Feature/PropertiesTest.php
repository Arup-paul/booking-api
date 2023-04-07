<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\Geoobject;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertiesTest extends TestCase
{

    use RefreshDatabase;

    public function test_property_owner_has_access_to_properties_feature()
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $response = $this->actingAs($owner)->getJson('/api/owner/properties');
        $response->assertStatus(200);
    }

    public function test_user_does_not_have_access_to_properties_feature()
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_USER]);
        $response = $this->actingAs($owner)->getJson('/api/owner/properties');

        $response->assertStatus(403);
    }

    public function test_property_owner_can_add_property()
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $response = $this->actingAs($owner)->postJson('/api/owner/properties', [
            'name' => 'My property',
            'city_id' => City::value('id'),
            'address_street' => 'Street Address 1',
            'address_postcode' => '12345',
        ]);

        $response->assertSuccessful();
        $response->assertJsonFragment(['name' => 'My property']);
    }

    public function test_property_search_by_country_returns_correct_results(): void
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $countries = Country::with('cities')->take(2)->get();
        $propertyInCountry = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $countries[0]->cities()->value('id')
        ]);
        $propertyInAnotherCountry = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $countries[1]->cities()->value('id')
        ]);

        $response = $this->getJson('/api/search?country=' . $countries[0]->id);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $propertyInCountry->id]);
    }

    public function test_property_search_by_geoobject_returns_correct_results(): void
    {
        $owner = User::factory()->create(['role_id' => Role::ROLE_OWNER]);
        $cityId = City::value('id');
        $geoobject = Geoobject::first();
        $propertyNear = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
            'lat' => $geoobject->lat,
            'long' => $geoobject->long,
        ]);
        $propertyFar = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $cityId,
            'lat' => $geoobject->lat + 10,
            'long' => $geoobject->long - 10,
        ]);

        $response = $this->getJson('/api/search?geoobject=' . $geoobject->id);

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $propertyNear->id]);
    }
}
