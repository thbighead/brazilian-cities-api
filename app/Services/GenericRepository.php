<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;
use Str;

class GenericRepository
{
    /** @var Builder|EloquentBuilder */
    public $query;

    /**
     * GenericRepository constructor.
     * @param Builder|EloquentBuilder $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @param string $table_name
     * @param array $columns
     * @return $this
     */
    public function applyFilters($table_name, array $columns)
    {
        $resource_singular_name = Str::singular($table_name);

        foreach ($columns as $requested_field => $value) {
            $column = Str::after($requested_field, "{$resource_singular_name}_");
            $this->query->where("$table_name.$column", $value);
        }

        return $this;
    }

    /**
     * @param string $table_name
     * @param array $columns
     * @return $this
     */
    public function applyFreeTextFilters($table_name, array $columns)
    {
        $resource_singular_name = Str::singular($table_name);

        foreach ($columns as $requested_field => $text) {
            $column = Str::after($requested_field, "{$resource_singular_name}_");
            $like_text = '%' . implode('%', mb_str_split($text)) . '%';
            $this->query->where("$table_name.$column", 'like', $like_text);
        }

        return $this;
    }

    public function applyIndexFilteringAndOrdering(Request $request)
    {
        return $this->applyFreeTextFilters('cities', $request->only([
            'city_name'
        ]))->applyFreeTextFilters('states', $request->only([
            'state_name'
        ]))->applyFilters('states', $request->only([
            'state_acronym'
        ]))->applyOrdering('cities', $request->only([
            'order_by_city_id', 'order_by_city_name', 'order_by_city_created_at', 'order_by_city_updated_at'
        ]))->applyOrdering('states', $request->only([
            'order_by_state_id',
            'order_by_state_acronym',
            'order_by_state_name',
            'order_by_state_created_at',
            'order_by_state_updated_at'
        ]));
    }

    /**
     * @param $table_name
     * @param array $columns
     * @return $this
     */
    public function applyOrdering($table_name, array $columns)
    {
        $resource_singular_name = Str::singular($table_name);

        foreach ($columns as $requested_field => $order) {
            $column = Str::after($requested_field, "order_by_{$resource_singular_name}_");
            $this->query->orderBy("$table_name.$column", $order);
        }

        return $this;
    }

    /**
     * @param int|bool $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|EloquentBuilder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getQueryData($paginate = false)
    {
        if ($paginate === false) return $this->query->get();

        if (($paginate = (int)$paginate) > 0) return $this->query->paginate($paginate);

        return $this->query->paginate();
    }
}
