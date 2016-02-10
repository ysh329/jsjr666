<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj -> responseMsg();
class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this -> checkSignature())
		{
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
        
		if (!empty($postStr))
        {
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj -> FromUserName;
                $toUsername = $postObj -> ToUserName;
            	$type = $postObj -> MsgType;
            	$customevent = $postObj -> Event;
			
                $keyword = trim($postObj -> Content);
                $time = time();
                $textTpl = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                <FuncFlag>0</FuncFlag>
							</xml>";
            	switch($type)
                {
                    case "event":
					{
						if ($type == "event" and $customevent == "subscribe")
						{
							$msgType = "text";
							$contentStr = "欢迎关注计算金融与风险管理计算中心。\n【1】查询天气。输入格式：地名+空格+天气，如“北京 天气”，“上海 天气”；\n【2】进入实验室讨论版，请点击这里；\n【3】访问实验室官方网站，获取更多资讯。请点击这里。";
							$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
							echo $resultStr;
									
						 }//没有else
						 break;//case "event" 结束				
					}
                    
                    case "text"://接收到文字信息，关键词和非关键词的自动回复
                    {
                        //关键词设置		开始
                        if(!empty($keyword))
                        {
                            $msgType = "text";
                            //股票行情分析开始
                            if (substr($keyword, 0, 6) == "分析") //判断用户输入的内容前两个字符是否是“分析”这个关键字，如果是则继续执行后面的内容，不是跳出for
                            {
                                //$msgType = "news";
                                $stockcode = substr($keyword, 6, 6); //用字符串截取函数substr对变量$input从第6个字符（第二个参数）开始截取，截取长度为6个字符（这个6是函数第三个参数）的字符串，并将字符串保存到变量$stockcode中
                                //$stockInfo = $this->getStockAnalysis($stockcode);
                                //$contentStr = $stockInfo[0].'\n'.$stockInfo[1];    
                                $contentStr = $this->getStockAnalysis($stockcode);
                                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                echo $resultStr;
                            }
                            //股票基本信息开始
                            if (substr($keyword, 0, 6) == "股票") //判断用户输入的内容前两个字符是否是“股票”这个关键字，如果是则继续执行后面的内容，不是跳出for
                            {
                                $stockcode = substr($keyword, 6, 6); //用字符串截取函数substr对变量$input从第6个字符（第二个参数）开始截取，截取长度为6个字符（这个6是函数第三个参数）的字符串，并将字符串保存到变量$stockcode中
                                $contentStr = $this->getStockInfo($stockcode);
                                $msgType = "news";
                                $newsTpl = "<xml>
													<ToUserName><![CDATA[%s]]></ToUserName>
													<FromUserName><![CDATA[%s]]></FromUserName>
													<CreateTime>%s</CreateTime>
													<MsgType><![CDATA[news]]></MsgType>
													<ArticleCount>2</ArticleCount>
													<Articles>
														<item>
															<Title><![CDATA[".$contentStr[Title]."]]></Title>
														</item>
														<item>
															<Title><![CDATA[".$contentStr[Description]."]]></Title> 
														</item> 														
													</Articles>
												</xml>";                               
                                $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time);
                                echo $resultStr;
                            }                            
                            //天气	查询		开始
							$keywordsectionArray = explode(" ", $keyword);
                            if ($keywordsectionArray[1] == "天气")
                            {
                                $url = "http://api.map.baidu.com/telematics/v2/weather?location={$keywordsectionArray[0]}&ak=6eeplpjtjf18kHHAKj3ckm8z";
                                $fa = file_get_contents($url);
                                $fa = simplexml_load_string($fa);
                                $city = $fa -> currentCity;
                                $da1 = $fa -> results -> result[0] -> date;
                                $da2 = $fa -> results -> result[1] -> date;
                                $da3 = $fa -> results -> result[2] -> date;
                                
                                $w1 = $fa -> results -> result[0] -> weather;
                                $w2 = $fa -> results -> result[1] -> weather;
                                $w3 = $fa -> results -> result[2] -> weather;
                            
                                $p1 = $fa -> results -> result[0] -> wind;
                                $p2 = $fa -> results -> result[1] -> wind;
                                $p3 = $fa -> results -> result[2] -> wind;
                            
                                $q1 = $fa -> results -> result[0] -> temperature;
                                $q2 = $fa -> results -> result[1] -> temperature;
                                $q3 = $fa -> results -> result[2] -> temperature;
                            
                                $picurl1 = $fa -> results -> result[0] -> dayPictureUrl;
                                $picurl2 = $fa -> results -> result[1] -> dayPictureUrl;
                                $picurl3 = $fa -> results -> result[2] -> dayPictureUrl;
                            
                                $d1 = "【".$city."今日"."天气】\n".$da1.$w1.$p1.$q1."\n";
                                $d2 = $da2.$w2.$p2.$q2."\n";
                                $d3 = $da3.$w3.$p3.$q3;
                                $contentStr = $d1.$d2.$d3;
								$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                echo $resultStr;
                            }
                            //天气 查询 结束
                            else
                            {//关键词 开始
                                switch($keyword)
                                { 
									case "纳新":
                                    case "纳新时间":
                                    case "1":
                                    {
										$msgType = "text";                                                                                
                                        $contentStr = "【纳新时间】\n通常为每学年上学期的9或10月份，下学期的5或6月份。\n具体时间：\n①请持续关注本官方平台；\n②登录我们的官方网站".'<a href="http://222.24.19.28/wap/">点这里</a>'."；\n③注意观察实验楼和教学楼的纳新海报通知；"."\n④加入".'<a href="http://qm.qq.com/cgi-bin/qm/qr?k=-Rps7yMM5yaeIPbdTSWZDvAl59IP4574">纳新咨询QQ群</a>。';
										$extra = "\n注：返回【主菜单】，请回复11。";
										$contentStr = $contentStr.$extra;										
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                         
                                        break;
                                    }
                                    
                                    case "实验室概况":
                                    case "2":
                                    {
										$msgType = "text";                                        
                                        $contentStr = "【实验室地点】\n位于西区图书馆五层南面。\n【实验室方向】\n①金融分析\n②软件开发\n③数学建模"; 
										$extra = "\n注：返回【主菜单】，请回复11。";
										$contentStr = $contentStr.$extra;										
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                         
                                    	break;
                                    }

                                    case "实验室网站":
                                    case "3":
                                    {
										$msgType = "text";                                                                                
                                        $contentStr = '访问实验室网站<a href="http://222.24.19.28/wap/">点击进入</a>。';      
										$extra = "\n注：返回【主菜单】，请回复11。";
										$contentStr = $contentStr.$extra;										
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                         
                                    	break;                                        
                                    }
                                    
                                    case "查天气":
                                    case "查询天气":
                                    case "天气":
                                    case "4":
                                    {
                                        $msgType = "text";
                                        $contentStr = "查询天气。输入格式：地名+空格+天气，例如“北京 天气”，“长安 天气”。";
										$extra = "\n注：返回【主菜单】，请回复11。";
										$contentStr = $contentStr.$extra;										
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                         
                                        break;
                                    }
                                    
                                    case "查股票信息":
                                    case "股票信息":
                                    case "信息":
                                    case "5":
                                    {
                                        $msgType = "text";
                                        $contentStr = "查询股票信息。发送股票加上6位股票数字代码，例如“股票000063”。";
										$extra = "\n注：返回【主菜单】，请回复11。";
										$contentStr = $contentStr.$extra;										
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                         
                                        break;
                                    }

                                    case "查股票行情":
                                    case "股票行情":
                                    case "行情分析":
                                    case "行情":
                                    case "6":
                                    {
                                        $msgType = "text";
                                        $contentStr = "查询股票行情分析。发送分析加上6位股票数字代码，例如“分析000063”。";
										$extra = "\n注：返回【主菜单】，请回复11。";
										$contentStr = $contentStr.$extra;										
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                         
                                        break;
                                    }
                                    
                                    case "wifi":
                                    case "WIFI":
                                    case "Wifi":
                                    case "路由器密码":
                                    case "密码":
                                    case "无线":
                                    {
                                        $msgType = "text";
                                        $contentStr = "【实验室免费开放WIFI】[愉快]\n名字:ComputationFinance\n密码:jsjr-xupt或xupt-jsjr或jsjr2013。";
										$extra = "\n注：返回【主菜单】，请回复11。";
										$contentStr = $contentStr.$extra;										
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                         
                                    	break;                                           
                                    }

                                    default://非关键字 自动回复
                                    {
										$msgType = "text";                                                                                
                                        $contentStr = '主菜单 | 请直接回复数字'."\n".'【1】纳新详情<a href="http://1.jsjr666.sinaapp.com/signup/index.php">现在报名</a>；'."\n".'【2】实验室概况；'."\n".'【3】访问实验室网站<a href="http://222.24.19.28/wap/">进入</a>；'."\n".'【4】查询天气。输入格式：地名+空格+天气，例如“北京 天气”，“长安 天气”；'."\n".'【5】查询股票信息。发送股票加上6位股票数字代码，例如“股票000063”；'."\n".'【6】查询股票行情分析。发送分析加上6位股票数字代码，例如“分析000063”。';
                                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                        echo $resultStr;                                          
                                    }   
                                
                             	}//关键词 结束
                       		}
                        }
                        else
						{
                            echo "Input something...";
						}
						break;//case "text" 结束						
                    }				
					default:
					{	
                        
					}
				}//switch($type) 结束
            }
        else //keyword为空
        {
        	echo "";
        	exit;
        }
    }
    
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
    
	private function getStockInfo($stockcode) //函数getStockInfo，将用户输入的股票代码借助sina股票开放的数据接口，返回股票基本信息数组$resultArray，两个有效字符串元素（键值分别为“Title”和“Description”）
	{
		//通过正则检查用户输入的是否是六位数字
		if(!preg_match("/^\d{6}$/", $stockcode)) //使用preg_match函数进行用户输入的股票代码进行正则匹配，过滤出股票代码的六位数字
		{
			return "发送股票加上6位股票数字代码，例如“股票000063”。"; //不符合正则的过滤的要求（在关键字股票后面，用户输入的不是六位数字），返回提示，并跳出函数
		}
		//建立股票代码和对应在新浪股票数据接口所使用代码的索引。其实就可以想象成是一个数据库
		$stockIndex = array( //关联数组，用户输入的对应数组里的键（左边的，相当于数组下标），根据用户输入的键，在关联数组索引出对应新浪股票数据接口对应的股票代码。
			'999999' => 'sh000001', //这里可以想象成是一种映射，用户输入的代码映射到新浪存储的索引值
			'399001' => 'sz399001', //这里也可建立数据库，将这些股票代码对应新浪股票的代码
			'000300' => 'sh000300', //我想我这里还是算了，如果有新的股票出来，旧的股票消失。还需要修改数据库。有些麻烦。
			'399005' => 'sz399005', //在后面我们会通过Curl反馈看，是否用户的股票代码是有的，所以可以不用太纠结这里
			'399006' => 'sz399006'  //格式是：sh或sz+股票代码。特例是股票代码为999999代表的是sh000001，上证指数
		);
		//查询用户输入的股票代码是否在上面建立的索引中
		if(array_key_exists($stockcode, $stockIndex)) //判断用户输入的股票代码$stockcode是否在刚刚建立的索引$stockIndex里面
		{//用户输入的股票代码$stockcode【在】索引$stockIndex里
			$url = "http://hq.sinajs.cn/list=".$stockIndex[$stockcode]; //根据新浪提供的股票数据开放接口，得到对应数据的地址。格式是：sh或sz+股票代码
		}
		else 
		{//用户输入的股票代码$stockcode【不】在索引$stockIndex里
			$exchange = (substr($stockcode, 0, 1) < 5)?"sz":"sh"; //用substr函数取出变量$stockcode开始第1个位置的字符，若小于5，则赋值字符串”sz“给$exchange，否则赋值字符串"sh"给$exchange
			$url = "http://hq.sinajs.cn/list=".$exchange.$stockcode; //根据新浪提供的股票数据开放接口，得到对应数据的地址。格式是：sh或sz+股票代码
		}
		//使用cURL（此函数可以用来模仿用户的一些行为）通过会话进行数据获取
		$ch = curl_init(); //初始化一个cURL会话
		curl_setopt($ch, CURLOPT_URL, $url); //设置一个cURL传输选项，第二个参数是你想要的设置，第三个参数$url是这个设置给定的值
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设置一个cURL传输选项
		$data = curl_exec($ch); //执行一个cURL会话
		$result = iconv("GBK", "UTF-8//IGNORE", $data); //iconv函数库能够完成各种字符集间的转换。这里是将GBK数据编码的$data转换成UTF-8字符编码。同时忽略转换过程中的错误
		
		$start = strpos($result, '"'); //返回的结果中，信息【开始】出现的位置处是符号“
		$last = strripos($result, '"'); //返回的结果中，信息【最后】出现的位置处是符号“
		$stockStr = substr($result, $start+1, $last-$start-1); //substr(string必须,start必须,length可选)函数返回字符串的一部分，若lengthWie负数则 从字符串末端返回
		$stockArray = explode(",", $stockStr); //将变量$stockStr依据里面的英文逗号为间隔生成数组
		
		if(count($stockArray) <> 33){ return "不存在此股票代码。"; } //计算数组变量$stockArray元素的个数，如果是33个元素那么说明抓取正确，即股票正确，否则用户输入股票代码有误，退出
		$stockTitle = $stockArray[0]."[".$stockcode."]"; //将包含“股票标题”以及“股票代码”的字符串存入变量$stockTitle中
		$stockInfo = "最新：".$stockArray[3]."\n".
					 "涨跌：".round($stockArray[3] - $stockArray[2], 3)."\n".
					 "涨幅：".round(($stockArray[3] - $stockArray[2])/$stockArray[2]*100, 3)."%%\n".
					 "今开：".$stockArray[1]."\n".
					 "昨收：".$stockArray[2]."\n".
					 "最高：".$stockArray[4]."\n".
					 "最低：".$stockArray[5]."\n".
					 "成交量：".
					 	((substr($stockcode, 0, 1) != 3)?/*sh/sz用3区分*/
					 		(array_key_exists($stockcode, $stockIndex)?round(($stockArray[8]/100000000), 3)."亿":round(($stockArray[8]/1000000), 3)."万")
					 		:(array_key_exists($stockcode, $stockIndex)?round(($stockArray[8]/100000000), 3)."亿":round(($stockArray[8]/1000000), 3)."万"))
					 		."\n".
					 //"金额：".(array_key_exists($stockcode, $stockIndex)?round(($stockArray[9]/100000000), 3)."亿":round(($stockcode[9]/10000), 3)."万")."\n".
					 "更新：".$stockArray[30]." ".$stockArray[31]; //组织信息成字符串变量$stockInfo
		return $resultArray = array(
									"Title" => $stockTitle,
									"Description" => $stockInfo,
									"PicUrl" => "",
            						"Url" => ""); //组织信息成数组变量$resultArray，前两个是有信息的键（元素）
		//return $resultArray;
		return $stockInfo;
	}   
    
    
	private function getStockAnalysis($stockcode) //传入数字代码（有可能是股票的六位代码），返回一个数组变量$resultArray，有两个字符串元素，第一个元素是股票名称和对应股票代码，第二个元素是股票分析
    {
        //通过正则检查用户输入的是否是六位数字
        if(!preg_match("/^\d{6}$/", $stockcode)) //使用preg_match函数进行用户输入的股票代码进行正则匹配，过滤出股票代码的六位数字
        {
            return "发送分析加上6位股票数字代码，例如“分析000063”。"; //不符合正则的过滤的要求（在关键字股票后面，用户输入的不是六位数字），返回提示，并跳出函数
        }
        
        include_once 'simple_html_dom.php';//在脚本执行期间包含并运行指定文件。如果该文件中的代码已经被包含了，则不会再次包含。这是与include()的唯一区别
        try 
        {
            $url = "http://m.ghzq.cn/weixin/index.aspx?code=".$stockcode; //拼上需要的用户查询的股票代码
            $html_analysis = file_get_html($url); //根据变量$url创建DOM对象。DOM—Document Object Model,它是W3C国际组织的一套Web标准。它定义了访问HTML文档对象的一套属性、方法和事件。 DOM是以层次结构组织的节点或信息片断的集合。更多请百度
            if (!isset($html_analysis)) //函数isset检查变量$html_analysis是否设置
            {
                $html_analysis->clear(); //变量没有设置，也就是说，尽管用户给的是6位数，但$stockcode没有这个对应的股票代码。清理文档对象，释放资源
                $resultArray = "输入的股票代码有误，请重试！"; //给出提示，其实也可以和下一句合并写。return "输入的股票代码有误，请重试！";
                return $resultArray; //返回变量，跳出函数
            }
            else //函数检查出来变量已经设置，就是说用户给的六位数字的代码是存在的
            {
                $stock = $html_analysis->find('div[class="row_first"]div', 0)->plaintext; // 使用函数find找出网页上股票的标题信息，其内容在div class="row_first"下的第一个div，所以在find的参数是0，plaintext是固定参数，更多请查看手册
                $endpos = strpos($stock, '）'); //因为抓到的结果是全部文字内容，我们只需要股票标题和对应代码，以中文右括号“）”结尾，因此用strpos函数对字符串变量查找“）”字符所在的位置，并返回其位置数字
                //echo $endpos; //检查输出结果是否正确，显示“）”出现的位置序号
                $stocktitle = substr($stock, 0, $endpos+3);
                //echo substr($stock, 0, $endpos+3); //检查输出结果是否正确，字符串截取函数substr对变量$stock进行从0位置（即开始位置）处截取长度为$endpos+3的字符串，加3是因为要算上最后一个中文字符
                //基本面
                $fundamentals = $html_analysis->find('div[class="font"]', 0) -> plaintext; //对对象$html_analysis使用类内函数find，找到所在网页的地方，并使用参数plaintext抓纯文本内容，还有外文本，内文本这样的参数。0代表第一个class为div的内容
                //技术面
                $technical = $html_analysis->find('div[class="font"]', 1) -> plaintext; //同上一个注释，这里1代表：抓取第二个class=font的div的内容
                //机构认同
                $institution = $html_analysis->find('div[class="font"]', 2) -> plaintext; //同上一个注释，这里1代表：抓取第三个class=font的div的内容
                //【四部分】信息整合
                $resultString = trim($stocktitle."\n".'【基本面】'."\n".$fundamentals."\n".'【技术面】'."\n".$technical."\n".'【机构认同】'."\n".$institution); //字符串结果拼
                //echo $resultArray; //检查输出结果是否正确
                $html_analysis->clear(); //清理文档对象，释放资源
            }
        }
        catch (Exception $e)
        {
        }
        return $resultString; //返回变量，跳出函数
		//return $stocktitle[0].'\n'.$stocktitle[1];
		//return $fundamentals;
    }

    
}
?>
