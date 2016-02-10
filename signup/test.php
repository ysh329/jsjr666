        <meta charset="UTF-8">
        <!--<meta name="viewport" content="width=device-width,user-scalable=no">-->
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=2.0">  
<?php 
if( stristr($_SERVER['HTTP_USER_AGENT'],'MSIE') ){$mess = '这是ie浏览器';}
if( stristr($_SERVER['HTTP_USER_AGENT'],'Chrome') ){$mess = '这是谷歌Chorme浏览器';} 
if( stristr($_SERVER['HTTP_USER_AGENT'],'Firefox') ){$mess = '这是火狐浏览器';} 
echo $mess; 
echo '<BR>';
print_r($_SERVER['HTTP_USER_AGENT']);
exit();
?>