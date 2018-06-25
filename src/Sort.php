<?php
/**
 * Created by PhpStorm.
 * User: qi
 * Date: 2018/6/24
 * Time: 16:17
 */

namespace Eastown\Pagination;


class Sort implements QueryBuilder
{
    const SORT_ASC = 'ASC';

    const SORT_DESC = 'DESC';

    private $field;

    private $direction;

    public function __construct(string $field, string $direction)
    {
        $this->field = $field;
        $this->direction = $direction;
    }

    public function build(&$builder)
    {
        $builder = $builder->orderBy($this->field, $this->direction);
    }
}