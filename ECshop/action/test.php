<?
/*$conn = mysql_connect('localhost','root','') or die ("数据连接错误!!!");
mysql_select_db('jfycarlife',$conn);
mysql_query("set names 'utf8'"); //使用GBK中文编码;
//开始一个事务
mysql_query("BEGIN"); //或者mysql_query("START TRANSACTION");
$sql = "INSERT INTO test  (datatime,znum,onenum) VALUES ('2015-12-29',2,6) ";
$sql2 = "INSERT INTO test  (datatime,znum,onenum) VALUES ('2015-12-29',8,9) ";//这条我故意写错
$res = mysql_query($sql);
$res1 = mysql_query($sql2);
if($res && $res1){
mysql_query("COMMIT");
echo '提交成功。';
}else{
mysql_query("ROLLBACK");
echo '数据回滚。';
}
mysql_query("END"); */


include_once '../../Bin/MySqlHelper.class.php';

$Mysql=new MySqlHelper();
$Mysql->Query("BEGIN");
$row=0;
$row=$Mysql->ExecuteSql("INSERT INTO test  (datatime,znum,onenum) VALUES ('2015-12-29',2,6)");
$row2=test();

if(($row>0)&&($row2>0)){

$Mysql->Query("COMMIT");
echo "提交成功";
}else{
$Mysql->Query("ROLLBACK");	
echo "数据回滚";
}
$Mysql->Query("END");


function test(){
	$Mysql=new MySqlHelper();
	return $row2=$Mysql->ExecuteSql("INSERT INTO test  (datatime,znum,onenum) VALUES ('2015-12-29',2,6)");
}


?>