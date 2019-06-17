<?php
namespace app\index\model;
use function foo\func;

const MAXLEN = 1500 ;
class MyBook
{
    private $book;

    public function __construct($cursor)
    {
        $this->book = [];
        $this->book = $this->replyDetail($cursor);
    }
    
    public function goPage($index){
        return $this->book[$index];
    }
    
    public function goCatalog(){
        return $this->book[0];
    }
    
    public function maxPageNumber(){
        return count($this->book);
    }
    
    /**把分页的结果存储在以1开始的array中
     * @param array $result
     * @return mixed
     */
    private function splitPages($result){
        $pages = array(	);
        $i = 1;
        $content = '';
        foreach ( $result as $item => $date ) {
            
            $len = $this->lenCount($content);
            if ($len > MAXLEN){
                $pages[$i]= $content ;
                $i++;
                $content = null;
            }
            
            $content = $content . "代码：" . $date ['违法代码'] . "\n内容：" . $date ['违法内容'] . "\n\n";
            
        }

        //最后一页
        $pages[$i]= $content ;
        
        return $pages;
    }
    
    public function replyDetail($result) {
        $countResult = count($result);
        $content = '';
        foreach ( $result as $item => $date ) {
            foreach ( $date as $k => $v ) {
                if (! is_numeric ( $v ))
                    $v = trim ( $v );
                    if (! empty ( $v )) {
                        $content = $content . $k . '：' . $v . "\n";
                    }
            }
            $content = $content . "\n";
            
            //如果超出MAXLEN字节就返回,进入replyShort
            $len = $this->lenCount($content);
            if ($len > MAXLEN)
                return $this->replyShort($result);
        }
        
        //如果只有一条结果
        if ($countResult == 1){
            $pages = $this->getLawPages($content);
            $content .= "查询到" . $countResult . "条记录\n输入对应数字可查看详细内容：\n"
                . $this->genrateChoice($content) ;
            $book = [$content];
           return array_merge($book, $pages);
            
        }
        else {
            $content = $content . "查询到" . $countResult . "条记录\n" ;
            return [$content];
        }
        
        
    }
    
    /**仅回复代码，违法内容
     * @param array $result 游标
     * @return string
     */
    public function replyShort($result) {
        $i = 0;
        $book = [];
        $content = '';
        foreach ( $result as $item => $date ) {
            $len = $this->lenCount($content);
            if ($len > MAXLEN)
                break;
            
            $content = $content . "代码：" . $date['违法代码'] . "\n内容：" . $date['违法内容'] . "\n\n";
            $i++;
        }
        
        //因为超出maxlen 跳出循环，or 数组循环完毕
        //数组循环完毕
        if ($i == count($result)){
            $content = $content . "查询到" . count ( $result ) . "条记录，请输入代码查看详情\n";
            $book[] = $content;
        }
        //超出一页的长度
        else {
            $pages = $this->splitPages($result);
            $sum = count($pages);
            
            //book[0] 目录页
            $book[] = $pages[1] . "查询到" . count ( $result ) . "条记录\n请输入数字翻页:\n\n第"
                .$this->stringPageNumber(1, $sum)."页";
            
            
            foreach ($pages as $k=>$v){
                $book[] = $v . "查询到" . count ( $result ) . "条记录\n请输入数字翻页:\n\n第"
                    .$this->stringPageNumber($k, $sum)."页";
            }
            
        }
        return $book;
    }
    
    public function getLawPages($content){
        $laws = $this->getLaws($content);
        $tokenizer = new Tokenizer();
        $lawSearcher = new LawSearcher();
        $book = [];
        foreach ($laws as $v){
            $law = $tokenizer->split($v[0]);
            $index = $tokenizer->split($v[1]);
            
            $book[] = $lawSearcher->law($law[0], $index[0]);
        }
        
        return $book;
        
    }
    
    private function genrateChoice($text){
        $temp = $this->getLaws($text);
        $content = '';
        $i = 1;
        foreach ($temp as $t){
            $content = $content . $i.":".$t[0] .$t[1].";\n";
            $i++;
        }
        return $content;
    }
    
    /**输入字符串，返回以数组形式组成的法律和条文。
     * @param string $text
     * @return array array[$i][0] == 《法》 array[$i][1] == 90  （条）
     */
    public function getLaws($text) {
        $result4law = $result = null;
        preg_match_all ( '/《[^《]*条/u', $text, $result4law );
        $law = $result4law [0];
        //	var_dump ( $result4law );
        $i = 0;
        foreach ( $law as $v ) {
            preg_match_all ( '/《\S*?》/u', $v, $k1 );
            $k1 = $k1 [0];
            //		var_dump ( $k1 );
            foreach ($k1 as $k11){
                preg_match_all ( '/\d{1,3}条/u', $v, $k2 );
                $k2 = $k2 [0];
                //			var_dump ( $k2 );
                foreach ( $k2 as $k21 ) {
                    $j = 0;
                    $result [$i][0] = $k11;
                    $result [$i][1] = $k21;
                    $j ++;
                    $i ++;
                }
            }
        }
        return $result;
    }
    
    private function stringPageNumber($now, $sum) {
        $result = '';
        switch ($now) {
            case 1 :
                $char = "①";
                break;
                
            case 2 :
                $char = "②";
                break;
                
            case 3 :
                $char = "③";
                break;
                
            case 4 :
                $char = "④";
                break;
                
            case 5 :
                $char = "⑤";
                break;
                
            case 6 :
                $char = "⑥";
                break;
            case 7 :
                $char = "⑦";
                break;
                
            case 8 :
                $char = "⑧";
                break;
                
            case 9 :
                $char = "⑨";
                break;
                
            case 10 :
                $char = "⑩";
                break;
        }
        for ($i=1 ; $i<= $sum && $i <= 10;$i++){
            if ($i == $now){
                $result .= $char." ";
            }
            else $result .= $i." ";
        }
        return $result;
    }
    
    private function setCatalog($content){
        $this->book['0'] = $content;
    }
    
    private function lenCount($content){
        return strlen($content);
    }
}

