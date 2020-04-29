<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

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
        foreach ($columns as $column => $value) {
            $this->query->where("$table_name.$column", $value);
        }

        return $this;
    }

    /**
     * @param $table_name
     * @param array $columns
     * @return $this
     */
    public function applyOrdering($table_name, $columns)
    {
        foreach ($columns as $column => $order) {
            $this->query->orderBy("$table_name.$column", $order);
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
        foreach ($columns as $column => $text) {
            $like_text = '%' . implode('%', mb_str_split($text)) . '%';
            $this->query->where("$table_name.$column", 'like', $like_text);
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

        if (is_int($paginate)) return $this->query->paginate($paginate);

        return $this->query->paginate();
    }
}
