<?php

namespace Tests\Feature;

use App\Repositories\City\Repository;
use Illuminate\Http\Request;
use Tests\TestCase;

class CityTest extends TestCase
{
    const RESOURCE_BASE_URI = '/api/cities';
    const INVALID_TEST_CITY_ID = '33045578';
    const INVALID_TEST_STATE_ACRONYM = 'gg';
    const VALID_TEST_CITY_ID = '3304557';
    const VALID_TEST_CITY_NAME = 'Iguaçu';
    const VALID_TEST_STATE_NAME = 'sao';
    const VALID_TEST_STATE_ACRONYM = 'sp';

    public function testBasicIndex()
    {
        $this->getJson(self::RESOURCE_BASE_URI)->assertOk()
            ->assertJsonCount((new Repository(request()))->getIndexResources()->count(), 'data');
    }

    public function testIndexWithRelationship()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'with_relationship' => true
        ])->assertJsonMissingValidationErrors(['with_relationship'])
            ->assertJsonStructure(['data' => [['state']]]);
    }

    public function testIndexWithRelationshipBoolValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'with_relationship' => self::TEST_NON_BOOL_VALUE
        ])->assertJsonValidationErrors(['with_relationship']);
    }

    public function testIndexPaginate()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => self::ITEMS_PER_PAGE_TEST_VALUE
        ])->assertJsonMissingValidationErrors(['paginate'])
            ->assertJsonCount(self::ITEMS_PER_PAGE_TEST_VALUE, 'data')
            ->assertJsonStructure(['links'])
            ->assertJsonPath('meta.total', (new Repository(new Request()))->getIndexResources()->count())
            ->assertJsonPath('meta.per_page', self::ITEMS_PER_PAGE_TEST_VALUE)
            ->assertJsonPath('meta.current_page', 1);
    }

    public function testIndexPaginateIntValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => self::TEST_NON_INT_VALUE
        ])->assertJsonValidationErrors(['paginate']);
    }

    public function testIndexPaginateMinValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => 1
        ])->assertJsonValidationErrors(['paginate']);
    }

    public function testIndexPage()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => self::ITEMS_PER_PAGE_TEST_VALUE,
            'page' => self::TEST_PAGINATION_PAGE,
        ])->assertJsonMissingValidationErrors(['page'])
            ->assertJsonPath('meta.current_page', self::TEST_PAGINATION_PAGE);
    }

    public function testIndexPageIntValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => self::ITEMS_PER_PAGE_TEST_VALUE,
            'page' => self::TEST_NON_INT_VALUE,
        ])->assertJsonValidationErrors(['page']);
    }

    public function testIndexPageMinValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => self::ITEMS_PER_PAGE_TEST_VALUE,
            'page' => 0,
        ])->assertJsonValidationErrors(['page']);
    }

    public function testIndexFilterByCityName()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'city_name' => self::VALID_TEST_CITY_NAME
        ])->assertJsonMissingValidationErrors(['city_name'])
            ->assertJsonCount((new Repository(request()))->getIndexResources()->count(), 'data');
    }

    public function testIndexFilterByCityNameMinValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'city_name' => self::TEST_SMALL_STRING
        ])->assertJsonValidationErrors(['city_name']);
    }

    public function testIndexFilterByStateName()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'state_name' => self::VALID_TEST_STATE_NAME
        ])->assertJsonMissingValidationErrors(['state_name'])
            ->assertJsonCount((new Repository(request()))->getIndexResources()->count(), 'data');
    }

    public function testIndexFilterByStateNameMinValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'state_name' => self::TEST_SMALL_STRING
        ])->assertJsonValidationErrors(['state_name']);
    }

    public function testIndexAllOrderings()
    {
        $orders = [
            'order_by_city_created_at',
            'order_by_city_id',
            'order_by_city_name',
            'order_by_city_updated_at',
            'order_by_state_acronym',
            'order_by_state_created_at',
            'order_by_state_id',
            'order_by_state_name',
            'order_by_state_updated_at',
        ];

        foreach ($orders as $order) {
            $this->json('GET', self::RESOURCE_BASE_URI, [
                $order => 'desc'
            ])->assertJsonMissingValidationErrors([$order]);
        }
    }

    public function testIndexAllOrderingsValidValues()
    {
        $orders = [
            'order_by_city_created_at',
            'order_by_city_id',
            'order_by_city_name',
            'order_by_city_updated_at',
            'order_by_state_acronym',
            'order_by_state_created_at',
            'order_by_state_id',
            'order_by_state_name',
            'order_by_state_updated_at',
        ];

        foreach ($orders as $order) {
            $this->json('GET', self::RESOURCE_BASE_URI, [
                $order => self::TEST_SMALL_STRING
            ])->assertJsonValidationErrors([$order]);
        }
    }

    public function testShow()
    {
        $this->getJson(self::RESOURCE_BASE_URI . '/' . self::VALID_TEST_CITY_ID)
            ->assertOk()->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'state' => [
                        'id',
                        'acronym',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    public function testShowNotFound()
    {
        $this->getJson(self::RESOURCE_BASE_URI . '/' . self::INVALID_TEST_CITY_ID)
            ->assertNotFound();
    }

    public function testStore()
    {
        $response = $this->postJson(self::RESOURCE_BASE_URI, [
            'state_acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::TEST_STRING_VALUE,
        ])->assertCreated()->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'state' => [
                    'id',
                    'acronym',
                    'name'
                ],
            ]
        ])->assertJsonPath('data.name', self::TEST_STRING_VALUE)
            ->assertJsonPath('data.state.acronym', strtoupper(self::VALID_TEST_STATE_ACRONYM));

        return $response->decodeResponseJson('data.id');
    }

    public function testStoreAcronymRequired()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'name' => self::TEST_STRING_VALUE,
        ])->assertJsonValidationErrors(['state_acronym']);
    }

    public function testStoreAcronymSize()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'state_acronym' => self::TEST_SMALL_STRING,
            'name' => self::TEST_STRING_VALUE,
        ])->assertJsonValidationErrors(['state_acronym']);
    }

    public function testStoreInvalidAcronym()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'state_acronym' => self::INVALID_TEST_STATE_ACRONYM,
            'name' => self::TEST_STRING_VALUE,
        ])->assertStatus(422);
    }

    public function testStoreNameRequired()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'state_acronym' => self::VALID_TEST_STATE_ACRONYM,
        ])->assertJsonValidationErrors(['name']);
    }

    public function testStoreNameSize()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'state_acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::TEST_SMALL_STRING,
        ])->assertJsonValidationErrors(['name']);
    }

    public function testStoreUniqueCityInState()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'state_acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::TEST_STRING_VALUE,
        ])->assertStatus(422);
    }

    /**
     * @depends testStore
     * @param $id
     */
    public function testUpdate($id)
    {
        $this->putJson(self::RESOURCE_BASE_URI . "/$id", [
            'state_acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::VALID_TEST_CITY_NAME,
        ])->assertOk()->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'state' => [
                    'id',
                    'acronym',
                    'name'
                ],
            ]
        ]);
    }

    public function testUpdateNotFound()
    {
        $this->putJson(self::RESOURCE_BASE_URI . '/' . self::INVALID_TEST_CITY_ID, [
            'state_acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::VALID_TEST_CITY_NAME,
        ])->assertNotFound();
    }

    /**
     * @depends testStore
     * @param $id
     */
    public function testUpdateAcronymSize($id)
    {
        $this->putJson(self::RESOURCE_BASE_URI . "/$id", [
            'state_acronym' => self::TEST_SMALL_STRING,
        ])->assertJsonValidationErrors(['state_acronym']);
    }

    /**
     * @depends testStore
     * @param $id
     */
    public function testUpdateInvalidAcronym($id)
    {
        $this->putJson(self::RESOURCE_BASE_URI . "/$id", [
            'state_acronym' => self::INVALID_TEST_STATE_ACRONYM,
        ])->assertStatus(422);
    }

    /**
     * @depends testStore
     * @param $id
     */
    public function testUpdateNameSize($id)
    {
        $this->putJson(self::RESOURCE_BASE_URI . "/$id", [
            'name' => self::TEST_SMALL_STRING,
        ])->assertJsonValidationErrors(['name']);
    }

    /**
     * @depends testStore
     * @param $id
     */
    public function testUpdateUniqueCityInState($id)
    {
        $this->putJson(self::RESOURCE_BASE_URI . "/$id", [
            'state_acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => 'São Paulo',
        ])->assertStatus(422);
    }

    /**
     * @depends testStore
     * @param $id
     */
    public function testDestroy($id)
    {
        $this->deleteJson(self::RESOURCE_BASE_URI . "/$id")->assertOk();
    }

    public function testDestroyNotFound()
    {
        $this->deleteJson(self::RESOURCE_BASE_URI . '/' . self::INVALID_TEST_CITY_ID)->assertNotFound();
    }
}
