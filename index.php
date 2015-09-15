<?php
require_once 'mysql.php';
$wechatObj = new wechat();
$wechatObj->responseMsg();
class wechat {



  public function responseMsg() {
  //---------- 接 收 数 据 ---------- //
  $postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; //获取POST数据
  //用SimpleXML解析POST过来的XML数据
  date_default_timezone_set("PRC");
  $postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
  $fromUsername = $postObj->FromUserName; //获取发送方帐号（OpenID）
  $toUsername = $postObj->ToUserName; //获取接收方账号
  $keyword = trim($postObj->Content); //获取消息内容
        $time =date("Y-m-d H:i:s",time());//date('h:i',time()); //获取当前时间戳

        $keywords = explode(" ",$keyword);


  //---------- 返 回 数 据 ---------- //
  //返回消息模板
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        <FuncFlag>0</FuncFlag>
      </xml>";


      if($keywords[0] == '取消'){

       $delete_sql = "delete from list where number = ' $keywords[1] '";

       $res = _delete_data($delete_sql);
       if($res == 1){
         $contentStr ="预订取消完成";
       }elseif($res == 0){
        $contentStr ="取消失败";
      }elseif($res == 2){
       $contentStr ="没有预订，不能取消";
     }


   }

   elseif(trim($keywords[0] == '预订')){

    $insert_sql="INSERT INTO list(name, number,data,star) VALUES('$keywords[1]','$keywords[2]','$time','2')";
    $res = _insert_data($insert_sql);
    if($res == 1){
      $contentStr = "Dear ".$keywords[1]."，你已经成功预订".$keywords[2]."号，Good Luck！";
    }elseif($res == 0){
      $contentStr = "不能重复预定";
    }
    
  }
  elseif(trim($keywords[0] == '大神预订')){

    $insert_sql="INSERT INTO list(name, number,data,star) VALUES('$keywords[1]','$keywords[2]','$time','3')";
    $res = _insert_data($insert_sql);
    if($res == 1){
      $contentStr = "Dear ".$keywords[1]."大神，你已经成功预订".$keywords[2]."号，Good Luck！";
    }elseif($res == 0){
      $contentStr = "不能重复预定";
    }
  }
  
  elseif(trim($keywords[0] == '帮忙预订')){

    $insert_sql="INSERT INTO list(name, number) VALUES('$keywords[1]','$keywords[2]')";
    $res = _insert_data($insert_sql);
    if($res == 1){
      $contentStr = "Dear ".$keywords[1]."，你已经成功预订".$keywords[2]."号，Good Luck！";
    }elseif($res == 0){
      $contentStr = "不能重复预定";
    }

  }
  
  elseif(trim($keywords[0] == '查询')){


   $flag1; 
   $flag2;
   $select_sql1="SELECT * FROM list WHERE star=2 ORDER BY number";
   $select_res1=_select_data($select_sql1);
   if($select_res1){

    while($rows=mysql_fetch_array($select_res1)){

      $contentStr1=$contentStr1.$rows[2]."号  ".date("H:i",strtotime($rows[3]))."  预订人：".$rows[1]."\n";}
      $flag1=1;

    }else{
      $flag1=0;

    }

    $select_sql2="SELECT * FROM list WHERE star=3 ORDER BY number";
    $select_res2=_select_data($select_sql2);
    if($select_res2){

      while($rows=mysql_fetch_array($select_res2)){

        $contentStr2=$contentStr2.$rows[2]."号  ".date("H:i",strtotime($rows[3]))."  预订人：".$rows[1]."\n";}
        $flag2=1;

      }else{
        $flag2=0;

      }  
      if ($flag1+$flag2>0)  {

        $contentStr ="         三星十本名单\n".$contentStr2."\n"."         普通预定名单"."\n".$contentStr1;}
        else{
          $contentStr ="还没人预定";
        }


      } 
      elseif(trim($keywords[0] == '我们都是基佬')){
        $select_sql="TRUNCATE TABLE  list";
        $select_res=_clear_table($select_sql);


        $contentStr = "清空成功";
      }
      else{
        $contentStr = "欢迎来到傲寒团战预定平台！\n\n一、查询请输入：查询\n二、预定请输入：预订 游戏昵称 号码，中间有空格，例如：预订 粟米 50，十本三星预定请输入：大神预定 游戏id 号码\n三、取消请输入：取消 号码，例如;取消 12\n注意：预定后请抓紧时间打，普通预定2个小时之后就会自动删除掉，三星十本为5小时！如果忘记格式请输入任意字符查看本消息。如有好的改进建议请@letsdestroy";
      }


       $msgType = "text"; //消息类型

  //格式化消息模板
       $resultStr = sprintf($textTpl,$fromUsername,$toUsername,
        $time,$msgType,$contentStr);




  echo $resultStr; //输出结果
}
}
?>

