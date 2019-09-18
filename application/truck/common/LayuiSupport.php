<?php


namespace app\truck\common;


class LayuiSupport
{
    private static function assembleArrayForLayuiTable($page, $limit,$dataArr)
    {
        $result = array();
        $result['code'] = 0;
        $result['msg'] = '';
        $result['count'] = count($dataArr);
        $result['data'] = self::getPageLimit($page, $limit,$dataArr);

        return $result;

    }

    public static function replyForTable($arr,$page=1, $limit=10)
    {
        return json(LayuiSupport::assembleArrayForLayuiTable($page, $limit,$arr));
    }

    private static function getPageLimit($page, $limit,$arr)
    {
        $start = ($page -1) * $limit;
        return array_splice($arr,$start,$limit);
    }

}