<?php
/**
 * Created by PhpStorm.
 * User: qi
 * Date: 2018/6/24
 * Time: 16:45
 */

namespace Eastown\Pagination;


use Illuminate\Support\Facades\DB;

class Group implements QueryBuilder
{
    use RawVerify;

    private $field;

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function build(&$builder)
    {
        $builder = $builder->groupBy($this->raw($this->field));
    }
}