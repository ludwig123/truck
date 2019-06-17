<?php

// 应用公共文件

/**统计数据表的行数
 * @param string $name
 * @return number
 */
function countTableRow($name)
{
    return count(db($name)->select());
}

/**目标文本是否存在指定字符，只要存在就返回true
 * @param String $pattern eg:/[0-9]?/u
 * @param String $srcText 目标文本
 * @return boolean
 */
function isMatch($pattern , $srcText){
    $mathces = array();
    preg_match_all($pattern, $srcText, $mathces);
    if ($mathces[0] ==null)
    {
        return false;
    }
    return true;
}

function strftimeBejing($unixTime){
   return gmstrftime("%Y-%m-%d %H:%M:%S 星期%u", floatval($unixTime)+8*3600);
}