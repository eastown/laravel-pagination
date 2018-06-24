<?php
/**
 * Created by PhpStorm.
 * User: qi
 * Date: 2018/6/24
 * Time: 15:23
 */

namespace Eastown\Pagination;


class Operator
{
    // Raw operator
    const EQUAL = '=';
    const GT = '>';
    const GTE = '>=';
    const LTE = '<=';
    const LT ='<';
    const NE ='<>';
    const BETWEEN = 'BETWEEN';
    const LIKE = 'LIKE';
    const NOT_LIKE = 'NOT LIKE';
    const REGEXP = 'REGEXP';


    const IN = 'IN';
    const NOT_IN = 'NOT IN';
    const HAS = 'HAS';
    const DOES_NOT_HAVE = 'DOES NOT HAVE';
}