<?php
namespace app\truck\model;

use Exception;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Model;
use app\truck\common\Tools;

/**
 *
 * @author ludwig
 *         2019年4月19日 下午7:17:03
 */
class WarningModel extends Model
{
    private $positions;
    
    function __construct(){
        parent::__construct();
        $positions = Db::name('position')->select();
        $this->positions = $positions;
    }

    // 存入一条记录
    public function add($rows)
    {
        //确保不会因为arlrmId 重复导致全部失败
        $records = $this->prepareRecords($rows);
        $sum = 0;
        foreach ($records as $k => $record){
            try {
            //不需要前缀wp_
            $result = Db::name('warning')->insert($record);
            if ($result == 1)
               $sum++;
            }
            catch (Exception $e){
//                var_dump($e);
            }
        }
        return $sum;
    }

    /**
     * @param array $rows
     * @return array
     */
    public function prepareRecords($rows)
    {
        $records = array();
        
        foreach ($rows as $k => $row) {
            $record['areaCode'] = $row['areaCode'];
            $record['cityId'] = $row['cityId'];
            $record['county'] = $row['county'];
            
            $record['alarmId'] = $row['alarmId'];
            
            // 以下全部都是int 数值
            $record['maplon'] = intval($row['maplon']);
            $record['maplat'] = intval($row['maplat']);
            $record['direction'] = intval($row['direction']);
            
            
            
            $record['alarmCode'] = intval($row['alarmCode']);
            
            $record['limitSpeed'] = intval($row['limitSpeed']);
            $record['gpsSpeed'] = intval($row['gpsSpeed']) / 10;
            
            $record['utc'] = intval($row['utc']);
            $record['alarmSysutcEnd'] = intval($row['alarmSysutcEnd']);
            $record['alarmStartUtc'] = intval($row['alarmStartUtc']);
            $record['sysutc'] = intval($row['sysutc']);
            $record['alarmEndUtc'] = intval($row['alarmEndUtc']);
            // int 到此结束
            
            $record['vehicleNo'] = $row['vehicleNo'];
            $record['companyname'] = $row['companyname'];
            $record['alarmAddr'] = $row['alarmAddr'];
            
            //方便输出excel新增的字段
            $record['durationTime'] = $this->CalculateDuration($row);
            $record['warningTime'] = date("Y-m-d H:i:s" , ($record['utc'] / 1000));
            
            $tempIndex = $this->findNearestPosition($record['maplat']/600000, $record['maplon']/600000);
            //如果不在高速公路上
            if ($tempIndex == false)
                continue;
            
            //辖区信息
            $record['dadui'] = $this->positions[$tempIndex]['dadui'];
            $record['expressway'] = $this->positions[$tempIndex]['expressway'];
            $record['pileNo'] = $this->positions[$tempIndex]['pileNo'];
            
            $records[] = $record;
        }
        
        return $records;
    }
    
    
    //

    /**
     * 查询车牌的记录
     * @param string $carNum
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function findByCarNum($carNum)
    {
        return Db::name('warning')->where('vehicleNo', '=', $carNum )->limit(200)->select();
    }
    
    public function getLastWarning(){
       return  Db::name('warning')->max('utc');
    }

    /** 数据库里最近的一次超速时间utc(micro second)
     * @return string
     */
    public function getLastSpeedWarning(){
        return  Db::name('warning')->where('alarmCode', '<>', '2')->max('utc');
    }
    /** 数据库里最近的一次疲劳时间utc(micro second)
     * @return string
     */
    public function getLastTiredWarning(){
        return  Db::name('warning')->where('alarmCode', '=', '2')->max('utc');
    }
    
    
    public function CalculateDuration($row){
        $duration = '';
        if ($row['alarmCode'] == 2){
            $duration  = $this->tiredTimeDuration($row);
        }
        
        else {
            $duration = $this->speedTimeDuration($row);
        }
        
        return $duration;
    }

    /**
     * 超速持续时间
     *
     * @param array $record
     * @return number
     */
    private function speedTimeDuration($record)
    {
        //$record['alarmSysutcEnd'] 可能为空，防止出错
        $alarmTime = (intval($record['alarmSysutcEnd']) - $record['sysutc']) / 1000;
        
        if ($alarmTime <= 0) { // 若时长为0或负数，则超速时长为5秒
            return $this->formatTime(5);
        } else if ($record['alarmSysutcEnd'] == 0 || $record['sysutc'] == null) { // 报警结束时间为空，则以1分钟作为超速时长
            return $this->formatTime(60);
        } else if ($alarmTime > 1200) { // 超速时长大于20分钟，则以1分钟作为超速时长
            return $this->formatTime(60);
        } else {
            return $this->formatTime($alarmTime);
        }
        
        return $alarmTime;
    }

