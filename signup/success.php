<?php
	error_reporting(0);//关闭错误报告
?>
<html>
	<head>
        <meta charset = "UTF-8">
        <!--<meta name = "viewport" content = "width=device-width,user-scalable = no">-->
        <meta name = "viewport" content = "width=device-width, minimum-scale=1.0, maximum-scale=2.0">
        
        <!-- 禁止用户缩放 开始 -->
        <meta content = "width=device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable = 0;" name = "viewport" />
        <!-- 禁止用户缩放 结束 -->
        
		<title>报名成功</title>
		<link href = "css/index_style.css" rel = "stylesheet" type = "text/css" />
        
        <!-- 禁止页面刷新 开始 -->
        <script type = "text/javascript">
        document.onkeydown = function()   
        {   
          	if(event.keyCode==116)   
            {   
          		event.keyCode=0;   
          		event.returnValue = false;   
          	}   
        }   
        document.oncontextmenu = function()
        {
            event.returnValue = false;
        }   
        </script>
        <!-- 禁止页面刷新 结束 -->
        
	</head>
	<body oncontextmenu=self.event.returnValue=false>
        
    <center>
	<!-- 报名成功 内容 开始-->
		<font face = "微软雅黑" color = "white">
            <font size = "5">干得漂亮！：)</font><br />
			<?php echo $_POST["class"]; ?>班的<?php echo $_POST["name"]; ?>同学，<br />
            <font size = "5">你已经报名成功！</font><br /><br />
			请持续关注<br />
			①微信公众平台<br />
            微信号：cfrmrc2013<br />
            ②<a href = "http://222.24.19.28/wap/">官方网站</a><img src = "images/pointer.png"><br />
            ③纳新咨询QQ群<br />
            （群号：372-996-378）<a href="http://qm.qq.com/cgi-bin/qm/qr?k=-Rps7yMM5yaeIPbdTSWZDvAl59IP4574">加群点这里</a><br />
			等待面试通知。<br />
            <br />
            <font size = "5">请继续努力！<br />
            <img src = "images/good.png" width = "50" height = "50" />
            </font><br />
		</font>
	<!-- 报名成功 内容 结束 -->
        
    <!-- 装载用户变量 数据 转存 存入数据库 开始 -->   
    	<?php 	
            $name = trim($_POST["name"]);
            $sex = $_POST["sex"];
            $class = trim($_POST["class"]);
            $telephone = trim($_POST["telephone"]);
            $email = trim($_POST["email"]);
            $arr_skill = $_POST["skill"];
			if ($arr_skill != "")
            	$skill = implode(";", $arr_skill);
			else
                $skill = "";
			//echo $skill;
            $direction = $_POST["direction"];
            $words = $_POST["words"];
            
            //记录 当前时间 开始
            date_default_timezone_set('PRC');
            $record_time = date('y-m-d g-i-s', time());
            //记录 当前时间 结束 
        ?>
                
        <!-- 数据库 开始 -->
        	<?php
            	// 连主库
            	$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                    
                // 连从库
                // $link = mysql_connect(SAE_MYSQL_HOST_S.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
                    
                if($link)
                {
                	mysql_select_db(SAE_MYSQL_DB, $link);
                    //开始执行
                    //echo "link successfully!";
                    $sql = "INSERT INTO `app_jsjr666`.`new_list2014` (`name`, `sex`, `class`, `telephone`, `email`, `skill`, `direction`, `words`, `record_time`) VALUES ('$name', '$sex', '$class', '$telephone', '$email', '$skill', '$direction', '$words', '$record_time')";
                    //$sql = "DELETE FROM `app_jsjr666`.`new_list2014` WHERE `new_list2014`.`user_id` = 127";
                    $query = mysql_query($sql);//执行sql语句
                    //echo "finished";
                 }
				 mysql_close($link);
        	?>        
    	<!-- 数据库 结束 -->            
    <!-- 装载用户变量 数据 转存 存入数据库 结束 --> 
        
	<!-- foot 开始 -->
		<font color = "white" face = "黑体">
			<br />
			<font size = "1px">
			<center>计算金融与风险管理&nbsp;研究中心</center>
			<center>Copyright &copy; All Rights Reserved.</center>
			<br />
			</font>
		</font>
	<!-- foot 结束 -->		
    </center>
        
	</body>
</html>