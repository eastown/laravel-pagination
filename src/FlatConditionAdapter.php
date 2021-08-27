<?php
/**
 * Created by PhpStorm.
 * User: qi
 * Date: 2018/9/11
 * Time: 17:12
 */

namespace Eastown\Pagination;


use Illuminate\Support\Arr;

class FlatConditionAdapter
{
    private $suffixMap = [
        '__gte__' => Operator::GTE,
        '__lte__' => Operator::LTE,
        '__lt__' => Operator::LT,
        '__gt__' => Operator::GT,
        '__ne__' => Operator::NE,
        '__eq__' => Operator::EQ,
        '__between__' => Operator::BETWEEN,
        '__like__' => Operator::LIKE,
        '__unlike__' => Operator::NOT_LIKE,
        '__regexp__' => Operator::REGEXP,
        '__in__' => Operator::IN,
        '__nin__' => Operator::NOT_IN,
        '__scope__' => Operator::SCOPE,
        '__both__' => Operator::BOTH
    ];

    private $field, $value, $operator, $nestedFields;

    public function __construct($field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function adapt()
    {
        if(is_array($this->value)) {
            return new Condition($this->field, Operator::HAS, RequestPagination::adaptFlatConditions($this->value));
        }
        $this->processSuffix();
        $this->processNestedFields();
        $nestedFields = array_reverse($this->nestedFields);
        $condition = [];
        foreach ($nestedFields as $key => $field) {
            if ($key == 0) {
                $condition = new Condition($field, $this->operator, $this->value);
            } else {
                $condition = new Condition($field, Operator::HAS, $condition);
            }
        }
        return $condition;
    }

    private function processSuffix()
    {
        $result = preg_match_all('/(__\w+__)/i', $this->field, $matches);
        $this->operator = $result ? Arr::get($this->suffixMap, end($matches[1]), Operator::EQ) : Operator::EQ;
    }


    private function processNestedFields()
    {
        $this->nestedFields = explode('.', preg_replace('/(__\w+__)/i', '', $this->field));
    }
}
