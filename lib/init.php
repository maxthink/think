<?php

define("BASEDIR", dirname(__FILE__));
include(BASEDIR . '/mysqli.php');
include(BASEDIR . '/pager.php');

//本地数据库连接配置
define("DB_SERVER", "localhost");
define("DB_USERNAME", "aizhuanbao");
define("DB_PASSWORD", "auje86ynj");
define("DB_DBNAME", "aizhuanbao");

$db = new lib_mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DBNAME );


define("PRODUCT_KEY","442a7704bd4c7fc424e844dc85bd141d");	//数据通信秘钥

define('RATE',10000);	//   积分/金钱 比率

define('REWARD_P',10);	//给邀请者奖励分成, 10就是10%
define('REWARD_PP',5);	//给爷爷辈邀请者奖励分成, 5%

/*客户端各个广告平台分配id
	public final static int platform_o2o 			= 11;	//欧拓	(server)
	public final static int platform_youmi 			= 12;	//有米	(server)
	public final static int platform_yinggao 		= 13;	//赢告无限	(server)
	public final static int platform_duomeng 		= 14;	//多盟
	public final static int platform_qumi 			= 15;	//趣米	客户端发送积分
	public final static int platform_wanpu 			= 16;	//万普	客户端发送积分
	public final static int platform_dianjoy 		= 17;	//点乐	(server)
	public final static int platform_guomeng 		= 18;	//果盟	(server)
	public final static int platform_adcocoa		= 19;	//adcocoa	(server)
	public final static int platform_appjoy 		= 20;	//appjoy (server)
*/
$platform_sign		= 0;	//每日签到收入
$platform_share		= 1;	//每日分享收入


define('INVITE_P',8);	//'邀请奖励',
define('INVITE_PP',9);	//'二级邀请奖励',

$platform_o2o		= 11;	//欧拓		(server)
$platform_youmi		= 12;	//有米		(server)
$platform_yinggao	= 13;	//赢告无限	(server)
$platform_duomeng	= 14;	//多盟		客户端发送积分
$platform_qumi		= 15;	//趣米		客户端发送积分
$platform_wanpu		= 16;	//可可		(server)
$platform_dianjoy	= 17;	//点乐		(server)
$platform_guomeng	= 18;	//果盟		(server)
$platform_appjoy	= 19;	//appjoy	(server)


/* 添加收益记录(z_income_record) ,项目此处只会增加收益
*	
*	$platform	int	渠道id
*	$platform_record_id int  渠道记录表id
*	$uid		int		用户编号
*	$income		int		收益
*	$wargin		boolean true,警告，时间不对；false,请求时间ok
*	
*	return boolean
*/
function add_order($plat, $plat_record_id, $uid, $score, $descript='', $quest_time=0 ){


	//$uid = getUserIdByImei($imei);
	if(!$uid){
		$sql = "insert app_user set imei='$imei', timeline=".$_SERVER['REQUEST_TIME'];
		$uid = insert($sql);
	}
	if($descript==''){
		$descript = get_desc($platform);
	}

	//服务器积分回调的不需要 request_time
	$sql = "insert into app_user_cpa set uid=$uid, plat=$plat, plat_record_id=$plat_record_id, score=$score, timeline=".$_SERVER['REQUEST_TIME'];
	//echo $sql;
	$inid = insert($sql);

	//更新用户做任务的记录数
	$sql = "update app_user set task_count=task_count+1 where uid=$uid ";
	query($sql);
	
	addRecord($uid, $score, $descript,$plat );	//添加用户记录

	//奖励积分
	$sql = 'select v_id,vv_id from app_user where uid='.$uid;
	$pinfo = getrow($sql);
	if($pinfo['v_id'])
	{
		$p_score = floor($score*10/100);
		//echo 'p_score:'.$p_score;
		if($p_score>0){

			addRecord($pinfo['v_id'], $p_score, '邀请奖励', INVITE_P );	//给上级添加 邀请奖励 记录
			$sql = 'insert app_user_devote set v_id='.$pinfo['v_id'].', p_score='.$p_score;
			if($pinfo['vv_id'])
			{
				$pp_score = floor($score*5/100);
				if($pp_score>0)
				{
					addRecord($pinfo['vv_id'], $pp_score, '二级邀请奖励', INVITE_PP );	//给上上级添加 邀请奖励
					$sql .= ', vv_id='.$pinfo['vv_id'].', pp_score='.$pp_score;
				}
			}

			$sql .= ', uid='.$uid.', income_id='.intval($inid).', timeline='.$_SERVER['REQUEST_TIME'];

			insert($sql);

		}

	}

}


//添加记录,增加收入
function addRecord($uid, $score, $descript, $plat='' )
{

	$sql = 'insert app_user_record set uid='.$uid.', score='.$score.', descript=\''.$descript.'\', plat='.$plat.', timeline='.$_SERVER['REQUEST_TIME'];
	insert($sql);
	$sql = "update app_user set score=score+$score, total=total+$score where uid=$uid ";
	query($sql);
}


/**
 * 密钥验证
 * @param string $type 接口标识
 * @param string $key 传入的密钥
 * @return bool true－密钥正确；false－密钥错误
 */
