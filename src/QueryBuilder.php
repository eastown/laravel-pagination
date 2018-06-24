<?php
/**
 * Created by PhpStorm.
 * User: qi
 * Date: 2018/6/24
 * Time: 16:16
 */

namespace Eastown\Pagination;


interface QueryBuilder
{
    public function build(&$builder);
}