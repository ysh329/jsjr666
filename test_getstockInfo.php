<meta charset=utf-8>
<?php
	/*独立检查项*/
	$input = "股票600151"; //假设这是用户输入的内容
	if (substr($input, 0, 6) == "股票") //判断用户输入的内容前两个字符是否是股票这个关键字，如果是则继续执行后面的内容，不是跳出for
	{
        $stockcode = substr($input, 6, 6); //用字符串截取函数substr对变量$input从第6个字符（第二个参数）开始截取，截取长度为6个字符（这个6是函数第三个参数）的字符串，并将字符串保存到变量$stockcode中
        print_r(getStockInfo($stockcode)); //用函数print_r输出数组（带键和键值）
	}

	/*附加检查项*/
	//echo substr($input, 0, 6)."xxxx"; //用来显示前两个字符是否是“股票”这个关键字
	//echo substr($input, 6, 6); //用来从关键字结束之后的六位字符是否是数字

	function getStockInfo($stockcode) //函数getStockInfo，将用户输入的股票代码借助sina股票开放的数据接口，返回股票基本信息数组$resultArray，两个有效字符串元素（键值分别为“Title”和“Description”）
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
					 "总手：".
					 	((substr($stockcode, 0, 1) != 3)?/*sh/sz用3区分*/
					 		(array_key_exists($stockcode, $stockIndex)?round(($stockArray[8]/100000000), 3)."亿":round(($stockArray[8]/1000000), 3)."万")
					 		:(array_key_exists($stockcode, $stockIndex)?round(($stockArray[8]/100000000), 3)."亿":round(($stockArray[8]/1000000), 3)."万"))
					 		."\n".
					 "金额：".(array_key_exists($stockcode, $stockIndex)?round(($stockArray[9]/100000000), 3)."亿":round(($stockcode[9]/10000), 3)."万")."\n".
					 "更新：".$stockArray[30]." ".$stockArray[31]; //组织信息成字符串变量$stockInfo
		return $resultArray = array(
									"Title" => $stockTitle,
									"Description" => $stockInfo,
									"PicUrl" => "",
            						"Url" => ""); //组织信息成数组变量$resultArray，前两个是有信息的键（元素）
		return $resultArray;
	}
?>