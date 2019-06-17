<?php
namespace app\truck\common;

class Tools{
    /**获取当前时间的UTC*1000
     * @return mixed 13位整数
     */
    public function currentUtcMicro(){
        return intval(microtime(true) * 1000);
    }
    
    /** 把 毫秒计的整数转为 时间字符串 2019-04-21 21:50:09
     * @param integer $microUtc
     * @return string
     */
    public function microUtcToDateTime($microUtc){
        return date('Y-m-d H:i:s' , $microUtc/1000);
    }
    
    /** 把utc 转为 Mysql 的dateTime
     * @param integer $utc
     * @return string
     */
    public function utcToDateTime($utc){
        return date('Y-m-d H:i:s' , $utc);
    }
    
    /** 把一分钟转为毫秒
     * @param integer $day
     * @return number
     */
    public function minToMicroseconds($min){
        return $min * 1000 * 60;
    }
    
    /** 把一小时转为毫秒
     * @param integer $day
     * @return number
     */
    public function hourToMicroseconds($hour){
        return $this->minToMicroseconds(1) * 60;
    }
    
    /** 把一天转为毫秒
     * @param integer $day
     * @return number
     */
    public function dayToMicroseconds($day){
        return $this->hourToMicroseconds(1) * 24;
    }
}