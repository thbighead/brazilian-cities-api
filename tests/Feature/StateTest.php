<?php

namespace Tests\Feature;

use App\Repositories\State\Repository;
use Illuminate\Http\Request;
use Tests\TestCase;

class StateTest extends TestCase
{
    const RESOURCE_BASE_URI = '/api/states';
    const INVALID_TEST_STATE_ID = 204;
    const VALID_TEST_CITY_NAME = 'Iguaçu';
    const VALID_TEST_STATE_ID = 20;
    const VALID_TEST_STATE_ACRONYM = 'zz';
    const VALID_TEST_STATE_NAME = 'sao';

    public function testBasicIndex()
    {
        $this->getJson(self::RESOURCE_BASE_URI)->assertOk()
            ->assertJsonCount((new Repository(request()))->getIndexResources()->count(), 'data');
    }

    public function testWithRelationship()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'with_relationship' => true
        ])->assertJsonMissingValidationErrors(['with_relationship'])
            ->assertJsonStructure(['data' => [['cities']]]);
    }

    public function testWithRelationshipBoolValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'with_relationship' => self::TEST_NON_BOOL_VALUE
        ])->assertJsonValidationErrors(['with_relationship']);
    }

    public function testPaginate()
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

    public function testPaginateIntValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => self::TEST_NON_INT_VALUE
        ])->assertJsonValidationErrors(['paginate']);
    }

    public function testPaginateMinValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => 1
        ])->assertJsonValidationErrors(['paginate']);
    }

    public function testPage()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => self::ITEMS_PER_PAGE_TEST_VALUE,
            'page' => self::TEST_PAGINATION_PAGE,
        ])->assertJsonMissingValidationErrors(['page'])
            ->assertJsonPath('meta.current_page', self::TEST_PAGINATION_PAGE);
    }

    public function testPageIntValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => self::ITEMS_PER_PAGE_TEST_VALUE,
            'page' => self::TEST_NON_INT_VALUE,
        ])->assertJsonValidationErrors(['page']);
    }

    public function testPageMinValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'paginate' => self::ITEMS_PER_PAGE_TEST_VALUE,
            'page' => 0,
        ])->assertJsonValidationErrors(['page']);
    }

    public function testFilterByCityName()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'city_name' => self::VALID_TEST_CITY_NAME
        ])->assertJsonMissingValidationErrors(['city_name'])
            ->assertJsonCount((new Repository(request()))->getIndexResources()->count(), 'data');
    }

    public function testFilterByCityNameMinValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'city_name' => self::TEST_SMALL_STRING
        ])->assertJsonValidationErrors(['city_name']);
    }

    public function testFilterByStateName()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'state_name' => self::VALID_TEST_STATE_NAME
        ])->assertJsonMissingValidationErrors(['state_name'])
            ->assertJsonCount((new Repository(request()))->getIndexResources()->count(), 'data');
    }

    public function testFilterByStateNameMinValue()
    {
        $this->json('GET', self::RESOURCE_BASE_URI, [
            'state_name' => self::TEST_SMALL_STRING
        ])->assertJsonValidationErrors(['state_name']);
    }

    public function testAllOrderings()
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

    public function testAllOrderingsValidValues()
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
        $this->getJson(self::RESOURCE_BASE_URI . '/' . self::VALID_TEST_STATE_ID)
            ->assertOk()->assertJsonStructure([
                'data' => [
                    'id',
                    'acronym',
                    'name',
                    'cities',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    public function testShowNotFound()
    {
        $this->getJson(self::RESOURCE_BASE_URI . '/' . self::INVALID_TEST_STATE_ID)
            ->assertNotFound();
    }

    public function testStore()
    {
        $response = $this->postJson(self::RESOURCE_BASE_URI, [
            'acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::TEST_STRING_VALUE,
        ])->assertCreated()->assertJsonStructure([
            'data' => [
                'id',
                'acronym',
                'name',
                'cities',
            ]
        ])->assertJsonPath('data.acronym', strtoupper(self::VALID_TEST_STATE_ACRONYM))
            ->assertJsonPath('data.name', self::TEST_STRING_VALUE);

        return $response->decodeResponseJson('data.id');
    }

    public function testStoreAcronymRequired()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'name' => self::TEST_STRING_VALUE,
        ])->assertJsonValidationErrors(['acronym']);
    }

    public function testStoreAcronymSize()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'acronym' => self::TEST_SMALL_STRING,
            'name' => self::TEST_STRING_VALUE,
        ])->assertJsonValidationErrors(['acronym']);
    }

    public function testStoreUniqueAcronym()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::TEST_STRING_VALUE . '2',
        ])->assertJsonValidationErrors(['acronym']);
    }

    public function testStoreNameRequired()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'acronym' => self::VALID_TEST_STATE_ACRONYM,
        ])->assertJsonValidationErrors(['name']);
    }

    public function testStoreNameSize()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::TEST_SMALL_STRING,
        ])->assertJsonValidationErrors(['name']);
    }

    public function testStoreUniqueName()
    {
        $this->postJson(self::RESOURCE_BASE_URI, [
            'acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::TEST_STRING_VALUE,
        ])->assertJsonValidationErrors(['name']);
    }

    /**
     * @depends testStore
     * @param $id
     */
    public function testUpdate($id)
    {
        $this->putJson(self::RESOURCE_BASE_URI . "/$id", [
            'acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::VALID_TEST_CITY_NAME,
        ])->assertOk()->assertJsonStructure([
            'data' => [
                'id',
                'acronym',
                'name',
                'cities',
            ]
        ]);
    }

    public function testUpdateNotFound()
    {
        $this->putJson(self::RESOURCE_BASE_URI . '/' . self::INVALID_TEST_STATE_ID, [
            'acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => self::VALID_TEST_CITY_NAME,
        ])->assertJsonValidationErrors(['acronym', 'name']);
    }

    /**
     * @depends testStore
     * @param $id
     */
    public function testUpdateAcronymSize($id)
    {
        $this->putJson(self::RESOURCE_BASE_URI . "/$id", [
            'acronym' => self::TEST_SMALL_STRING,
        ])->assertJsonValidationErrors(['acronym']);
    }

    /**
     * @depends testStore
     * @param $id
     */
    public function testUpdateUniqueAcronym($id)
    {
        $this->putJson(self::RESOURCE_BASE_URI . "/$id", [
            'acronym' => 'SP',
        ])->assertJsonValidationErrors(['acronym']);
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
    public function testUpdateUniqueName($id)
    {
        $this->putJson(self::RESOURCE_BASE_URI . "/$id", [
            'acronym' => self::VALID_TEST_STATE_ACRONYM,
            'name' => 'São Paulo',
        ])->assertJsonValidationErrors(['name']);
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
        $this->deleteJson(self::RESOURCE_BASE_URI . '/' . self::INVALID_TEST_STATE_ID)->assertNotFound();
    }
}
