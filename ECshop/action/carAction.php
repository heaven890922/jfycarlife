<?
session_start();
include_once '../../Bin/MySqlHelper.class.php';
include_once '../../Bin/Log.class.php';
$userID=$_SESSION['coustomid'];
$m=$_REQUEST['m'];




if($m=="ADD"){
	$gdetailid=$_REQUEST['gdetailid'];
	$sdnum=$_REQUEST['sdnum'];
	$gsname=$_REQUEST['gsname'];
	$gdname=$_REQUEST['gdname'];
	
	if($userID<0||$gdetailid<0||$sdnum<0){
		
		echo json_encode(array("code"=>"120","mess"=>'数据异常，提交失败'));
		return;
		
	}
	
	if($gsname==''||$gdname==''){
		echo json_encode(array("code"=>"120","mess"=>'数据异常，提交失败'));
		return;	
	}
	//查询购物车中是否已经有了改商品
	$sqlsel="SELECT id FROM sh_store_cart WHERE userid=$userID AND gdetailid=$gdetailid AND state='ON' limit 1 ";
	$Mysql=new MySqlHelper();
	$datasel=$Mysql->FetchData($sqlsel);
	$flag=0;
	$flag=count($datasel);
	$time=date('Y-m-d H:i:s');
	//如果已经存在则增加数量，如果不存在则插入到购物车
	//new Log('','sds'.$sqlsel);
	if($flag>0){
		$sql="UPDATE sh_store_cart SET snum=snum+$sdnum WHERE id=".$datasel[0]['id'];
	}else{
		
		$sql="INSERT INTO sh_store_cart (gdetailid,gsname,gdname,snum,userid,createtime) VALUES ($gdetailid,'$gsname','$gdname',$sdnum,$userID,'$time')";
	}
	$row=0;
	$row=$Mysql->ExecuteSql($sql);
	
	$sqlnum="SELECT sum(snum) AS scarnum FROM sh_store_cart WHERE state='ON' AND userid=".$userID;
	$datanum=$Mysql->FetchData($sqlnum);
	$scarnum=$datanum[0][0];
	if($row>0){
		echo json_encode(array("code"=>"200","mess"=>$scarnum));
		return;
	}else{
		echo json_encode(array("code"=>"390","mess"=>$sql));
		return;		
	}


}elseif($m=="DELETE"){
	$id=$_REQUEST['id'];
	
	$sql="update sh_store_cart set state='DEL' where id=$id";
	$Mysql= new MySqlHelper();
	$row=$Mysql->ExecuteSql($sql);
	if($row>0){
		echo json_encode(array("code"=>"200","mess"=>"删除成功！"));
		return; 	
	}else{
		echo json_encode(array("code"=>"110","mess"=>"删除失败！"));
		return; 
	}
}elseif($m=='TOACCOUNT'){
	
	$childs=$_REQUEST['childs'];
	$resdnum=$_REQUEST['resdnum'];
	echo json_encode(array("code"=>"200","mess"=>$childs[1]));
	return; 	
}elseif($m=='CHANGENUM'){
	$id=$_REQUEST['id'];
	$num=$_REQUEST['num'];
	$sql="UPDATE sh_store_cart SET snum=$num WHERE gdetailid=$id AND userid=".$userID;
	$Mysql= new MySqlHelper();
	$row=$Mysql->ExecuteSql($sql);
	if($row>0){
		echo json_encode(array("code"=>"200","mess"=>"修改成功！"));
		return; 	
	}else{
		echo json_encode(array("code"=>"110","mess"=>"修改失败！"));
		return; 
	}
	
		
}elseif($m=='CHECK'){
	$strid=$_REQUEST['strid'];
	$flag=false;
	if($strid!=''){
	$strid=substr($strid,0,-1);	
	$flag=true;
	}else{
	$strid=0;	
	}
	$sql="update sh_store_cart set checkstate=CASE WHEN  gdetailid  in ($strid)  THEN 'ON' ELSE 'OFF'  END  WHERE userid=$userID";
	
	
	$Mysql=new MySqlHelper();
	$row=$Mysql->ExecuteSql($sql);
	if($row>0){
		echo json_encode(array("code"=>"200","mess"=>"修改成功！"));
		return; 	
	}else{
		echo json_encode(array("code"=>"110","mess"=>$strid));
		return;
	}
	
}






?>