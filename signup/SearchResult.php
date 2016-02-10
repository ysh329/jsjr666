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
        
		<title>纳新报名后台——搜素结果</title>
		<link href = "css/webadmin_style.css" rel="stylesheet" type="text/css" />   		
	</head>
	<body>
		
		<h2>纳新报名表——搜素结果</h2>
		<p>您输入的搜索关键字为:
			<?php
				$searchs = $_POST['search'];
				echo $searchs;
			?>
			,以下为搜索结果:
		</p>
		<table class="bordered">
			<thead>
				<tr>
					<th>#</th>        
					<th>姓名</th>
					<th>性别</th>
					<th>专业班级</th>
					<th>手机号码</th>
					<th>电子邮箱</th>
					<th>基本技能</th>
					<th>选择方向</th>
					<th>想说的话</th>
					<th>记录时间</th>
				</tr>
			</thead>

			<!-- 数据库 开始 -->
			<?php
				// 连主库
				$link = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);

				if($link)
				{
					mysql_select_db(SAE_MYSQL_DB, $link);
					//开始执行
					//echo "link successfully!";
					$sql = "SELECT * FROM `app_jsjr666`.`new_list2014` WHERE like `$searchs` LIMIT 0, 30 ";
					//$sql = "DELETE FROM `app_jsjr666`.`new_list2014` WHERE `new_list2014`.`user_id` = 127";
					$query = mysql_query($sql);//执行sql语句
					echo "finished";

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