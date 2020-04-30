<?php

namespace App\Repositories\State;

use App\State;
use App\Services\GenericRepository;

trait Index
{
    public function getIndexResources()
    {
        $genericRepository = (new GenericRepository(State::query()->select('states.*')
            ->leftJoin('cities', 'cities.state_id', '=', 'states.id')))
            ->applyIndexFilteringAndOrdering($this->request);
        $genericRepository->query = $this->mountIndexRepository()->query
            ->fromSub($genericRepository->query, 'not_grouped_states')
            ->groupBy('not_grouped_states.id');
        return $genericRepository->getQueryData($this->request->get('paginate', false));
    }

    private function mountIndexRepository()
    {
        return new GenericRepository(($this->request->get('with_relationship') ? State::with([
            'cities' => function ($query) {
                if ($this->request->hasAny([
                    'city_name',
                    'order_by_city_id',
                    'order_by_city_name',
                    'order_by_city_created_at',
                    'order_by_city_updated_at',
                ])) {
                    (new GenericRepository($query))->applyFreeTextFilters('cities', $this->request->only([
                        'city_name'
                    ]))->applyOrdering('cities', $this->request->only([
                        'order_by_city_id',
                        'order_by_city_name',
                        'order_by_city_created_at',
                        'order_by_city_updated_at'
                    ]));
                }
            }
        ]) : State::query()));
    }
}
