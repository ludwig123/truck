<?php
// mb_internal_encoding("UTF-8");

$url = 'http://localhost/suanlajizhen2/public/index.php/index/wechat/index';
// test_help($url );
test_code_search($url);
 test_law($url);
 //test_next_page($url);
// test_next_page_search_in_result();


echo gmstrftime("%Y-%m-%d %H:%M:%S 星期%u", 1525347972+8*3600);
 
function request_data($url, $input){
	$watchStart = microtime(TRUE);
	$ctime = time ();
$request = '<xml>
<ToUserName><![CDATA[gh_3236bf0536d3]]></ToUserName>
<FromUserName><![CDATA[oG24uwN10qZXaFm9KZLdeRj2n22j4]]></FromUserName>
<CreateTime>' . $ctime . '</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[' . $input . ']]></Content>
<MsgId>1234567890123456</MsgId>
</xml>';
	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
$data = curl_exec($ch);
curl_close($ch);


$timeCost = microtime(TRUE) -$watchStart;
if ($data != null){
    var_dump($data);
	preg_match("/<Content><!\[CDATA\[[\s\S]*?]]><\/Content>/", $data,$match1);
	preg_match('/A\[[\s\S]*?]/', $match1[0],$match2);
	return "$timeCost" .'<br>'. "$match2[0]";
}
else return '服务器未响应'.$timeCost;
}

function test_help($url){
	$input = array('帮助','1','2','3','4');
	
	foreach ($input as $v){
		$result = request_data($url, $v);
		//preg_replace('/\\n/', '<br>', $result);
		//var_dump($result);
		echo $v.'：'."<html><head></head><body>".$result."</bode>";
		echo "<br><br>";
	}
}

function test_code_search($url){
	$input =array('11101','10050','2','3','4','12分号牌200元','放大号牌','2','3');
	
	foreach ($input as $v){
		$result = request_data($url, $v);
		echo $v.'：'."<html><head></head><body>".$result."</bode>";
		echo "<br><br>";
	}
}

function test_law($url){
	$input =array('法45条','条例第5条','校车第1条',);
	
	foreach ($input as $v){
		$result = request_data($url, $v);
		//preg_replace('/\\n/', '<br>', $result);
		//var_dump($result);
		echo $v.'：'."<html><head></head><body>".$result."</bode>";
		echo "<br><br>";
	}
}

function test_next_page($url){
	$input =array('号牌','2','1','3','11','4','15','1','10');

	foreach ($input as $v){
		$result = request_data($url, $v);
		//preg_replace('/\\n/', '<br>', $result);
		//var_dump($result);
		echo $v.'：'."<html><head></head><body>".$result."</bode>";
		echo "<br><br>";
	}
}


function test_next_page_search_in_result($url){
	$input =array('号牌','2','?规定安装',"规定","？安装 号牌");

	foreach ($input as $v){
		$result = request_data($url, $v);
		//preg_replace('/\\n/', '<br>', $result);
		//var_dump($result);
		echo $v.'：'."<html><head></head><body>".$result."</bode>";
		echo "<br><br>";
	}
}