    public function tiredTimeDuration($row)
    {
        //如果警报还没结束
        //to-do 如果之后警报结束了，我该如何更新这条记录呢？
        if ($row['alarmSysutcEnd'] == "" || $row['alarmSysutcEnd'] == null) {
            // $now = dateFormat(new Date(), "yyyy-MM-dd hh:mm:ss");
            $now = microtime(true);
            $alarmTime = ($now - $row['sysutc']) / 1000;
            if ($alarmTime <= 0) {
                return "--";
            } else {
                if ($alarmTime > 1200) {
                    return $this->formatTime(1200);
                } else {
                    return $this->formatTime($alarmTime);
                }
            }
        } else {
            $alarmTime = ($row['alarmSysutcEnd'] - $row['sysutc']) / 1000;
            if ($alarmTime <= 0) {
                return "--";
            } else {
                return $this->formatTime($alarmTime);
            }
        }
    }

    /**
     * 报警时间
     *
     * @param array $record
     * @return string
     */
    public function alarmTime($record)
    {
        return $record['utc'];
    }

    /** 以秒为单位的数字字符串
     * @param string $Stime
     * @return string
     */
    public function formatTime($Stime)
    {
        $result = "";
        $NumTime = intval($Stime);
        $hours = $NumTime / 3600;
        $hours = floor($hours);
        if ($hours != 0) {
            $result = $hours."时";
        }
        $minus = $NumTime % 3600;
        $minu = $minus / 60;
        $minu = floor($minu);
        if ($minu != 0) {
            $result = $result.$minu."分";
        }
        $second = $minus - $minu * 60;
        $result = $result.$second."秒";
        return $result;
    }
    
    //货车平台是高德地图坐标，可视化融合平台是百度坐标,传入的是高德的坐标
    public function findNearestPosition($lat, $lng){
        $nearest = 10000;
        $index = 0;
        $bd_data = $this->bd_encrypt($lng, $lat);
        $lat = $bd_data['bd_lat'];
        $lng = $bd_data['bd_lon'];
        
        foreach ($this->positions as $k => $v){
            $distance = abs($lat - $v['lat']) + abs($lng - $v['lng']);
            if ($distance < $nearest){
                $nearest = $distance;
                $index = $k;
            }
        }
        /*粗略估计同一经纬度下 ，1公里=0.01度， 直角三角形的斜边长度约为直角边的1.414倍;
         * 所以斜边一公里大概为0.01414度，这里取5公里范围，超过5公里即认为不在高速公路上
         * 0.0707 = 0.01414 * 5
        */
        if ($nearest < 0.01414)
            return $index;
        else 
            return false;
    }
        
    /**计算距离的平方  简化版的 
     * @param float $pa
     * @param float $pb
     * @return number
     */
    public function distanceCalc($pa, $pb){
        $distance = abs($pa['lng'] - $pb['lng']) + abs($pa['lat'] - $pb['lat']);
        return $distance;
    }
    
    //GCJ-02(火星，高德) 坐标转换成 BD-09(百度) 坐标
    //@param bd_lon 百度经度
    //@param bd_lat 百度纬度
    public function bd_encrypt($gg_lon,$gg_lat)
    {
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $gg_lon;
        $y = $gg_lat;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $data['bd_lon'] = $z * cos($theta) + 0.0065;
        $data['bd_lat'] = $z * sin($theta) + 0.006;
        return $data;
    }
    //BD-09(百度) 坐标转换成  GCJ-02(火星，高德) 坐标
    //@param bd_lon 百度经度
    //@param bd_lat 百度纬度
   public function bd_decrypt($bd_lon,$bd_lat)
    {
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $bd_lon - 0.0065;
        $y = $bd_lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $data['gg_lon'] = $z * cos($theta);
        $data['gg_lat'] = $z * sin($theta);
        return $data; //phpfensi.com
    } 
    
    public function cars(){
         $tools = new Tools();
         $NowUtc = $tools->currentUtcMicro();
         //一天 86400000
         $beginUtc = $NowUtc - 86400000 * 1;
         //$result =  Db::query("SELECT DISTINCT vehicleNo, companyname, alarmCode, limitSpeed, gpsSpeed, durationTime, warningTime, alarmAddr, expressway, pileNo, dadui FROM wp_warning where utc > ".$beginUtc);
        return $result = null;
    }
    
    public function warnings($startTime, $endTime, $warningType, $dadui){
        if ($warningType == 'tired')
            return $this->tiredWarnings($startTime, $endTime, $dadui);
            else if ($warningType == 'speed')
            return  $this->speedWarnings($startTime, $endTime, $dadui);
    }
    
    public function tiredWarnings($startTime, $endTime, $dadui){
        
        $map[] = ['alarmCode', '=', '2'];
        
        $result = $this->realsearchWarning($startTime, $endTime, $map, $dadui);
        return $result;
    }
    
    public function speedWarnings($startTime, $endTime, $dadui){
        
        $map[] = ['alarmCode', '<>', '2'];
        $result = $this->realsearchWarning($startTime, $endTime, $map, $dadui);
        return $result;
    }
    
    private function realsearchWarning($startTime, $endTime, $map, $dadui){
        $map[] = ['utc', '>', $startTime];
        $map[] = ['utc', '<', $endTime];
        if (!empty($dadui)) 
            $map[] = ['dadui', '=', $dadui];
        
        return Db::name('warning')->where($map)->limit(200)->select();
    }
}