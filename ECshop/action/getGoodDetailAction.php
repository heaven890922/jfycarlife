<?
include_once '../../Bin/MySqlHelper.class.php';
$id=$_REQUEST['id'];
if($id>0){
	
	$Mysql = new MySqlHelper();
	$data=$Mysql->FetchData("select dnum,dprice from sh_store_goods_detail where detailid=$id");
	$num=$data[0]['dnum'];
	$price=$data[0]['dprice'];
	echo json_encode(array("msg"=>"200","num"=>$num,"price"=>$price));
	
	}
else{
	echo json_encode(array("msg"=>"110","num"=>'0'));
	}	

?>