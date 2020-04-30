<?php

namespace App\Repositories\City;

use App\City;
use App\Services\GenericRepository;

trait Index
{
    public function getIndexResources()
    {
        return $this->mountIndexRepository()
            ->applyIndexFilteringAndOrdering($this->request)
            ->getQueryData($this->request->get('paginate', false));
    }

    private function mountIndexRepository()
    {
        return new GenericRepository(($this->request->get('with_relationship') ? City::with([
            'state' => function ($query) {
                if ($this->request->hasAny([
                    'state_name',
                    'state_acronym',
                    'order_by_state_id',
                    'order_by_state_acronym',
                    'order_by_state_name',
                    'order_by_state_created_at',
                    'order_by_state_updated_at',
                ])) {
                    (new GenericRepository($query))->applyFreeTextFilters('states', $this->request->only([
                        'state_name'
                    ]))->applyFilters('states', $this->request->only([
                        'state_acronym'
                    ]))->applyOrdering('states', $this->request->only([
                        'order_by_state_id',
                        'order_by_state_acronym',
                        'order_by_state_name',
                        'order_by_state_created_at',
                        'order_by_state_updated_at'
                    ]));
                }
            }
        ]) : City::query())->select('cities.*')
            ->leftJoin('states', 'cities.state_id', '=', 'states.id'));
    }
}
