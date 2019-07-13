<?php
namespace app\truck\common;


const MICRO_SECONDS_PER_MINUTE = 60000;
const MICRO_SECONDS_PER_SECOND = 1000;
const MICRO_SECONDS_PER_HOUR = 3600000;
class TimeTranslator{
    /**获取当前时间的UTC*1000
     * @return mixed 13位整数
     */
    public static function currentUtcMicro(){
        return intval(microtime(true) * 1000);
    }
    
    /** 把 毫秒计的整数转为 时间字符串 2019-04-21 21:50:09
     * @param integer $microUtc
     * @return string
     */
    public static function microUtcToDateTime($microUtc){
        return date('Y-m-d H:i:s' , $microUtc/1000);
    }
    
    /** 把utc 转为 Mysql 的dateTime
     * @param integer $utc
     * @return string
     */
    public static function utcToDateTime($utc){
        return date('Y-m-d H:i:s' , $utc);
    }
}