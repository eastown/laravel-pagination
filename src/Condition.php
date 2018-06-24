<?php
/**
 * Created by PhpStorm.
 * User: qi
 * Date: 2018/6/24
 * Time: 15:09
 */

namespace Eastown\Pagination;


class Condition implements QueryBuilder
{
    private $field;

    private $operator;

    private $value;

    public function __construct(string $field, string $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function build(&$builder)
    {
        if ($this->operator == Operator::HAS) {
            $builder = $builder->whereHas($this->field, function ($query) {
                $conditions = is_array($this->value)? $this->value: [$this->value];
                foreach($conditions as $condition) {
                    $condition->build($query);
                }
            });
            return;
        }

        if ($this->operator == Operator::DOES_NOT_HAVE) {
            $builder = $builder->whereDoesntHave($this->field, function ($query) {
                $conditions = is_array($this->value)? $this->value: [$this->value];
                foreach($conditions as $condition) {
                    $condition->build($query);
                }
            });
            return;
        }

        if ($this->operator == Operator::IN) {
            $builder = $builder->whereIn($this->field, (array)$this->value);
            return;
        }

        if ($this->operator == Operator::NOT_IN) {
            $builder = $builder->whereNotIn($this->field, (array)$this->value);
            return;
        }

        $builder = $builder->where($this->field, $this->operator, $this->value);
        return;
    }

}