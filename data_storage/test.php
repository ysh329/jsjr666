<meta charset="UTF-8">
<?php

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
			'399006' => 'sz399006', //格式是：sh或sz+股票代码。特例是股票代码为999999代表的是sh000001，上证指数
			'600664' => 'sh600664',
			'601318' => 'sh601318',
			'000423' => 'sz000423',
			'000538' => 'sz000538'			
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

		return $stockArray;
	}


	// 通过getStockInfo函数获取股票信息，并存储于数组StockInfo数组中

	// 连主库
	$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);	
	if($link)
	{
		mysql_select_db(SAE_MYSQL_DB, $link);			
		$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
		
		echo "Link successfully!";
		//sz000538
		$stockcode = "000538";//print_r(getStockInfo($stockcode));
		$StockInfo = getStockInfo($stockcode);
		$sql = "INSERT INTO `app_jsjr666`.`sz000538` (`str01`, `str02`, `str03`, `str04`, `str05`, `str06`, `str07`, `str08`, `str09`, `str10`, `str11`, `str12`, `str13`, `str14`, `str15`, `str16`, `str17`, `str18`, `str19`, `str20`, `str21`, `str22`, `str23`, `str24`, `str25`, `str26`, `str27`, `str28`, `str29`, `str30`, `str31`, `str32`, `str33`) VALUES ('$StockInfo[0]', '$StockInfo[1]', '$StockInfo[2]', '$StockInfo[3]', '$StockInfo[4]', '$StockInfo[5]', '$StockInfo[6]', '$StockInfo[7]', '$StockInfo[8]', '$StockInfo[9]', '$StockInfo[10]', '$StockInfo[11]', '$StockInfo[12]', '$StockInfo[13]', '$StockInfo[14]', '$StockInfo[15]', '$StockInfo[16]', '$StockInfo[17]', '$StockInfo[18]', '$StockInfo[19]', '$StockInfo[20]', '$StockInfo[21]', '$StockInfo[22]', '$StockInfo[23]', '$StockInfo[24]', '$StockInfo[25]', '$StockInfo[26]', '$StockInfo[27]', '$StockInfo[28]', '$StockInfo[29]', '$StockInfo[30]', '$StockInfo[31]', '$StockInfo[32]')";
		$query = mysql_query($sql);//执行sql语句
		echo $StockInfo[0];
		
		//sz000423
		$stockcode = "000423";//print_r(getStockInfo($stockcode));
		$StockInfo = getStockInfo($stockcode);		
		$sql = "INSERT INTO `app_jsjr666`.`sz000423` (`str01`, `str02`, `str03`, `str04`, `str05`, `str06`, `str07`, `str08`, `str09`, `str10`, `str11`, `str12`, `str13`, `str14`, `str15`, `str16`, `str17`, `str18`, `str19`, `str20`, `str21`, `str22`, `str23`, `str24`, `str25`, `str26`, `str27`, `str28`, `str29`, `str30`, `str31`, `str32`, `str33`) VALUES ('$StockInfo[0]', '$StockInfo[1]', '$StockInfo[2]', '$StockInfo[3]', '$StockInfo[4]', '$StockInfo[5]', '$StockInfo[6]', '$StockInfo[7]', '$StockInfo[8]', '$StockInfo[9]', '$StockInfo[10]', '$StockInfo[11]', '$StockInfo[12]', '$StockInfo[13]', '$StockInfo[14]', '$StockInfo[15]', '$StockInfo[16]', '$StockInfo[17]', '$StockInfo[18]', '$StockInfo[19]', '$StockInfo[20]', '$StockInfo[21]', '$StockInfo[22]', '$StockInfo[23]', '$StockInfo[24]', '$StockInfo[25]', '$StockInfo[26]', '$StockInfo[27]', '$StockInfo[28]', '$StockInfo[29]', '$StockInfo[30]', '$StockInfo[31]', '$StockInfo[32]')";
		$query = mysql_query($sql);//执行sql语句
		echo $StockInfo[0];
		
		//sh600664
		$stockcode = "600664";//print_r(getStockInfo($stockcode));
		$StockInfo = getStockInfo($stockcode);			
		$sql = "INSERT INTO `app_jsjr666`.`sh600664` (`str01`, `str02`, `str03`, `str04`, `str05`, `str06`, `str07`, `str08`, `str09`, `str10`, `str11`, `str12`, `str13`, `str14`, `str15`, `str16`, `str17`, `str18`, `str19`, `str20`, `str21`, `str22`, `str23`, `str24`, `str25`, `str26`, `str27`, `str28`, `str29`, `str30`, `str31`, `str32`, `str33`) VALUES ('$StockInfo[0]', '$StockInfo[1]', '$StockInfo[2]', '$StockInfo[3]', '$StockInfo[4]', '$StockInfo[5]', '$StockInfo[6]', '$StockInfo[7]', '$StockInfo[8]', '$StockInfo[9]', '$StockInfo[10]', '$StockInfo[11]', '$StockInfo[12]', '$StockInfo[13]', '$StockInfo[14]', '$StockInfo[15]', '$StockInfo[16]', '$StockInfo[17]', '$StockInfo[18]', '$StockInfo[19]', '$StockInfo[20]', '$StockInfo[21]', '$StockInfo[22]', '$StockInfo[23]', '$StockInfo[24]', '$StockInfo[25]', '$StockInfo[26]', '$StockInfo[27]', '$StockInfo[28]', '$StockInfo[29]', '$StockInfo[30]', '$StockInfo[31]', '$StockInfo[32]')";
		$query = mysql_query($sql);//执行sql语句
		echo $StockInfo[0];
		
		//sh601318
		$stockcode = "601318";//print_r(getStockInfo($stockcode));
		$StockInfo = getStockInfo($stockcode);			
		$sql = "INSERT INTO `app_jsjr666`.`sh601318` (`str01`, `str02`, `str03`, `str04`, `str05`, `str06`, `str07`, `str08`, `str09`, `str10`, `str11`, `str12`, `str13`, `str14`, `str15`, `str16`, `str17`, `str18`, `str19`, `str20`, `str21`, `str22`, `str23`, `str24`, `str25`, `str26`, `str27`, `str28`, `str29`, `str30`, `str31`, `str32`, `str33`) VALUES ('$StockInfo[0]', '$StockInfo[1]', '$StockInfo[2]', '$StockInfo[3]', '$StockInfo[4]', '$StockInfo[5]', '$StockInfo[6]', '$StockInfo[7]', '$StockInfo[8]', '$StockInfo[9]', '$StockInfo[10]', '$StockInfo[11]', '$StockInfo[12]', '$StockInfo[13]', '$StockInfo[14]', '$StockInfo[15]', '$StockInfo[16]', '$StockInfo[17]', '$StockInfo[18]', '$StockInfo[19]', '$StockInfo[20]', '$StockInfo[21]', '$StockInfo[22]', '$StockInfo[23]', '$StockInfo[24]', '$StockInfo[25]', '$StockInfo[26]', '$StockInfo[27]', '$StockInfo[28]', '$StockInfo[29]', '$StockInfo[30]', '$StockInfo[31]', '$StockInfo[32]')";
		$query = mysql_query($sql);//执行sql语句
		echo $StockInfo[0];
		
		//$num_all = $num_all[0];
		//echo $num_all;	
	}
	
	mysql_close($link);		
?>