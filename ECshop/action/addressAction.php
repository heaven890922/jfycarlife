<?
session_start();
include_once '../../Bin/MySqlHelper.class.php';
include_once '../../Bin/Log.class.php';
$userID=$_SESSION['coustomid'];
$m=$_REQUEST['m'];

//设置默认地址；
if($m=='SETDEFULT'){
	$id=$_REQUEST['id'];
	if($id==''){
		echo json_encode(array("code"=>"120","mess"=>'数据异常，设置失败'));
		return;
	}
	//符合调教的设置成默认地址，否则设置成普通地址；
	$sql="UPDATE  sh_store_address SET state =CASE  WHEN id=$id THEN 'ON' ELSE 'OFF'  END WHERE userid=$userID";	
	$Mysql=new MySqlHelper();
	$row=$Mysql->ExecuteSql($sql);
	if($row>0){
		echo json_encode(array("code"=>"200","mess"=>'设置成功'));
		return;	
	}else{
		echo json_encode(array("code"=>"110","mess"=>$sql));
		return;
	}
}elseif($m=='ADDADDRESS'){
	//添加地址
	$name=$_REQUEST['name'];
	$tel=$_REQUEST['tel'];
	$province=$_REQUEST['province'];
	$city=$_REQUEST['city'];
	$country=$_REQUEST['country'];
	$detail_address=$_REQUEST['detail_address'];
	$time=date('Y-m-d H:i:s',time());
	$Mysql= new MySqlHelper();
	//检查是否已经存在默认地址；
	$sqlcheck="SELECT id FROM sh_store_address where userid=2 AND delstate='N' AND state='ON'";
	$datacheck=$Mysql->FetchData($sqlcheck);
	$flag=count($datacheck);
	//如果存在默认地址，则设置为普通地址，如果不存在默认地址，则设置成默认地址。
	if($flag>0){
		$state='OFF';	
	}else{
		$state='ON';	
	}
	$sqlinaddress="INSERT INTO sh_store_address  (userid,provinceid,cityid,countyid,detailaddress,createtime,tel,`name`,state) VALUES ($userID,$province,$city,$country,'$detail_address','$time','$tel','$name','$state')";
	$row=$Mysql->ExecuteSql($sqlinaddress);
	if($row>0){
		echo json_encode(array("code"=>"200","mess"=>'添加成功！'));
		return;	
	}else{
		echo json_encode(array("code"=>"110","mess"=>'添加失败！'));
		return;	
	}
	
		
}elseif($m=='DELETE'){
	//删除地址
	$id=$_REQUEST['id'];
	$sql="UPDATE sh_store_address SET state='OFF',delstate='Y' WHERE id=$id";
	$Mysql= new MySqlHelper();
	$row= $Mysql->ExecuteSql($sql);
	if($row>0){
		echo json_encode(array("code"=>"200","mess"=>'删除成功！'));
		return;	
	}else{
		echo json_encode(array("code"=>"110","mess"=>'删除失败！'));
		return;
	}
	
}elseif($m=='UPDATEADDRESS'){
	//修改地址
	$id=$_REQUEST['id'];
	$name=$_REQUEST['name'];
	$tel=$_REQUEST['tel'];
	$province=$_REQUEST['province'];
	$city=$_REQUEST['city'];
	$country=$_REQUEST['country'];
	$detail_address=$_REQUEST['detail_address'];
	$time=date('Y-m-d H:i:s',time());
	$Mysql= new MySqlHelper();
	
	
	$sqlupaddress="UPDATE sh_store_address SET provinceid =$province ,cityid=$city ,countyid=$country ,detailaddress='$detail_address' ,tel='$tel' , `name`='$name' WHERE id=$id AND userid =$userID";
	$row=$Mysql->ExecuteSql($sqlupaddress);
	if($row>0){
		echo json_encode(array("code"=>"200","mess"=>'修改成功！'));
		return;	
	}else{
		echo json_encode(array("code"=>"110","mess"=>'修改失败！'));
		return;	
	}
	
}elseif($m=='EDITADDRESS'){
	//修改地址
	$id=$_REQUEST['id'];
	$name=$_REQUEST['name'];
	$tel=$_REQUEST['tel'];
	$province=$_REQUEST['province'];
	$city=$_REQUEST['city'];
	$country=$_REQUEST['country'];
	$detail_address=$_REQUEST['detail_address'];
	$time=date('Y-m-d H:i:s',time());
	$Mysql= new MySqlHelper();
	
	
	$sqlupaddress="UPDATE sh_store_address SET provinceid =$province ,cityid=$city ,countyid=$country ,detailaddress='$detail_address' ,tel='$tel' , `name`='$name' WHERE id=$id AND userid =$userID";
	$row=$Mysql->ExecuteSql($sqlupaddress);
	if($row>0){
		echo json_encode(array("code"=>"200","mess"=>'修改成功！'));
		return;	
	}else{
		echo json_encode(array("code"=>"110","mess"=>'修改失败！'));
		return;	
	}
	
}elseif($m=='LISTADDADDRESS'){
	//添加地址并使用
	$name=$_REQUEST['name'];
	$tel=$_REQUEST['tel'];
	$province=$_REQUEST['province'];
	$city=$_REQUEST['city'];
	$country=$_REQUEST['country'];
	$detail_address=$_REQUEST['detail_address'];
	$time=date('Y-m-d H:i:s',time());
	$Mysql= new MySqlHelper();
	//检查是否已经存在默认地址；
	$sqlcheck="SELECT id FROM sh_store_address where userid=$userID AND delstate='N' AND state='ON'";
	$datacheck=$Mysql->FetchData($sqlcheck);
	$flag=count($datacheck);
	//如果存在默认地址，则设置为普通地址，如果不存在默认地址，则设置成默认地址。
	if($flag>0){
		$state='OFF';	
	}else{
		$state='ON';	
	}
	$sqlinaddress="INSERT INTO sh_store_address  (userid,provinceid,cityid,countyid,detailaddress,createtime,tel,`name`,state) VALUES ($userID,$province,$city,$country,'$detail_address','$time','$tel','$name','$state')";
	$row=$Mysql->ExecuteSql($sqlinaddress);
	$sqlsearchid="SELECT id FROM sh_store_address WHERE userid=$userID ORDER BY createtime  DESC  LIMIT 1";
	$datasearchid=$Mysql->FetchData($sqlsearchid);
	
	if($row>0){
		echo json_encode(array("code"=>"200","mess"=>$datasearchid[0][0]));
		return;	
	}else{
		echo json_encode(array("code"=>"110","mess"=>'添加失败！'));
		return;	
	}	
	
}







?>