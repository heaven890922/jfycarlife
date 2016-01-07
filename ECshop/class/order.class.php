<?
include_once '../../Bin/MySqlHelper.class.php';
include_once '../../Bin/Log.class.php';


class Order{
	/*
		订单提交函数
		$cartId购物车中选择要结算的id,
		$addressId选择的收货的地址id,
		$remarks订单的备注,
		$expressPrice订单运费,
		$userId用户id
	*/
	
	private $_result_order;
	
	public function orderSubmit($cartId,$addressId,$remarks,$expressPrice,$userId,$orderNum){
		$Mysql=new MySqlHelper();
		$checkOrderNumsql="SELECT orderid FROM sh_store_order WHERE ordernum =$orderNum LIMIT 1";
		$dataOrderNum=$Mysql->FetchData($checkOrderNumsql);
		$checkflag=count($dataOrderNum);
		if($checkflag>0){
			return "REPETE_ORDERNUM";	
		}
		
		//new Log("","sss".$orderNum);
		
		$time=date("Y-m-d H:i:s",time());
		
		//查询用户所选择结算购物车id的信息
		$sqlcartinfo="SELECT c.*,d.dprice FROM sh_store_cart c JOIN sh_store_goods_detail d ON c.gdetailid=d.detailid WHERE  c.id IN ($cartId)";
		$dataTableCartInfo=$Mysql->FetchData($sqlcartinfo);
		$goodsPrice=0;
		//统计商品购物清单中所选商品的总价格
		foreach($dataTableCartInfo as $itemCartInfo){
			$goodsPrice=$goodsPrice+($itemCartInfo['dprice']*$itemCartInfo['snum']);
		}
		//订单价格=商品价格+运费
		$orderPrice=$goodsPrice+$expressPrice;
		//生成订单
		$sqlInToOrder="INSERT INTO sh_store_order (userid,ordertime,paystate,deliverstate,orderstate,money,discountmoney,countmoney,realpay,paytype,remarks,address,phone,`name`,expressPrice,ordernum) SELECT $userId,'$time','N', 'NOT','PRE',$orderPrice,0,$orderPrice,0,4,'$remarks',concat(ar2.areaname,ar1.areaname,ar.areaname,a.detailaddress) AS address,a.tel,a.`name`,$expressPrice,'$orderNum' FROM sh_store_address a JOIN sh_store_area ar  ON a.countyid=ar.areaid  JOIN sh_store_area ar1 ON a.cityid=ar1.areaid JOIN sh_store_area ar2 ON a.provinceid=ar2.areaid WHERE id=$addressId AND a.userid=$userId AND delstate='N' limit 1";
		//new Log("","ssss".$sqlInToOrder);
		$effectRow=$Mysql->ExecuteSql($sqlInToOrder);
		//插入到sh_store_order表成功
		if($effectRow>0){
			//查询该订单的订单号
			$sqlSearchOrderId="SELECT orderid FROM sh_store_order WHERE ordernum='$orderNum' LIMIT 1 ";	
			//new Log(""," ad".$sqlSearchOrderId);
			$dataTableSearchOID=$Mysql->FetchData($sqlSearchOrderId);
			$row=count($dataTableSearchOID);
			//找到订单号的id
			if($row==1){
				$orderId=$dataTableSearchOID[0][0];
				//插入订单详细信息到sh_store_order_detail表
				$sqlInToOrderDetail="INSERT INTO sh_store_order_detail  (goodsid,price,orderid,num) SELECT c.gdetailid,d.dprice,$orderId,c.snum FROM sh_store_cart c JOIN sh_store_goods_detail d ON c.gdetailid=d.detailid WHERE c.id IN ($cartId) and c.userid=$userId ";
				//new Log("","详细".$sqlInToOrderDetail);
				$effectDetailRows=$Mysql->ExecuteSql($sqlInToOrderDetail);
				if($effectDetailRows>0){
					$sqlUpCart="UPDATE sh_store_cart SET state ='OFF' WHERE id IN ($cartId)";
					$orderLogSql="INSERT INTO sh_store_order_log  (logTime,orderID,userID,logType,sysid) VALUES ('$time',$orderId,$userId,'CREATE',0)";
					$Mysql->ExecuteSql($orderLogSql);
					$Mysql->ExecuteSql($sqlUpCart);
					
					return "OK";	
				}else{
					return "ORDERDETAIL_FALSE";
				}
			}else{
				return "ORDERID_SEARCH_FALSE";	
			}
			
		}else{
			return "ORDER_FALSE";	
		}
		
	}
	
	
	//订单付款
	public function orderPay($orderNum,$userId){
		if($userId==''){
			return "USERID_ERROR";	
		}
		$searchInfoSql="SELECT orderid,money,userid FROM sh_store_order  WHERE ordernum ='$orderNum' LIMIT 1";
		
		$Mysql=new MySqlHelper();
		
		
		$dataTableInfo=$Mysql->FetchData($searchInfoSql);
		//判断是否是用户本人的订单
		if($userId!=$dataTableInfo[0]['userid']){
			return "ORDER_USER_ERROR";
		}
		
		$money=$dataTableInfo[0]['money'];
		
		$orderId=$dataTableInfo[0]['orderid'];
		$result=Order::isShop($userId);
		
		if($result){
			$shopPayRreult= Order::shopPay($money,$result,$orderId);
			return $shopPayRreult;
			
		}else{
			
			$coustomPayResult=Order::coustomPay($money,$userId,$orderId);
			return $coustomPayResult;	
		}
		
		
		
		
	}
	//判断是否是商户，是商户则返回商户shopid，不是商户则返回false
	public function isShop($userId){
		$Mysql=new MySqlHelper();
		$sql="SELECT origin,tel FROM sh_coustom WHERE coustomid= $userId";
		$dataTable=$Mysql->FetchData($sql);
		$flag=$dataTable[0]['origin'];
		
		if($flag=='SHOP'){
			return $dataTable[0]['tel'];	
		}else{
			return false;	
		}
		
	}
	
