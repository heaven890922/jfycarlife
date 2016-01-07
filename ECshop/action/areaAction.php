<?
session_start();
include_once '../../Bin/MySqlHelper.class.php';
include_once '../../Bin/Log.class.php';
$userID=$_SESSION['coustomid'];
$m=$_REQUEST['m'];

//设置默认地址；
if($m=='GETAREA'){
	$areaid=$_REQUEST['areaID'];
	$sql="SELECT * FROM sh_store_area WHERE fid=$areaid";
	$Mysql=new MySqlHelper();
	$dataArea=$Mysql->FetchData($sql);
	echo json_encode($dataArea);
	return;
}







?>