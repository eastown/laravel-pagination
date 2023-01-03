<?php namespace Eastown\Pagination;


use Illuminate\Support\Arr;

class Pagination
{
    use RawVerify;

    protected $currentPage = 1;

    protected $pageSize = 20;

    protected $builder;

    protected $groupCountBuilder;

    protected $originalBuilder;

    protected $sumBuilder;

    protected $hasGroups = false;

    public function __construct($builder)
    {
        $this->builder = $builder;
        $this->originalBuilder = clone $this->builder;
        $this->groupCountBuilder = clone $this->builder;
        $this->sumBuilder = clone $this->builder;
    }


    public function reset()
    {
        $this->builder = clone $this->originalBuilder;
        $this->groupCountBuilder = clone $this->originalBuilder;
        $this->sumBuilder = clone $this->originalBuilder;
        return $this;
    }

    public function getBuilder()
    {
        return $this->builder;
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
            is_array($condition) and $condition = new Condition(...$condition);
            $condition->build($this->builder);
            $condition->build($this->groupCountBuilder);
            $condition->build($this->sumBuilder);
        }
        return $this;
    }

    public function sorts(array $sorts)
    {
        foreach ($sorts as $key => $sort) {
            if(is_numeric($key)) {
                is_array($sort) and $sort = new Sort(...$sort);
            }else {
                $sort = new Sort($key, $sort);
            }
            $sort->build($this->builder);
        }
        return $this;
    }

    public function selects(array $selects)
    {
        foreach ($selects as $select) {
            is_array($select) and $select = new Select(...$select);
            is_string($select) and $select = new Select(...[$select]);
            $select->build($this->builder);
        }
        return $this;
    }

    public function groups(array $groups)
    {
        foreach ($groups as &$group) {
            is_array($group) and $group = new Group(...$group);
            is_string($group) and $group = new Group(...[$group]);
            $group->build($this->builder);
        }
        if($groups) {
            $this->hasGroups = true;
            $this->makeGroupCountBuilder($groups);
        }
        return $this;
    }

    private function makeGroupCountBuilder(array $groups)
    {
        $groups = join(',', array_map(function(Group $group){
            return $group->getField();
        }, $groups));
        $this->groupCountBuilder->select($this->raw("COUNT(DISTINCT {$groups}) as total"));
    }

    public function sum(array $fields)
    {
        if(!$fields) {
            return [];
        }
        $raw = $this->raw(join(',', array_map(function($field){
            return "SUM({$field}) AS {$field}";
        }, $fields)));
        return array_map('floatval', Arr::only((clone $this->sumBuilder)->select($raw)->first()->toArray(), $fields));
    }

    public function query()
    {
        return (clone $this->builder)->skip(($this->currentPage - 1) * $this->pageSize)->take($this->pageSize)->get();
    }

    public function total()
    {
        return $this->hasGroups? (clone $this->groupCountBuilder)->value('total'): (clone $this->builder)->count();
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
