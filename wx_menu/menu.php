<?php
    $APPID="wxa0bbaf0b185e5ea8";
    $APPSECRET="09c7f82e0a6b5211414a0cf94bcb1b2f";
    $TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;
    $json=file_get_contents($TOKEN_URL);
    $result=json_decode($json,true);
    print_r($result);
	echo "<br />";
	$ACC_TOKEN=$result['access_token'];
	echo $ACC_TOKEN;

    $MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACC_TOKEN;
    
    $ch = curl_init(); 
    
    curl_setopt($ch, CURLOPT_URL, $MENU_URL); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    
    $info = curl_exec($ch);
	echo "<br />";
    if (curl_errno($ch)) {
        echo 'Errno'.curl_error($ch);
    }
    
    curl_close($ch);
    
    var_dump($info);

?>

{
    "button": [
        {
            "type": "view", 
            "name": "中心微社区", 
            "url": "http://wsq.qq.com/reflow/231014598"
        }, 
        {
            "type": "view", 
            "name": "纳新报名", 
            "url": "http://1.jsjr666.sinaapp.com/signup/index.php"
        }, 
        {
            "name": "关于中心", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "首页", 
                    "url": "http://222.24.19.28/wap/"
                }, 
                {
                    "type": "view", 
                    "name": "简介", 
                    "url": "http://222.24.19.28/wap/index.php?lang=cn&id=19&module=1"
                }, 
                {
                    "type": "view", 
                    "name": "动态", 
                    "url": "http://222.24.19.28/wap/index.php?lang=cn&class2=85&module=3"
                }
            ]
        }
    ]
}