<?php
/**
 * Created by PhpStorm.
 * User: qi
 * Date: 2018/6/24
 * Time: 18:02
 */

namespace Eastown\Pagination;


use Illuminate\Support\Facades\DB;

trait RawVerify
{
    public function verifyRawSql($rawSql)
    {
        $rawSql = strtolower($rawSql);
        $illegalKeywords = [
            ' from ',
            ' delete ',
            ' update ',
            ' drop ',
            ' truncate ',
            ' select ',
            ';'
        ];
        foreach($illegalKeywords as $keyword) {
            if(false !== strpos($rawSql, $keyword)) {
                throw new \InvalidArgumentException('Illegal select');
            }
        }
    }

    public function raw($sql)
    {
        $this->verifyRawSql($sql);
        return DB::raw($sql);
    }
}