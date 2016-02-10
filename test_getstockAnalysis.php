<meta charset=utf-8>
<?php
	/*独立检查项*/
	$input = "分析600151"; //假设这是用户输入的内容
	if (substr($input, 0, 6) == "分析") //判断用户输入的内容前两个字符是否是“分析”这个关键字，如果是则继续执行后面的内容，不是跳出for
	{
        $stockcode = substr($input, 6, 6); //用字符串截取函数substr对变量$input从第6个字符（第二个参数）开始截取，截取长度为6个字符（这个6是函数第三个参数）的字符串，并将字符串保存到变量$stockcode中
        print_r(getStockAnalysis($stockcode)); //用函数print_r输出数组（带键和键值）
	}

	/*附加检查项*/
	//echo substr($input, 0, 6)."xxxx"; //用来显示前两个字符是否是“分析”这个关键字
	//echo substr($input, 6, 6); //用来从关键字结束之后的六位字符是否是数字
	$contentStr = getStockAnalysis($stockcode);
	echo $contentStr[0];
	function getStockAnalysis($stockcode) //传入数字代码（有可能是股票的六位代码），返回一个数组变量$resultArray，有两个字符串元素，第一个元素是股票名称和对应股票代码，第二个元素是股票分析
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
                $resultArray = array($stocktitle, '【基本面】'."\n".$fundamentals."\n".'【技术面】'."\n".$technical."\n".'【机构认同】'."\n".$institution); //字符串结果拼
                //echo $resultArray; //检查输出结果是否正确
                $html_analysis->clear(); //清理文档对象，释放资源
            }
        }
        catch (Exception $e)
        {
        }
        return $resultArray; //返回变量，跳出函数
    }
?>