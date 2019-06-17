<?php
namespace app\index\model;

class BusWaiter implements Waiter
{
    public function reply($input)
    {
        if (!Guide::isCarSearch($input)){
            return null;
        }
        //车牌第二位必须是字母
        if (! $this->isAlpha($input[1])){
            return "车牌第2位应该是字母!";
        }
        
        $fiveChar = "";
        for ($i = 2; $i < count($input); $i++){
            $fiveChar .= $input[$i];
        }
            
        if (! $this->isFiveChar($fiveChar)){
            return '车牌后5位应该是由数字和字母组成!';
        }
        
        $busID = "";
        for ($i = 0; $i < count($input); $i++){
            $busID .= $input[$i];
        }
        
        $codeSearcher = new CodeSearcher();
        
        return $codeSearcher->warningCar($busID);
       
    }

    private function isAlpha($str){
        $pattern = '/[a-zA-z]/u';
        $mathces = array();
        preg_match_all($pattern, $str, $mathces);
        if ($mathces[0] ==null)
        {
            return false;
        }
        return true;
    }
    
    private function isFiveChar($str){
        $pattern = '/[a-zA-z0-9]/u';
        $mathces = array();
        if (preg_match_all($pattern, $str, $mathces))
        {
            if ($mathces == null)
                return false;
                if (count($mathces[0]) != 5)
                    return false;
        }
        return true;
    }
    public function serviceType()
    {
        return "bus-search";
    }

}

