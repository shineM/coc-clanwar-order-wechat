  <?php
 
   require_once 'mysql.php';
   //获取当前时间戳 
    $nowhour=date("H",time());
    
    $select_sqlone="SELECT * FROM list WHERE star=2 ORDER BY number";
    $select_res=_select_data($select_sqlone);
      if($select_res){
          
              while($rows=mysql_fetch_array($select_res)){
                  
                  $shijiancha1=(int)$nowhour-(int)(date("H",strtotime($rows[3])));
                  $temp=$rows[1];
                   if($shijiancha1<0){
                    $shijiancha1=$shijiancha1+24;
                  }
                   echo "    现在时间    ".$nowhour;
                 
                echo "     预定时间".date("H",strtotime($rows[3]));
                 
                   echo "       时间差     ".$shijiancha1;
                 
                  if($shijiancha1>2){
                      //echo " 超过3小时   ";
                       $delete_sql = "delete from list where name = '$temp'";
                         $res = _delete_data($delete_sql);
                     if($res == 1){
                          echo "预订取消完成";
                       }elseif($res == 0){
                           echo "取消失败";
                     }elseif($res == 2){
                           echo "没有预订，不能取消";
                        }
                  }
                  else{
                          echo " 未超过3小时   ";
                     }
                  echo "<br>";
                }
            
          
               }else{
            echo "没数据";
      
        }

    $select_sqltwo="SELECT * FROM list WHERE star=3 ORDER BY number";
    $select_res=_select_data($select_sqltwo);
      if($select_res){
          
              while($rows=mysql_fetch_array($select_res)){
                  $shijiancha=(int)$nowhour-(int)(date("H",strtotime($rows[3])));
                  $temp=$rows[1];
                  if($shijiancha<0){
                    $shijiancha=$shijiancha+24;
                  }
                  echo "    现在时间    ".$nowhour;
                 
                echo "     预定时间".date("H",strtotime($rows[3]));
                 
                   echo "       时间差     ".$shijiancha;
                  
                  if($shijiancha>5){
                      //echo " 超过3小时   ";
                       $delete_sql = "delete from list where name = '$temp'";
                         $res = _delete_data($delete_sql);
                     if($res == 1){
                          echo "预订取消完成";
                       }elseif($res == 0){
                           echo "取消失败";
                     }elseif($res == 2){
                           echo "没有预订，不能取消";
                        }
                  }
                  else{
                          echo " 未超过5小时   ";
                     }
                  echo "<br>";
                }
            
          
               }else{
            echo "没数据";
      
        }
?>