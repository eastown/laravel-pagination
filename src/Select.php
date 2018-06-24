<?php
/**
 * Created by PhpStorm.
 * User: qi
 * Date: 2018/6/24
 * Time: 16:45
 */

namespace Eastown\Pagination;


use Illuminate\Support\Facades\DB;

class Select implements QueryBuilder
{
    use RawVerify;

    private $field;

    private $asField;

    public function __construct(string $field, string $asField)
    {
        $this->field = $field;
        $this->asField = $asField;
    }

    public function build(&$builder)
    {
        $raw = "{$this->field} as {$this->asField}";
        $this->verifyRawSql($raw);
        $builder = $builder->addSelect(DB::raw($raw));
    }
}