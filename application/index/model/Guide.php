<?php
namespace app\index\model;

use app\index\model\Tokenizer;

/**
 * 导购员
 *
 * @author ludwig
 *        
 */
class Guide
{

    private $userID, $input, $rawInput;

    /**
     *
     * @param String $userID
     *            用户ID
     * @param String $rawInput
     *            用户的消息
     */
    public function __construct($userID, $rawInput)
    {
        $this->userID = $userID;
        $this->rawInput = $rawInput;
        $tokenizer = new Tokenizer();
        $this->input = $tokenizer->split($rawInput);
    }

    public function startTalk()
    {
        if (count($this->input) == 0)
            return "您的输入不存在！";
        
        $waiter = $this->lastWaiter();
        
        // 找到上次的服务员
        if ($waiter != null) {
            $reply = $waiter->reply($this->input);
            if ($reply == null) {
                $waiter = $this->newWaiter();
            }
        }        // 没有找到上次的服务员
        else {
            $waiter = $this->newWaiter();
        }
        
        $reply = $waiter->reply($this->input);
        
        $this->registerWaiter($waiter);
        return $reply;
    }

    public function lastWaiter()
    {
        $waiter = cache($this->userID);
        return $waiter;
    }

    private function registerWaiter($waiter)
    {
        cache($this->userID, $waiter, 180);
    }

    public function newWaiter()
    {
        if (self::isCarSearch($this->input)) {
            return new BusWaiter();
        }
        if (self::isLawSearch($this->input)) {
            return new LawWaiter($this->input);
        }
        
        if (self::isCookie($this->rawInput)){
            return new TruckWaiter($this->rawInput);
        }
        
        return new CodeWaiter();
    }

    static function isCarSearch($input)
    {
        $dictstr = "京 津 沪 渝 蒙 新 藏 宁 桂 港 澳 黑 吉 辽 晋 冀 青 鲁 豫 苏 皖 浙 闽 赣 湘 鄂 粤 琼 甘 陕 贵 川 云";
        $dictArr = explode(" ", $dictstr);
        foreach ($dictArr as $v) {
            if ($v == $input[0])
                return true;
        }
        return false;
    }

    static function isLawSearch($input)
    {
        $lawSearch = new LawSearcher();
        if ($lawSearch->translateTableName($input[0])) {
            return true;
        } else
            return false;
    }

    static function isCodeSearch($input)
    {
        if (self::isCarSearch($input) || self::isLawSearch($input)) {
            return false;
        } 
        else
            return true;
    }
    
    
    static function isCookie($input){
        $pattern = '/JSESSIONID=/u';
        preg_match($pattern, $input, $match);
        if (!empty($match[0])){
            return true;
        }
        else 
            return false;
        
    }
}

