<?php
	error_reporting(0);//关闭错误报告
?>
<html>
	<head>
        <meta charset="UTF-8">
        <!--<meta name="viewport" content="width=device-width,user-scalable=no">-->
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=2.0">  
        
        <!-- 禁止用户缩放 开始 -->
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" />
        <!-- 禁止用户缩放 结束 -->
        
		<title>纳新报名后台——纳新人员性别统计名单</title>
		<link href = "css/webadmin_style.css" rel="stylesheet" type="text/css" />   	

	</head>
	<body>
		
		<h2>纳新报名表——纳新人员性别统计名单</h2>
		当前共计：
		<?php
            // 连主库
            $link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
			
			if($link)
			{
				mysql_select_db(SAE_MYSQL_DB, $link);			
				$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
				$sql = "select count(*) as num from new_list2014";
				$query = mysql_query($sql);//执行sql语句
				$num_all = mysql_fetch_array($query);//得到的是数组
				$num_all = $num_all[0];
				echo $num_all;

				$sql = "SELECT COUNT(*) FROM `new_list2014` WHERE `direction`='数学建模'";
				$query = mysql_query($sql);//执行sql语句
				$num_math = mysql_fetch_array($query);//得到的是数组
				$num_math = $num_math[0];

				$sql = "SELECT COUNT(*) FROM `new_list2014` WHERE `direction`='软件开发'";
				$query = mysql_query($sql);//执行sql语句
				$num_dev = mysql_fetch_array($query);//得到的是数组
				$num_dev = $num_dev[0];

				$sql = "SELECT COUNT(*) FROM `new_list2014` WHERE `direction`='金融分析'";
				$query = mysql_query($sql);//执行sql语句
				$num_fin = mysql_fetch_array($query);//得到的是数组
				$num_fin = $num_fin[0];

				$sql = "SELECT COUNT(*) FROM `new_list2014` WHERE `sex`='男'";
				$query = mysql_query($sql);//执行sql语句
				$num_male = mysql_fetch_array($query);//得到的是数组
				$num_male = $num_male[0];

				$sql = "SELECT COUNT(*) FROM `new_list2014` WHERE `sex`='女'";
				$query = mysql_query($sql);//执行sql语句
				$num_female = mysql_fetch_array($query);//得到的是数组
				$num_female = $num_female[0];					
			}
		?>
		人（其中<a href="list_math.php">数学建模：<?php echo $num_math;?>人</a>；<a href="list_dev.php">软件开发：<?php echo $num_dev;?>人</a>；<a href="list_fin.php">金融分析：<?php echo $num_fin;?>人</a>。<a href="list_sex.php">男：<?php echo $num_male;?>人</a>；<a href="list_sex.php">女：<?php echo $num_female;?>人</a>）
		
		<!-- 导航 开始 -->
		<div class="nav">
			<ul>
				<li class="cur"><a href="webadmin.php">首页</a></li>
				<li><a href="list_math.php">数学建模</a></li>
				<li><a href="list_dev.php">软件开发</a></li>
				<li><a href="list_fin.php">金融分析</a></li>
				<li><a href="sql_operate.php">条目追加</a></li>
				<li><a href="sql_operate.php">条目删除</a></li>
				<li><a href="sql_operate.php">条目修改</a></li>
				<li><a href="sql_operate.php">条目查询</a></li>
			</ul>
			<div class="curBg"></div>
			<div class="cls"></div>
		</div>  		
		<!-- 导航 结束 -->
		
		<!-- 搜索 开始 -->
		<div id="search_box"> 
			<form id="search_form" method="post" action="SearchResult.php" name="search">
			整表查询：
				<input name="search" type="text" value="" size="15"/> 
				<input type="submit" value="查询" /> 
				<div id="showtime" align="right">当前时间：<?php echo $showtime=date("Y-m-d H:i:s");?> </div>
			</form> 
		</div> 
		<!-- 搜索 结束 -->		
		<table class="bordered">
			<thead>
				<tr>
					<th>#</th>        
					<th><a href="#">姓名</a></th>
					<th><a href="#">性别</a></th>
					<th><a href="#">专业班级</a></th>
					<th><a href="#">手机号码</a></th>
					<th><a href="#">电子邮箱</a></th>
					<th><a href="#">基本技能</a></th>
					<th><a href="#">选择方向</a></th>
					<th><a href="#">想说的话</a></th>
					<th><a href="#">记录时间</a></th>
				</tr>
			</thead>

			<!-- 数据库 继续-->
			<?php
				if($link)
				{
					mysql_select_db(SAE_MYSQL_DB, $link);
					//开始执行
					//echo "link successfully!";
					$sql = "SELECT * FROM `new_list2014` WHERE `sex` LIKE '男' LIMIT 0, 30 ";
					//$sql = "DELETE FROM `app_jsjr666`.`new_list2014` WHERE `new_list2014`.`user_id` = 127";
					$query = mysql_query($sql);//执行sql语句
					//echo "finished";
					
					$counter = 0;
					while($row = mysql_fetch_array($query))
					{
						$counter = $counter + 1;
						echo "<tr><td>".$counter."</td><td>".$row['name']."</td><td>".$row['sex']."</td><td>".$row['class']."</td><td>".$row['telephone']."</td><td>".$row['email']."</td><td>".$row['skill']."</td><td>".$row['direction']."</td><td>".$row['words']."</td><td>".$row['record_time']."</td></tr>";
					}
				 }
			?>        
			<!-- 数据库 后续 -->
		</table>
		<!-- 数据库 开始 -->
		<?php 
			mysql_close($link);
		?>
		<!-- 数据库 结束 -->
	<br><br>
	<!-- foot 开始 -->
		<font color = "black" face = "黑体">
			<br />
			<font size = "1px">
			<center>计算金融与风险管理&nbsp;研究中心</center>
			<center>Copyright &copy; All Rights Reserved.</center>
			<br />
			</font>
		</font>
	<!-- foot 结束 -->	
	</body>
</html>