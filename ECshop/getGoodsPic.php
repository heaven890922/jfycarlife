<?php  
    include_once '../Bin/MySqlHelper.class.php'; //连接数据库 
	include_once("../Bin/Log.class.php");  
     $Mysql=new MySqlHelper;  
    //$user = array('demo1','demo2','demo3','demo3','demo4');   
    $sqllimit=$_REQUEST['sqllimit'];
	$page = $_REQUEST['page'];
	//$page=0;  //获取请求的页数   
    $start = $page*5; 
	$sqllimit=$sqllimit." ".$start.",5 "; 
	//new Log('',$username.'修改的sql语句==='.$sqllimit); 
    $query=$Mysql->FetchData($sqllimit);   
    
     echo json_encode($query);
	 return;  //转换为json数据输出   
?>  