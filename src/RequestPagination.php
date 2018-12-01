<?php
/**
 * Created by PhpStorm.
 * User: qi
 * Date: 2018/6/25
 * Time: 14:02
 */

namespace Eastown\Pagination;


use Illuminate\Http\Request;

class RequestPagination extends Pagination
{
    private $request = null;

    public function request(Request $request)
    {
        $this->request = $request;
        return $this->conditions($this->adaptFlatConditions($this->parseInput('conditions', [])))
            ->selects($this->parseInput('selects', []))
            ->groups($this->parseInput('groups', []))
            ->sorts($this->parseInput('sorts', []))
            ->setPageSize($request->input('page_size', 20))
            ->setCurrentPage($request->input('current_page', 1));
    }

    public function paginate(callable $mapFunc = null)
    {
        return array_merge(parent::paginate($mapFunc), [
            'request' => $this->request ? $this->request->only(['conditions', 'selects', 'groups', 'sorts', 'sum_fields', 'page_size', 'current_page']) : null,
            'sum' => $this->sum($this->request ? $this->parseInput('sum_fields', []) : [])
        ]);
    }

    private function parseInput($field, $default = [])
    {
        $value = $this->request->input($field);
        if (is_string($value)) {
            $jsonValue = json_decode($value, true);
            if ($jsonValue) {
                $value = $jsonValue;
                $this->request->merge([
                    $field => $value
                ]);
            }
        }
        return $value ?: $default;
    }

    private function adaptFlatConditions($conditions)
    {
        $newConditions = [];
        foreach ($conditions as $field => $value) {
            if (is_numeric($field)) {
                $newConditions[] = $value;
            } else {
                $newConditions[] = (new FlatConditionAdapter($field, $value))->adapt();
            }
        }
        return $newConditions;
    }

}