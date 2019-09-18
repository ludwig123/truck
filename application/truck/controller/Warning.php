<?php
namespace app\truck\controller;

use const app\truck\common\MICRO_SECONDS_PER_MINUTE;
use app\truck\common\SpeedCar;
use app\truck\common\TiredCar;
use app\truck\model\WarningModel;
use app\truck\model\WarningUpdateLog;
use app\truck\common\NetWorker;
use app\truck\common\TimeTranslator;
use think\facade\Request;

use think\Controller;

/** 1、post_data 中的des和asc 排序是没有用的，永远都是时间utc值大的在列表的前面
 *  2、抓取间隔sleep 为1 秒，能勉强抓完一天的数据而不导致curl 出错
 *   2.1 目前发现以下错误： sleep 10 时候，curl_erro 7 ，无法建立连接；
 *                    sleep 1 时候， curl_erro 52 ,curl 没有获取到数据，实际上是没爬完的；
 *  3、alarmStartUtc 的值有问题，经常不是正确的utc 时间
 * 
 * 
 * 
 * 
 * @author ludwig
 * 2019年4月22日 下午7:22:11
 */
class Warning extends Controller
{

    public function index()
    {
        return $this->fetch();
        
    }

    //手动添加cookie
    public function cookie(){
        $cookie= Request::get('cookie');
        if (!empty($cookie)){
            NetWorker::setCookie($cookie);
        }
        return $this->fetch();
    }

    public function car()
    {
        $speedCar = new SpeedCar(true);
        $speedCar->speedCar();

        $tiredCar = new TiredCar(true);
        $tiredCar->tiredCar();
    }


    public function tiredCarChina()
    {
        $tiredCar = new TiredCar(true);
        $tiredCar->tiredCar();

    }

    public function tiredCarHunan()
    {
        $tiredCar = new TiredCar(false);
        $tiredCar->tiredCar();

    }

    public function speedCarChina()
    {
        $speedCar = new SpeedCar(true);
        $speedCar->speedCar();

    }

    public function speedCarHunan(){
        $speedCar = new SpeedCar(false);
        $speedCar->speedCar();
    }






    



}