<?php
namespace app\index\model;

class TruckWaiter implements Waiter
{
    public function __construct($cookie){
        cache('truckCookie', $cookie);
    }
    public function serviceType()
    {}

    public function reply($input)
    {
        return cache('truckCookie');;
    }

    
}