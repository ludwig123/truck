<?php
namespace app\index\model;


class LawWaiter implements Waiter{
    
    public function reply($input)
    {
        if (!Guide::isLawSearch($input)){
            return null;
        }
        $lawName = $input[0];
        $lawIndex = '';
        for ($i = 1; $i < count($input); $i++){
            if (is_numeric($input[$i])){
                $lawIndex = $input[$i];
                break;
            }
        }
        
        $lawSearcher = new LawSearcher();
        return $lawSearcher->law($lawName, $lawIndex);
    }
    
    public function serviceType()
    {
        return "law-search";
    }



    
    
}