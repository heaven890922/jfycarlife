<?
@session_start();
include_once '../../Bin/MySqlHelper.class.php';
include_once '../../Bin/Log.class.php';
include_once 'config.php';
include_once '../class/order.class.php';
$userID=$_SESSION['coustomid'];

$m=$_REQUEST['m'];

//提交订单
if($m=='SUBMITORDER'){
	$cartId=$_REQUEST['cartId'];
	$addressId=$_REQUEST['addressId'];
	$remarks=$_REQUEST['remarks'];
	$expressprice=EXPRESSPRICE;
	$cartId=substr($cartId,0,-1);
	if($remarks==''){
		$remarks='无备注';	
	}
	$orderNum=date('YmdHis').rand(100,999);
	//new Log("","sss".$userID);	
	$Order=new Order();
	//
	$createOrderResult=$Order->orderSubmit($cartId,$addressId,$remarks,$expressprice,$userID,$orderNum);
	//new Log("","sss".$createOrderResult);
	if($createOrderResult=='OK'){
		echo json_encode(array("code"=>"200","mess"=>'订单提交成功！',"ordernum"=>$orderNum));
		return;	
	}elseif($createOrderResult=='REPETE_ORDERNUM'){
		echo json_encode(array("code"=>"200","mess"=>'订单提交成功！',"ordernum"=>$orderNum));
		return;	
	}else{
		echo json_encode(array("code"=>"110","mess"=>'订单提交失败，请重试！'));
		return;		
	}
	
}elseif($m=='CANCELORDER'){
	$orderNum=$_REQUEST['orderNum'];
	
	$sqlCancel="UPDATE sh_store_order SET delstate='Y' WHERE ordernum ='$orderNum' and userid=$userID";
	$Mysql= new MySqlHelper();
	$effectRow=$Mysql->ExecuteSql($sqlCancel);
	
	if($effectRow>0){
		echo json_encode(array("code"=>"200","mess"=>'订单取消成功！'));
		return;		
	}else{
		echo json_encode(array("code"=>"110","mess"=>'订单取消失败，请重试！'));
		return;	
	}
	
}elseif($m=='PAYORDER'){
	$orderNum=$_REQUEST['orderNum'];
	$Order=new Order();
	$orderResult=$Order->orderPay($orderNum,$userID);
	//new Log("","ss".$orderResult);
	if($orderResult=='PAY_SUCCESS'){
		echo json_encode(array("code"=>"200","mess"=>'订单支付成功！'));
		return;		
	}elseif($orderResult=='PAY_FAIL'){
		echo json_encode(array("code"=>"110","mess"=>'订单支付失败，请联系客户或管理员！'));
		return;		
	}elseif($orderResult=='NOT_ENOUGH'){
		echo json_encode(array("code"=>"120","mess"=>'余额不足，订单支付失败！'));
		return;		
	}elseif($orderResult=='STOCK_NOT_ENOUGH'){
		echo json_encode(array("code"=>"120","mess"=>'库存不足，订单支付失败！'));
		return;		
	}else{
		echo json_encode(array("code"=>"911","mess"=>'非法访问！'));
		return;
	}
	
		
}elseif($m=='ORDERBACK'){
	$orderNum=$_REQUEST['orderNum'];
	$reason=$_REQUEST['reason'];
	$orderid=$_REQUEST['orderid'];
	$Mysql=new MySqlHelper();
	$time=date('Y-m-d H:i:s',time());
	//检查是否重复提交
	$sqlcheck="select id from sh_store_order_cancel where orderid=$orderid";
	$row=count($Mysql->FetchData($sqlcheck));
	if($row>0){
		echo json_encode(array("code"=>"110","mess"=>'请勿重复提交！'));
		return;	
	}
	//更改订单状态
	$sqlBack="UPDATE sh_store_order SET orderstate='FORCANCEL' WHERE ordernum='$orderNum' AND userid =$userID";
	//插入订单取消申请记录
	$sqlInBack="INSERT INTO  sh_store_order_cancel  (orderid,reason,createtime) VALUES  ($orderid,'$reason','$time') ";
	$Mysql->Query("BEGIN");
	$upRow=$Mysql->ExecuteSql($sqlBack);
	$inRow=$Mysql->ExecuteSql($sqlInBack);
	if(($upRow>0)&&($inRow>0)){
		$Mysql->Query("COMMIT");
		$Mysql->Query("END");
		echo json_encode(array("code"=>"200","mess"=>'申请提交成功！'));
		return;
	}else{
		$Mysql->Query("ROLLBACK");
		$Mysql->Query("END");
		new Log("","订单号为".$orderNum."的订单提交退款申请失败，更改订单状态的sql为====".$sqlBack."退款申请插入sql为===".$sqlInBack);
		echo json_encode(array("code"=>"110","mess"=>'申请提交失败！'));
		return;	
	}
		
}elseif($m=='GET'){
	$orderNum=$_REQUEST['orderNum'];
	$sqlGet="UPDATE sh_store_order SET orderstate='FINISH' WHERE ordernum='$orderNum'";
	$Mysql=new MySqlHelper();
	$getRow=$Mysql->ExecuteSql($sqlGet);
	if($getRow>0){
		echo json_encode(array("code"=>"200","mess"=>'确认收货成功！'));
		return;
	}else{
		new Log("","订单号为".$orderNum."的订单确认收货失败，sql===".$sqlGet);
		echo json_encode(array("code"=>"110","mess"=>'确认收货失败！'));
		return;
	}
}elseif($m=='BUYNOW'){
	$gdetailid=$_REQUEST['gdetailid'];
	$addressId=$_REQUEST['addressId'];
	$sdnum=$_REQUEST['sdnum'];
	$remarks=$_REQUEST['remarks'];
	$expressprice=EXPRESSPRICE;
	if($remarks==''){
		$remarks='无备注';	
	}
	$orderNum=date('YmdHis').rand(100,999);
	
	$sqlInfo="SELECT detailid,dprice,dnum,state FROM sh_store_goods_detail WHERE detailid=$gdetailid";
	$Mysql=new MySqlHelper();
	$dataInfo=$Mysql->FetchData($sqlInfo);
	if($dataInfo[0]['state']!='ON'){
		echo json_encode(array("code"=>"110","mess"=>'该商品已经下架！'));
		return;
	}
	if($dataInfo[0]['dnum']<$sdnum){
		echo json_encode(array("code"=>"110","mess"=>'库存量不足！'));
		return;
	}
	$price=$dataInfo[0]['dprice'];
	$totalmoney=$price*$sdnum+$expressprice;
	$time=date("Y-m-d H:i:s",time());
	
	
	$Mysql->Query("BEGIN");
	$sqlInToOrder="INSERT INTO sh_store_order (userid,ordertime,paystate,deliverstate,orderstate,money,discountmoney,countmoney,realpay,paytype,remarks,address,phone,`name`,expressPrice,ordernum) SELECT $userID,'$time','N', 'NOT','PRE',$totalmoney,0,$totalmoney,0,4,'$remarks',concat(ar2.areaname,ar1.areaname,ar.areaname,a.detailaddress) AS address,a.tel,a.`name`,$expressprice,'$orderNum' FROM sh_store_address a JOIN sh_store_area ar  ON a.countyid=ar.areaid  JOIN sh_store_area ar1 ON a.cityid=ar1.areaid JOIN sh_store_area ar2 ON a.provinceid=ar2.areaid WHERE id=$addressId AND a.userid=$userID AND delstate='N' limit 1";
	//new Log("","ssss".$sqlInToOrder);
	$effectRow=$Mysql->ExecuteSql($sqlInToOrder);
	if($effectRow>0){
		$sqlSearchOrderId="SELECT orderid FROM sh_store_order WHERE ordernum='$orderNum' LIMIT 1 ";	
		//new Log(""," ad".$sqlSearchOrderId);
		$dataTableSearchOID=$Mysql->FetchData($sqlSearchOrderId);
		
		$row=count($dataTableSearchOID);
		if($row==1){
			$orderId=$dataTableSearchOID[0][0];
			//插入订单详细信息到sh_store_order_detail表
			$sqlInToOrderDetail="INSERT INTO sh_store_order_detail  (goodsid,price,orderid,num) values ($gdetailid,$price,$orderId,$sdnum)  ";
			//new Log("","详细".$sqlInToOrderDetail);
			$effectDetailRows=$Mysql->ExecuteSql($sqlInToOrderDetail);
			if($effectDetailRows>0){
				//订单提交成功时，减少商品库存量
				$Order=new Order();
				$numUpResult=$Order->updateNums($orderId);
				if(!$numUpResult){
				$Mysql->Query("ROLLBACK");
				$Mysql->Query("END");	
				echo json_encode(array("code"=>"110","mess"=>'库存量不足！'));
				return;
			}
				
				
					$Mysql->Query("COMMIT");
					$Mysql->Query("END");
					echo json_encode(array("code"=>"200","mess"=>'订单提交成功！',"ordernum"=>$orderNum));
					return;	
				
				
			}else{
				new Log("","订单id为$orderId的订单插入订单详细失败(直接购买)！sql===".$sqlInToOrderDetail);
				$Mysql->Query("ROLLBACK");
				$Mysql->Query("END");	
				echo json_encode(array("code"=>"110","mess"=>'订单提交失败！'));
				return;
			}
		}else{
			new Log("","订单号为".$orderNum."查询订单id失败(直接购买)，sql===".$sqlInToOrder);
			$Mysql->Query("ROLLBACK");
			$Mysql->Query("END");	
			//return "ORDER_FAIL";	
			echo json_encode(array("code"=>"110","mess"=>'订单提交失败！'));
			return;
		}
		
	}else{
		new Log("","直接购买创建订单失败，sql===".$sqlInToOrder);
		echo json_encode(array("code"=>"110","mess"=>'订单提交失败！'));
		return;
			
	}
	
	
}







?>