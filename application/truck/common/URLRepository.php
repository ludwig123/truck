<?php


namespace app\truck\common;


use app\truck\model\WarningModel;

class URLRepository
{
    /** 预警车辆的查询接口
     * @return string
     */
    public static function vehicleAlarmUrl()
    {
        return "https://jg.gghypt.net/hyjg/statistics/statisticsAction!findVehicleAlarmByPage.action";
    }

    /** 通过经纬度列表查询文字地址<br>
     * 如： ‘湖南省衡阳市衡南县黄土坳，向西南方向，230米’
     * @return string
     */
    public static function findAddrByLatitudeUrl()
    {
        return "https://jg.gghypt.net/hyjg/monitor/monitorAction!findAddressList.action";
    }

    public static function findTrackUrl()
    {
        return "https://jg.gghypt.net/hyjg/monitor/monitorAction!findTrack.action";
    }

    public static function findBasicInfoVehicleUrl()
    {
        return "https://jg.gghypt.net/hyjg/monitor/monitorAction!findBasicInfoVehicle.action";
    }

    public static function findVehicleForListUrl()
    {
        return "https://jg.gghypt.net/hyjg/statistics/statisticsAction!findVehicleForList.action";
    }
}