	//商户付款
	private function shopPay($money,$shopid,$orderId){
		//查询账号余额和id
		$sqlSearch="SELECT accountID,balance  FROM sh_shop_account WHERE shopID=$shopid";
		$Mysql= new MySqlHelper();
		$dataSearch=$Mysql->FetchData($sqlSearch);
		$balance=$dataSearch[0]['balance'];
		$accountId=$dataSearch[0]['accountID'];
		
		//判断余额是否足够
		if($balance>=$money){
			$time=date('Y-m-d H:i:s',time());
			$Mysql->Query("BEGIN");
			//扣除小车券
			$sqlCutMoney="UPDATE sh_shop_account SET balance =balance-$money WHERE accountID=$accountId";
			$cutRow=$Mysql->ExecuteSql($sqlCutMoney);
			//失败则写log
			if($cutRow<1){
				new Log("","orderid为".$orderId."的订单扣券失败！");	
			}
			//插入扣小车券的记录sh_shop_account_log
			
			$sqlAccountLog="INSERT INTO sh_shop_account_log (orderid,balance,money,type,createtime) VALUES ($orderId,$balance,$money,'BUY','$time')";
			$logRow=$Mysql->ExecuteSql($sqlAccountLog);	
			if($logRow<1){
				new Log("","orderid为".$orderId."的插入扣券记录失败！");	
			}
			//更改订单的状态为已付款
			
			$Upresult=Order::UpOrderState($orderId,$money,$time,$money);
			if(!$Upresult){
				new Log("","orderid为".$orderId."的订单修改状态失败！");	
			}
			
			$numUpResult=Order::updateNums($orderId);
			if(!$numUpResult){
				$Mysql->Query("ROLLBACK");
				$Mysql->Query("END");	
				return "STOCK_NOT_ENOUGH";
			}
			
			if(($cutRow>0)&&($logRow>0)&&$Upresult){
				
				
				$Mysql->Query("COMMIT");
				$Mysql->Query("END");
				
				return "PAY_SUCCESS";	
			}else{
				$Mysql->Query("ROLLBACK");
				$Mysql->Query("END");	
				return "PAY_FAIL";
			}
			
				
		}else{
			return "NOT_ENOUGH";	
		}
		
	}
	//客户付款
	private function coustomPay($money,$coustomid,$orderId){
		
		
		//查询客户账户信息
		$Mysql=new MySqlHelper();
		$sqlSearch="SELECT coustomid,purseid,balance,elsebalance,rechargebalance,frozenmoney FROM sh_purse WHERE coustomid=$coustomid LIMIT 1";
		$dataSearch=$Mysql->FetchData($sqlSearch);
		$purseId=$dataSearch[0]['purseid'];
		$balance=$dataSearch[0]['balance'];
		$rechargebalance=$dataSearch[0]['rechargebalance'];
		$elsebalance=$dataSearch[0]['elsebalance'];
		$frozenmoney=$dataSearch[0]['frozenmoney'];
		$coustomid=$dataSearch[0]['coustomid'];
		//判断是否有券
		$dataCoupon = $Mysql->FetchData("SELECT cuponID,balance,cutoff FROM  sh_mycupon WHERE customerID=188 AND state='ON' order by createTime");
		if(count($dataCoupon)==0){
			return 'ON_COUPON';
		}
		//余额大于需要付款的小车券
		if($balance>$money){
			
			$time=date('Y-m-d H:i:s',time());
			$moneyPay=$money;
			$userShouldPay=0;	//用户提现余额需扣
			$companyPay=0;		//公司需付小车券
			//$i=0;	
					//用于给sql查询语句返回结果计数
			$Mysql->Query("BEGIN");
			
			foreach($dataCoupon as $Items){
				
				if($moneyPay>0){
					//计算该券所剩余额
					//new Log('','company='.$companyPay);	
					$couponBalance=$Items['balance']/(1-$Items['cutoff']);
					//如果剩余需要付款大于
					if($couponBalance<=$moneyPay){
						$sqlCut="UPDATE  sh_mycupon SET balance =0,state='OFF' WHERE cuponID=".$Items['cuponID'];
						$rowCut=$Mysql->ExecuteSql($sqlCut);
						if($rowCut<1){
							new Log("","couponID为".$Items['cuponID']."的券扣余额失败");
							//事务不符合完成条件，回滚数据
							$Mysql->Query("ROLLBACK");
							$Mysql->Query("END");
							return "PAY_FAIL";		
						}
						$sqlLog="INSERT INTO sh_store_coupon_log (orderid,couponid,balance,money,time,type) VALUES ($orderId,".$Items['cuponID'].",".$Items['balance'].",".$Items['balance'].",'$time','BUY') ";
						$rowLog=$Mysql->ExecuteSql($sqlLog);
						if($rowLog<1){
							new Log("","couponID为".$Items['cuponID']."的券插入记录失败");
							//事务不符合完成条件，回滚数据
							$Mysql->Query("ROLLBACK");
							$Mysql->Query("END");
							return "PAY_FAIL";		
						}
						$i++;
						$moneyPay=$moneyPay-$couponBalance;
						$companyPay=$companyPay+$Items['balance'];
						//new Log('','companyPay='.$companyPay);
					}else{
						//最后要扣的一张券扣除所需扣小车券额
						//new Log('','company='.$companyPay);
						$cutbalance=$moneyPay*(1-$Items['cutoff']);//公司需要付的小车券额
						$sqlCut="UPDATE  sh_mycupon SET balance =balance-$cutbalance WHERE cuponID=".$Items['cuponID'];
						$rowCut=$Mysql->ExecuteSql($sqlCut);
						if($rowCut<1){
							new Log("","couponID为".$Items['cuponID']."的券扣余额失败");
							//事务不符合完成条件，回滚数据
							$Mysql->Query("ROLLBACK");
							$Mysql->Query("END");
							return "PAY_FAIL";	
						}
						$sqlLog="INSERT INTO sh_store_coupon_log (orderid,couponid,balance,money,time,type) VALUES ($orderId,".$Items['cuponID'].",".$Items['balance'].",".$cutbalance.",'$time','BUY') ";
						$rowLog=$Mysql->ExecuteSql($sqlLog);
						if($rowLog<1){
							new Log("","couponID为".$Items['cuponID']."的券插入记录失败");
							//事务不符合完成条件，回滚数据
							$Mysql->Query("ROLLBACK");
							$Mysql->Query("END");
							return "PAY_FAIL";	
						}
						$moneyPay=0;
						$companyPay=$companyPay+$cutbalance;	
						//new Log('','companyPay='.$companyPay);	
						
						//new Log('','companyPay='.$companyPay);
						$companyPay=round($companyPay,2);
						$userShouldPay=$money-$companyPay;
						
						//new Log('','companyPay='.$companyPay);
						//new Log('','userShouldPay='.$userShouldPay);
						//避免rechargebalance 和elsebalance出现负数的情况 ---开始
						if($companyPay>$elsebalance){
							$companyPay=$elsebalance;
							new Log("","orderid为".$orderId."的订单号公司需付部分为".$companyPay."钱包额外余额为".$elsebalance);	
						}
						
						if($userShouldPay>$rechargebalance){
							$userShouldPay=$rechargebalance;
							new Log("","orderid为".$orderId."的订单号个人提现余额需付部分为".$userShouldPay."钱包提现余额为".$rechargebalance);		
						}
						//避免rechargebalance 和elsebalance出现负数的情况 ---结束
					
						break;
								
					}	  
				}else{
					//$money<=0开始
					/*$flag=true;
					
					for($j=0;$j<=$i;$j++){
						if(($rowCut[$j]<1)||($rowLog[$j]<1)){
							$flag=false;	
						}	
					}
					//如果扣券余额出错误，则终止整个事务，数据回滚
					if(!$flag){
						$Mysql->Query("ROLLBACK");
						$Mysql->Query("END");
						return "PAY_FAIL";	
						break;
						
					}*/
					//记得考虑 rechargebalance 或者 elsebalance不足的情况
				break;
				//$money<=0结束	
				}
				
			//foreach结束
			}
			
			//new Log("","companypay=".$companyPay."ssss---ssss  userShouldPay=".$userShouldPay);
			//更改订单的状态为已付款
			$Upresult=Order::UpOrderState($orderId,$money,$time,$userShouldPay,$companyPay);
			if(!$Upresult){
				new Log("","orderid为".$orderId."的订单修改状态失败！");	
				//事务不符合完成条件，回滚数据
				$Mysql->Query("ROLLBACK");
				$Mysql->Query("END");
				return "PAY_FAIL";
			}
			
			//减少库存，库存不足则回滚数据
			$numUpResult=Order::updateNums($orderId);
			new Log("","".$numUpResult);
			if(!$numUpResult){
				$Mysql->Query("ROLLBACK");
				$Mysql->Query("END");	
				return "STOCK_NOT_ENOUGH";
			}
			
			//扣款
			$Orderres=Order::cutPurse($userShouldPay,$companyPay,$purseId,$coustomid,$balance,$elsebalance,$frozenmoney,$time,$rechargebalance);
			
			return $this->_result_order;
			
		}elseif($balance==$money){
			$totalcouponBalance=0;
			
			foreach($dataCoupon as $Items){
				//计算该券所剩余额
				$totalcouponBalance=$totalcouponBalance+$Items['balance']/(1-$Items['cutoff']);
				
				$sqlCut="UPDATE  sh_mycupon SET balance =0,state='OFF' WHERE cuponID=".$Items['cuponID'];
				$rowCut[$i]=$Mysql->ExecuteSql($sqlCut);
				if($rowCut<1){
					new Log("","couponID为".$Items['cuponID']."的券扣余额失败");
					//事务不符合完成条件，回滚数据
					$Mysql->Query("ROLLBACK");
					$Mysql->Query("END");
					return "PAY_FAIL";	
					break;	
				}
				$sqlLog="INSERT INTO sh_store_coupon_log (orderid,couponid,balance,money,time,type) VALUES ($orderId,".$Items['cuponID'].",".$Items['balance'].",".$Items['balance'].",'$time','BUY') ";
				$rowLog[$i]=$Mysql->ExecuteSql($sqlLog);
				if($rowLog<1){
					new Log("","couponID为".$Items['cuponID']."的券插入记录失败");
					//事务不符合完成条件，回滚数据
					$Mysql->Query("ROLLBACK");
					$Mysql->Query("END");
					return "PAY_FAIL";	
					break;	
				}
				
				
			}
			
			if($totalcouponBalance+1>$money){
				$companyPay=$elsebalance;
				$userShouldPay=$rechargebalance;	
			}
			//更改订单的状态为已付款
			//new Log("","money==".$money);
			$Upresult=Order::UpOrderState($orderId,$money,$time,$userShouldPay,$companyPay);
			//new Log("","aa".$Upresult);
			if(!$Upresult){
				new Log("","orderid为".$orderId."的订单修改状态失败！");	
				//事务不符合完成条件，回滚数据
				$Mysql->Query("ROLLBACK");
				$Mysql->Query("END");
				return "PAY_FAIL";
			}
			
			//减少库存，库存不足则回滚数据
			$numUpResult=Order::updateNums($orderId);
			if(!$numUpResult){
				$Mysql->Query("ROLLBACK");
				$Mysql->Query("END");	
				return "STOCK_NOT_ENOUGH";
			}
			//扣款
		    Order::cutPurse($userShouldPay,$companyPay,$purseId,$coustomid,$balance,$elsebalance,$frozenmoney,$time,$rechargebalance);
			return $this->_result_order;	
			
			
		}else{
			return "NOT_ENOUGH";
		}
		
	}
	//更改订单状态
	private function UpOrderState($orderid,$money,$time,$countmoney,$discountmoney=0){
		$Mysql=new MySqlHelper();
		
		$OrderUpSql="UPDATE sh_store_order SET paytime='$time',paystate='Y',orderstate='WDELIVER',realpay=$money,countmoney=$countmoney ,discountmoney =$discountmoney   WHERE orderid=$orderid";
		//new Log("","sql==".$OrderUpSql);
		$row=$Mysql->ExecuteSql($OrderUpSql);
		if($row>0){
			return true;	
		}else{
			return false;	
		}
	}
	//扣款函数
	private function cutPurse($userShouldPay,$companyPay,$purseId,$coustomid,$balance,$elsebalance,$frozenmoney,$time,$rechargebalance){
		$Mysql=new MySqlHelper();
		$cutPruseSql="UPDATE sh_purse SET balance =balance-$userShouldPay-$companyPay ,rechargebalance=rechargebalance-$userShouldPay,elsebalance=elsebalance-$companyPay WHERE purseid=$purseId";
		
		$cutPruseRow=$Mysql->ExecuteSql($cutPruseSql);
		//插入钱包余额记录
		$inPruseLogsql="INSERT INTO sh_purse_log (userID,balance,elsebalance,rechargebalance,frozenmoney,createTime,logType) VALUES ($coustomid,$balance-$userShouldPay-$companyPay,$elsebalance-$companyPay,$rechargebalance-$userShouldPay,$frozenmoney,'$time','BUY') ";
		
		$inPruseRow=$Mysql->ExecuteSql($inPruseLogsql);
		//new Log("","".$inPruseRow.$cutPruseRow);
		
		//判断事务是否完成
		if(($cutPruseRow>0)&&($inPruseRow>0)){
			//事务符合完成条件，提交
			$Mysql->Query("COMMIT");
			$Mysql->Query("END");
			$this->_result_order= "PAY_SUCCESS";	
		}else{
			new Log("","cutPruseSql==".$cutPruseSql);
			new Log("","inPruseLogsql==".$inPruseLogsql);
			//事务不符合完成条件，回滚数据
			$Mysql->Query("ROLLBACK");
			$Mysql->Query("END");
			$this->_result_order= "PAY_FAIL";
			
		}

		
	}
	
	//减去已购商品数量并添加已售商品数量
	public function updateNums($orderid=0){
		$Mysql=new MySqlHelper();
		$where='';
		//两个条件满足一个就可以更改，orderid优先
		
		if($orderid<1){
			return false;
		}
		
		if($orderid>0){
			$where=" where o.orderid=$orderid ";
		}
		$sqlInfo="SELECT d.goodsid,d.num FROM sh_store_order o JOIN sh_store_order_detail d ON o.orderid=d.orderid ".$where;
		new Log("","".$sqlInfo);
		$dataInfo=$Mysql->FetchData($sqlInfo);
		$row=count($dataInfo);
		if($row>0){		
			foreach($dataInfo as $Items){
				$upsql="UPDATE sh_store_goods_detail SET dnum =dnum-".$Items['num']." ,salenum =salenum+".$Items['num']." WHERE detailid=".$Items['goodsid'];
				$upRow=$Mysql->ExecuteSql($upsql);
				if($upRow<1){
					new Log("","更改商品数量失败，sql===".$upsql);
					
					return false;
				}
	
			}
			return true;
		}else{
			return false;	
		}
		
		
		
	}	
	
}

