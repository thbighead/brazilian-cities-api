<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Routing\Middleware\ThrottleRequests;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    const ITEMS_PER_PAGE_TEST_VALUE = 5;
    const TEST_PAGINATION_PAGE = 2;
    const TEST_NON_BOOL_VALUE = 'testing';
    const TEST_NON_INT_VALUE = 'testing';
    const TEST_STRING_VALUE = 'testing';
    const TEST_SMALL_STRING = 'a';

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }
}
