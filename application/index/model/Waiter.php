<?php
namespace app\index\model;

interface Waiter
{
    public function reply($input);
    public function serviceType();
}

