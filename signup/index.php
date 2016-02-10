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
        
		<title>计算金融实验室纳新报名</title>
		<link href = "css/index_style.css" rel="stylesheet" type="text/css" />      
        
        <!-- 表单验证 开始 -->
        <script type = "text/javascript">
            //表单邮箱 验证 开始
            function validate_email(field, alerttxt)
            {
                with (field)
                {
                    apos = value.indexOf("@")
                    dotos = value.lastIndexOf(".")
                    if (apos<1 || dotpos-apos<2) 
                    {
                        alert(alerttxt);
                        return false;
                    }
                    else 
                    {
                        return true;
                    }
                }
            }			
            //表单邮箱 验证 结束
            function validate_form(thisform)
            {
                with(thisform)
                {
                    if (name.value == "" || telephone.value == "")
					{
                        alert("信息填写不完整");
                        name.focus();
                        return false;
					}                
                    if (validate_email(email, "好好填写邮箱，表调皮~" ) == false)
                    {
                        email.focus();
                        return false;
                    }
                }
            }
        </script>        
        <!-- 表单验证 结束 -->
	</head>
    	
	<body oncontextmenu=self.event.returnValue=false>
		<center>
		<!-- 报名导语 开始-->
		<font face = "微软雅黑" color = "white" size "7">
            <font size = "5">计算金融与风险管理</font>
			<br />
            <font size = "4">研究中心</font>
			<br />
			2015 纳新在即
			<br />
            朋友，<br />
            就请在下面填写你的报名信息吧！<br />
            :-P
            <hr />
		</font>
		<!-- 报名导语 结束-->
		
		<!-- 报名信息表 开始 -->
		<font face = "微软雅黑" color = "white">
            <form name = "default_form" action = "success.php" onsubmit = "return validate_form(this);" method = "post">
				1.姓名<input type = "text" name = "name" size = "6" maxlength = "5" />
                <br /><br />
                2.性别
                <input type = "radio" name = "sex" value = "女" />女
                <input type = "radio" name = "sex" value = "男" />男
                <br /><br />
				3.专业班级<input type = "text" name = "class" size = "10" maxlength = "6" />
                <br />例，“经济1301”
                <br /><br />
                4.手机<input type = "text" name = "telephone" size = "14" maxlength = "14" />
                <br /><br />
                5.邮箱<input type = "text" name = "email" maxlength = "25">
                <br /><br />
                6.基本技能(可多选)<br />
                <input type = "checkbox" name = "skill[]" value = "C/C++" />C/C++
                <input type = "checkbox" name = "skill[]" value = "JAVA" />JAVA<br />
				<input type = "checkbox" name = "skill[]" value = "Python" />Python<br />
                <input type = "checkbox" name = "skill[]" value = "数据结构与算法" />数据结构与算法<br />
                <input type = "checkbox" name = "skill[]" value = "SQL" />SQL
                <input type = "checkbox" name = "skill[]" value = "Photoshop" />Photoshop<br />
                <input type = "checkbox" name = "skill[]" value = "HTML/CSS/PHP" />HTML/CSS/PHP<br />
				<input type = "checkbox" name = "skill[]" value = "MATLAB/LINGO/SPSS" />MATLAB/LINGO/SPSS<br />
				<input type = "checkbox" name = "skill[]" value = "SAS/STATA/R/MATHEMATICS" />SAS/STATA/R/MATHEMATICS<br />
                <br />
                7.选择方向(单选)<br />
                <input type = "radio" name = "direction" value = "金融分析" />a.金融分析<br />
                <input type = "radio" name = "direction" value = "软件开发" />b.软件开发<br />
                <input type = "radio" name = "direction" value = "数学建模" />c.数学建模<br />
                <br />
                8.最想给我们说的一句话(限15字以内)<br />
                <textarea name = "words" cols = "15" rows = "2" maxlength = "15"></textarea>
                <br /><hr />             
                <input type = "submit"  name = "提交" value = '提交'/><input type = "reset" value = "重填" />
			</form>
		</font>
		<!-- 报名信息表 结束 -->  
        
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