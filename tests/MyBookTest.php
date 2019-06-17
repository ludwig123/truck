<?php

use app\index\model\CodeSearcher;
use app\index\model\MyBook;

/**
 * Book test case.
 */
class MyBookTest extends PHPUnit_Framework_TestCase
{
    private $myBook;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        

    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->myBook = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests Book->__construct()
     */
    public function test__construct()
    {
    }

    /**
     * Tests Book->replyShort()
     */
    public function testReplyShort()
    {
        
        $words = ['驾驶'];
        $codeSearcher = new CodeSearcher();
        $result = $codeSearcher->search($words);
        $this->myBook = new MyBook($result);
        $book = $this->myBook->replyDetail($result);
        $this->assertContains($words[0], $book[0]);
        $this->assertEquals(15, count($book));
        
        $words = ['11110'];
        $result = $codeSearcher->search($words);
        $this->myBook = new MyBook($result);
        $book = $this->myBook->replyDetail($result);
        $this->assertContains($words[0], $book[0]);
        $this->assertEquals(5, count($book));
        
        $words = ['111111'];
        $result = $codeSearcher->search($words);
        $this->myBook = new MyBook($result);
        $book = $this->myBook->replyDetail($result);
        $this->assertContains('11110', $book[0]);
        $this->assertEquals(5, count($book));
        
        $words = ['111'];
        $result = $codeSearcher->search($words);
        $this->myBook = new MyBook($result);
        $book = $this->myBook->replyDetail($result);
        $this->assertContains($words[0], $book[0]);
        $this->assertContains("4条记录", $book[0]);
        $this->assertEquals(1, count($book));
        
        $words = ['200元','3分'];
        $result = $codeSearcher->search($words);
        $this->myBook = new MyBook($result);
        $book = $this->myBook->replyDetail($result);
        $this->assertContains("25条记录", $book[0]);
        $this->assertEquals(3, count($book));

    }


    public function testGetLaws()
    {

    }
}

