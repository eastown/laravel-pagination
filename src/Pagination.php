<?php namespace Eastown\Pagination;


use Illuminate\Support\Facades\DB;

class Pagination
{
    private $currentPage = 1;

    private $pageSize = 20;

    private $builder;

    private $groupCountBuilder;

    private $originalBuilder;

    public function __construct($builder)
    {
        $this->builder = $builder;
        $this->originalBuilder = clone $this->builder;
    }

    /**
     * @param int $currentPage
     * @return $this
     */
    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = abs($currentPage);
        return $this;
    }

    /**
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize(int $pageSize)
    {
        $this->pageSize = abs($pageSize);
        return $this;
    }

    public function conditions(array $conditions)
    {
        foreach ($conditions as $condition) {
            $condition->build($this->builder);
        }
        return $this;
    }

    public function sorts(array $sorts)
    {
        foreach ($sorts as $sort) {
            $sort->build($this->builder);
        }
        return $this;
    }

    public function selects(array $selects)
    {
        foreach ($selects as $select) {
            $select->build($this->builder);
        }
        return $this;
    }

    public function groups(array $groups)
    {
        $this->makeGroupCountBuilder($groups);
        foreach ($groups as $group) {
            $group->build($this->builder);
        }
        return $this;
    }

    private function makeGroupCountBuilder(array $groups)
    {
        $groups = join(',', array_map(function(Group $group){
            return $group->getField();
        }, $groups));
        $this->groupCountBuilder = (clone $this->originalBuilder)->select(DB::raw("COUNT(DISTINCT {$groups}) as total"));
    }

    public function sum(array $fields)
    {
        $raw = DB::raw(join(',', array_map(function($field){
            return "SUM({$field}) AS {$field}";
        }, $fields)));
        return (clone $this->builder)->select($raw)->first();
    }

    public function query()
    {
        return (clone $this->builder)->skip(($this->currentPage - 1) * $this->pageSize)->take($this->pageSize)->get();
    }

    public function total()
    {
        return $this->groupCountBuilder? (clone $this->groupCountBuilder)->value('total'): (clone $this->builder)->count();
    }

    public function paginate(callable $mapFunc = null)
    {
        $data = $this->query();
        $mapFunc and $data = $data->map($mapFunc);
        $total = $this->total();
        return [
            'data' => $data,
            'total' => $total,
            'page_size' => $this->pageSize,
            'current_page' => $this->currentPage
        ];
    }
}