function checkKey($type, $token, $param) {
	switch ($type) {
		case 'index':   //首页			
			return $token == md5($param['imei'] . 'aic');
			break;
		case 'exchange':                   //提现
			return $token == md5(PRODUCT_KEY . $param['imei'] . $param['time']);
			break;
		case 'income':                      //收入
			return $token == md5( PRODUCT_KEY . $param['imei'] . $param['time']);
			break;
		case 'share':   //分享接口
			return true;
			break;
		case 'sign':   //签到
			return true;
			break;
		case 'get_version':
			return true;
			break;
	}
}



function get_desc($platform){

	global $config_platform;

	switch($platform){
		case 0:
			return '签到';
		break;
		case 1:
			return '分享';
		break;
		case 14:
			return '多盟积分';
		break;
		case 15:
			return '趣米积分';
		break;
	}
}






function query($sql=''){
	if($sql!=''){
		global $db;
		$db->query($sql);
		return true;
	}else{
		return false;
	}
	
}

//数据库插入数据方法
function insert($sql=''){
	if($sql!=''){
		global $db;
		$db->query($sql);
		$inid = $db->insert_id();
		if($inid){
			return $inid;
		}else{
			return false;
		}
	}else{
		return false;
	}
	
}


//数据库获取一个值
function getone($sql=''){
	if($sql!=''){
		global $db;
		return $db->get_one($sql);
	}else{
		return false;
	}
}


function getrow($sql=''){
	if($sql!=''){
		global $db;
		return $db->get_row($sql);
	}else{
		return false;
	}
}

function getall($sql=''){
	if($sql!=''){
		global $db;
		return $db->get_all($sql);
	}else{
		return false;
	}
}


//获取广告平台信息
function getPlat(){
	
	$set_plat = getCache('app_plat');
	if($set_plat==false)
	{
		$sql = 'select id,name,color,plat_id,allow_num from app_plat where status=1 order by ord asc';
		$res = getall($sql);
		//addCache('app_plat', $res, 43200);
		return $res;
	}else
	{
		return $set_plat;
	}

}

//获取广告平台信息
function getSet($version=1,$channel=''){
	
	$app_set = getCache('app_set');
	if($app_set==false)
	{
		$sql = 'select * from app_set where status=1 ';
		$res = getall($sql);
		addCache('app_set', $res);
		return $res;
	}else
	{
		return $app_set;
	}

}

//任务已完成次数
function getDidCount($uid){

	$today = strtotime(date('Y-m-d'));
	$sql = 'SELECT plat,count(*) tc FROM `app_user_cpa` where uid='.$uid.' and timeline>'.$today.' GROUP BY plat';
	return getall($sql);
}


/*	通过imei号获取用户信息
*	使用地方: 前端接口获取分数
*	$imei 手机imei号
*	return array boolean  如果没有数据, 则返回false
*/
function getUserByImei($imei=''){
	
	if($imei!==''){
		$sql = "select * from app_user where imei='$imei' ";
		return getrow($sql);
	}else{
		return false;
	}

}

function getUserIdByImei($imei='')
{
	if($imei!=='')
	{
		$sql = "select uid from app_user where imei='$imei' ";
		return getone($sql);
	}else{
		return false;
	}

}

function getUserById($uid='')
{
	if($uid!==''){
		$sql = 'select * from app_user where uid=' . $uid;
		return getrow($sql);
	}else{
		return false;
	}
}

/*	通过用户编号获取当天积分
*	使用地方: 前端接口获取分数
*	$uid 
*	return num 当天赚的积分
*/
function getTodayScore($uid){
	if($uid!==''){

		$todaytime = strtotime(date('Y-m-d'));
		$sql = 'select sum(score) from app_user_record where uid='.$uid.' and timeline>'.$todaytime. ' and score>0 ' ;	//score 必须大于0，因为有负的， 负的是提现

		$todayScore = getone($sql);
		if($todayScore){
			return $todayScore;
		}else{
			return 0;	
		}
		
	}else{
		return 0;
	}
}

/*	
*	获取邀请码
*	return string
*/
function getInviteCode(){
	

	$go = false;
	do{
		$code = generate_rand(6);

		$sql = 'select invitecode from app_user where invitecode=\''.$code.'\'';
		$ok = getone($sql);
		if($ok){
			$go = true;
		}else{
			return strtoupper($code);
		}
	}while($go);

}

/**
* 生成随机数字
*
*/
function generate_rand($l)
{ 
	$c= "abcdefghijklmnopqrstuvwxyz0123456789"; 
	srand((double)microtime()*1000000); 

	$rand= '';
	for($i=0; $i<$l; $i++) 
	{ 
		$rand.= $c[rand()%strlen($c)]; 
	} 
	return strtolower($rand); 
}


function addCache($name, $value, $expire=0)
{
	$memc = new Memcache;
	$memc->connect('localhost', 11211);

	return $memc->set($name, $value, false, $expire);

}

//功能性方法
function getCache($name)
{
	$memc = new Memcache;
	$memc->connect('localhost', 11211);
	return $memc->get($name);
}

function getRedis()
{
    try {
        $r = new Redis();
        $r->connect('127.0.0.1', 6379);
        return $r;
    } catch (Exception $ex) {
        log('redis : '.$ex->getMessage() );
        return false;
        
    }
}

function log($msg){
    file_put_contents('log.html', date().' '.$msg , FILE_APPEND );
}

?>
