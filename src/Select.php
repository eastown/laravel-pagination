<?php
/**
 * Created by PhpStorm.
 * User: qi
 * Date: 2018/6/24
 * Time: 16:45
 */

namespace Eastown\Pagination;



class Select implements QueryBuilder
{
    use RawVerify;

    private $field;

    private $asField;

    public function __construct(string $field, string $asField = null)
    {
        $this->field = $field;
        $this->asField = $asField? : $field;
    }

    public function build(&$builder)
    {
        $raw = "{$this->field} as {$this->asField}";
        $builder = $builder->addSelect($this->raw($raw));
    }
}