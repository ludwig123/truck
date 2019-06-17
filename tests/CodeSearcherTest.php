<?php
namespace tests;


use PHPUnit\Framework\TestCase;
use app\index\model\CodeSearcher;
use app\index\model\Tokenizer;

class CodeSearcherTest extends TestCase
{

    private $codeSearcher;


    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated SearchEngineTest::setUp()
        
        $this->codeSearcher = new CodeSearcher(/* parameters */);
    }

    protected function tearDown()
    {
        // TODO Auto-generated SearchEngineTest::tearDown()
        $this->codeSearcher = null;
        
        parent::tearDown();
    }


    public function __construct()
    {
        // TODO Auto-generated constructor
    }
    
    public function testSearch(){
        $tokenizer = new Tokenizer();
        
        $inputs = "11110驾驶证";
        $words = $tokenizer->split($inputs);
        $result = $this->codeSearcher->search($words);
        
    }
    
    public function testCreateMap(){
        $tokenizer = new Tokenizer();
        
        $input = "11110";
        $words = $tokenizer->split($input);
        $map = $this->codeSearcher->createMap($words);
        $this->assertContains('违法代码', $map[0][0]);
        $this->assertEquals('%11110%', $map[0][2]);
        
        $input = "100元3分11110驾驶证";
        $words = $tokenizer->split($input);
        $map = $this->codeSearcher->createMap($words);

        $this->assertContains('罚款金额', $map[0][0]);
        $this->assertEquals('100', $map[0][2]);
        $this->assertContains('违法记分', $map[1][0]);
        $this->assertEquals('3', $map[1][2]);
        $this->assertContains('违法代码', $map[2][0]);
        $this->assertEquals('%11110%', $map[2][2]);
        $this->assertContains('违法内容', $map[3][0]);
        $this->assertEquals('%驾驶证%', $map[3][2]);
        
        
        $input = "11110100元";
        $words = $tokenizer->split($input);
        $map = $this->codeSearcher->createMap($words);
        $this->assertContains('违法代码', $map[0][0]);
        
        $input = "100元驾驶证12个";
        $words = $tokenizer->split($input);
        $map = $this->codeSearcher->createMap($words);
        $this->assertContains('罚款金额', $map[0][0]);
        $this->assertContains('违法内容', $map[1][0]);
    }

    public function testConnectBusFind(){
        $chePai = "川C19485";
        $result = $this->codeSearcher->connectBus($chePai);
        $this->assertContains($chePai, $result);
    }
    
    public function testConnecBusNotFind(){
        $chePai = "湘D99999";
        $result = $this->codeSearcher->connectBus($chePai);
        $this->assertContains("未查询到接驳信息", $result);
    }
    
    public function testCodeSearch(){
        $code = '11110';
        $result = $this->codeSearcher->codeSearch($code);
        //返回的11110 是 float 格式！
        $this->assertEquals($code, $result[0]['违法代码']);
        
        $code = '11111';
        $result = $this->codeSearcher->codeSearch($code);
        $this->assertEquals('11110', $result[0]['违法代码'], "应该返回11110的结果");
        
        $code = '10069';
        $result = $this->codeSearcher->codeSearch($code);
        $this->assertEquals('10060', $result[0]['违法代码'], "应该返回10060的结果");
        $this->assertEquals(3, count($result));
    }
    
    public function testTextSearch(){
        $text = '放大号牌';
        $result = $this->codeSearcher->search($text);
        var_dump($result);
//         $this->assertEquals('11110', $result[0]['违法代码'], "应该返回11110的结果");
    }
    
}

