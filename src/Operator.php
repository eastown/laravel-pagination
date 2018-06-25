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
    const EQ = 'EQ';
    const GT = 'GT';
    const GTE = 'GTE';
    const LTE = 'LTE';
    const LT ='LT';
    const NE ='NE';
    const LIKE = 'LIKE';
    const NOT_LIKE = 'NOT_LIKE';
    const REGEXP = 'REGEXP';


    const IN = 'IN';
    const NOT_IN = 'NOT_IN';
    const BETWEEN = 'BETWEEN';
    const NOT_BETWEEN = 'NOT_BETWEEN';
    const HAS = 'HAS';
    const DOES_NOT_HAVE = 'DOES_NOT_HAVE';

}