<?php
namespace app\truck\controller;

use app\truck\model\WarningModel;
use app\truck\common\Tools;
use think\facade\Request;

class Api{
    
    public function getWarningByCarNum(){
        
    }
    
    public function warningCars(){
        $warningModel = new WarningModel();
        $cars = $warningModel->cars();
        return json($cars);
    }
    
    public function trucks(){
        
        $key = Request::param('key');
        if ($key == null)
            $key = array();
        
        if (array_key_exists('dadui', $key))
            $dadui = $key['dadui'];
        else 
            $dadui = '';
        
        if (array_key_exists('vehicleNo', $key))
        $vehicleNo = $key['vehicleNo'];
        
        if (!empty($vehicleNo)){
            $model = new WarningModel();
            $warnings = $model->findByCarNum($vehicleNo);
            $data['code'] = 0;
            $data['msg'] ='';
            $data['count']=count($warnings);
            $data['data'] = $warnings;
            return json($data);
        }

        
        
        $tool = new Tools();
        $endTime = $tool->currentUtcMicro();
//        $startTime = '1557582110000';
         $startTime = $endTime - $tool->dayToMicroseconds(3);
        $warningType = 'speed';
        
        $model = new WarningModel();
        $warnings = $model->warnings($startTime, $endTime, $warningType, $dadui);
        $data['code'] = 0;
        $data['msg'] ='';
        $data['count']=count($warnings);
        $data['data'] = $warnings;
        return json($data);
    }
    
}