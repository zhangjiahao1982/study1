<?php
/**
 * wage base  Manage
 * @date 2016/11/17
 * @author zw
 */
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_class('phpExcel', 'admin',0);

class wage_manage extends admin {
	protected $passport_type;
	function __construct() {
		//var_dump("asdf");die;
		parent::__construct();
		$this->db 				= pc_base::load_model('wage_model');
		$this->employee_db 		= pc_base::load_model('employee_infomation_model');
		$this->wage_project_db 	= pc_base::load_model('wage_project_model');
		$this->spoc_append_db	= pc_base::load_model('spoc_append_model');
		$this->wage_log_db		= pc_base::load_model('wage_log_model');
        $this->shuiwu			= pc_base::load_model('shuiwu_model');
		$this->siteid 			= $this->get_siteid();
		$this->passport_type 	= array('身份证'=>1, '护照' =>2,'1'=>'身份证', '2' =>'护照');
	}

	/**
	 * defalut
	 */
	function init(){
		$year = isset($_GET['year']) ? $_GET['year'] : '';
		$month = isset($_GET['month']) ? $_GET['month'] : '';
		$job_number = isset($_GET['job_number']) ? $_GET['job_number'] : '';
		$spoc_name = isset($_GET['spoc_name']) ? $_GET['spoc_name'] : '';
		$oad_name = isset($_GET['oad_name']) ? $_GET['oad_name'] : '';
		$provinceid = isset($_GET['provinceid']) ? $_GET['provinceid'] : '';
		$cityid = isset($_GET['cityid']) ? $_GET['cityid'] : '';
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : date('Y-m-d', SYS_TIME);	
		//search
		$provinceInfos = GetProvince();
		
		$page = max(intval($_GET['page']), 1);
		$year = date("Y");
		$month = date("m");
		$where ="year = '$year' and month='$month'";
		$spoc_data = $this->db->listinfo($where, 'id DESC', $page, 20);

		if(empty($spoc_data)){
			if($month>1){
				$month = $month-1;
			}elseif($month=1){
				$month = 12;
				$year = $year-1;
			}	
			$spoc_data = $this->db->listinfo($where, 'id DESC', $page, 20);
		}
		
		if(isset($spoc_data) && !empty($spoc_data)){
			// 获取当月所有项目
			$project_Data = $this->wage_project_db->getAllProject($year, $month, $fild="id,pro_name,pro_code,project_leader");
			foreach($spoc_data as $k=>$r){
				// $获取人员信息
				$employee_Data = $this->employee_db->getAllEmployee($user_id = $r['user_id'], $fild="id,name,passport_type,passport_number,mobile");

				$pro['pro_name'] 		= empty($project_Data) ? "" : $project_Data[$r['pro_id']]['pro_name'];
				$pro['pro_code'] 		= empty($project_Data) ? "" : $project_Data[$r['pro_id']]['pro_code'];
				$pro['pro_leader']		= empty($project_Data) ? "" : $project_Data[$r['pro_id']]['project_leader'];
				// 人员信息
				$pro['name']			= empty($employee_Data) ? "" : $employee_Data[$r['user_id']]['name'];
				$pro['passport_type']	= empty($employee_Data) ? "" : $this->passport_type[$employee_Data[$r['user_id']]['passport_type']];
				$pro['passport_number']	= empty($employee_Data) ? "" : $employee_Data[$r['user_id']]['passport_number'];
				$pro['mobile']			= empty($employee_Data) ? "" : $employee_Data[$r['user_id']]['mobile'];
				$r['addtime']			= date("Y-m-d H:i:s", $r['addtime']);

				//var_dump($project_Data[$r['pro_id']]);
				$spoclist[] = array_merge($r, $pro);

				$spoc_data[$k] = null;
			}

		}

		$spocCount = $this->db->count($where);
		$pages = $this->db->pages;
		//$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=admin&c=wage_manage&a=add\', title:\''.L('spoc_add').'\', width:\'700\', height:\'600\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('spoc_add'));
		
		$import_menu = array('javascript:window.top.art.dialog({id:\'import\',iframe:\'?m=admin&c=wage_manage&a=import\', title:\''.L('import_spoc_payroll').'\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'import\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'import\'}).close()});void(0);', L('import_spoc_payroll'));
		
		//$export_menu = array('javascript:window.top.art.dialog({id:\'export\',iframe:\'?m=admin&c=wage_manage&a=export\', title:\''.L('export_spoc_payroll').'\', width:\'600\', height:\'300\', lock:true}, function(){var d = window.top.art.dialog({id:\'export\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'export\'}).close()});void(0);', L('export_spoc_payroll'));

		$importall_menu = array('javascript:window.top.art.dialog({id:\'import\',iframe:\'?m=admin&c=wage_manage&a=import&bonus=1\', title:\'导入奖金\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'import\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'import\'}).close()});void(0);', '导入奖金');

		$export_menu = array('javascript:window.top.art.dialog({id:\'import\',iframe:\'?m=admin&c=wage_manage&a=export\', title:\'导出工資\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'import\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'import\'}).close()});void(0);', '导出工資');

		include $this->admin_tpl('wage/wage_list');
	}
	
	/**
	 * SPOCSalarySheetSearch
	 */
	function search() {
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$year = isset($_GET['year']) ? $_GET['year'] : '';
		$month = isset($_GET['month']) ? $_GET['month'] : '';
		$project_code = isset($_GET['project_code']) ? $_GET['project_code'] : '';
		$employee_name = isset($_GET['employee_name']) ? $_GET['employee_name'] : '';
		$mobile = isset($_GET['mobile']) ? $_GET['mobile'] : '';
		$passport_number = isset($_GET['passport_number']) ? $_GET['passport_number'] : '';
		$city_name = isset($_GET['city_name']) ? $_GET['city_name'] : '';
		
		//search data
				
		if (isset($_GET['search'])) {
			/*$where_start_time = strtotime($start_time) ? strtotime($start_time) : 0;
			$where_end_time = strtotime($end_time) + 86400;
			//开始时间大于结束时间，置换变量
			if($where_start_time > $where_end_time) {
				$tmp = $where_start_time;
				$where_start_time = $where_end_time;
				$where_end_time = $tmp;
				$tmptime = $start_time;
				
				$start_time = $end_time;
				$end_time = $tmptime;
				unset($tmp, $tmptime);
			}*/
			
			$where = '';
			if(isset($year)) {
				$where .= "a.`year` = '$year' AND ";
			}
			if(isset($month)) {
				$where .= "a.`month` = '$month' AND ";
			}
			if($project_code) {
				$where .= "pro.`pro_code` = '$project_code' AND ";
			}

			if($mobile) {
				$where .= "mobile = '$mobile' AND ";
			}
			if($passport_number) {
				$where .= "emp.`passport_number` = '$passport_number' AND ";
			}
			if($city_name) {
				$where .= "`city_name` = '$city_name' AND ";
			}

			if($employee_name) {
				$where .= "emp.name like '%$employee_name%' AND ";
			}
			$where .= "1=1";
			
		} else {
			$where = '';
		}

		$sql = "SELECT 
					a.* ,emp.name,emp.mobile,pro.pro_code,pro.pro_name,pro.project_leader as pro_leader 
				 FROM 
					v9_wage as a 
				RIGHT JOIN 
					v9_wage_project as pro
				 ON 
				 	a.pro_id = pro.id AND a.year = pro.year AND a.month = pro.month 
				RIGHT JOIN 
					v9_employee_infomation as emp 
				 ON 
				 	a.user_id = emp.id 
				 WHERE 
				  $where 
				  GROUP BY pro_id,a.id 
				  ORDER BY a.id DESC";
		 $countSql = "SELECT 
							count(a.id) as num
						 FROM 
							v9_wage as a 
						RIGHT JOIN 
							v9_wage_project as pro
						 ON 
						 	a.pro_id = pro.id AND a.year = pro.year AND a.month = pro.month 
						RIGHT JOIN 
							v9_employee_infomation as emp 
						 ON 
						 	a.user_id = emp.id
						 WHERE 
						  $where";
		$count = $this->db->fetch_array($this->db->query($countSql));
		$spocCount = $count[0]['num'];
		$data = $this->db->fetch_array($this->db->query($sql));
		$countSql=$sql = $count = null;
		if(!empty($data)){
			foreach($data as $r){
				$r['addtime'] = date("Y-m-d H:i:s", $r['addtime']);
				$spoclist[] = $r;
			}
		}

		$big_menu = array('?m=admin&c=wage_manage&a=init', L('research'));
		include $this->admin_tpl('wage/wage_list');
	}
	
	/**
	*  edit
	*/
	function edit(){
	    $id = trim($_GET['id']);
		if (isset($_POST['dosubmit']) && !empty($_POST['dosubmit'])) {
			$update = $this->db->update($_POST['info'],array('id'=>$id));
			if($update==true){
			    showmessage('operation_success', '', '120', 'edit');
			}
		}else{
			$provinceInfos = GetProvince();
			$show_header = $show_validator = true;
		    $data = $this->db->get_one(array('id'=>$id));
		}
	    include $this->admin_tpl("spoc_wage_edit");
	}
	
	
	/**
	 * import SPOCSalarySheet 
	 */
	function import() {
		$objPHPExcel = new PHPExcel();
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip; 
		$cacheSettings = array(); 
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
		$is_bonus = isset($_GET['bonus']) ? $_GET['bonus'] : '-1';
		$user = param::get_cookie('admin_username');
		// var_dump($_POST['dosubmit']);die;
		session_start();
		if($_SESSION['is_import'] != '-1'){
			$_SESSION['is_import'] = '9';
		}
		if(isset($_POST['dosubmit'])) {
			// $_SESSION['is_import'] = '';die;
			//die("系统维护");
			

			unset($is_bonus);
			$is_bonus = isset($_POST['bonus']) ? $_POST['bonus'] : '-1';
			if(empty($_FILES["inputExcel"]["tmp_name"])){
				showmessage(L('select_upload_file'), HTTP_REFERER);exit;
			}

			$is_import = $is_bonus;

			$year = $_POST['year'];
			$month = $_POST['month'];
			$company = $_POST['company'];
			if ($company == 0) {
				showmessage("请选择公司,谢谢!",HTTP_REFERER);
			}
			switch ($company) {
				case '1':
					$code = 'A';
					break;
				case '2':
					$code = 'C';
					break;
				case '3':
					$code = 'B';
					break;
				case '4':
					$code = 'D';
					break;
				case '5':
					$code = 'E';
					break;
			}
			$year = date("Y");
			if ($user !='phpcms') {
				$month = date("m");
			}

			 $objPHPExcel = PHPExcel_IOFactory::load($_FILES["inputExcel"]["tmp_name"]);
			//内容转换为数组 
			$indata = $objPHPExcel->getSheet(0)->toArray();
			if ($user =='phpcms') {
				// echo time();
				// var_dump($indata);die;
			}
			//var_dump($indata);die;
			array_shift($indata);

			//print_r($indata);die;
			$countNum = 0;
			$pro_id= false;
			$pro_temp = false;

			if($indata){
				//根据工号判断导入的数据是否重复
				foreach ( $indata as $k => $v){
					if (empty($v[0])) {
						unset($indata[$k]);
						continue;
					}
					$error = array();
					++$countNum;
					$count = $k+1;
					$count = ++$count;

					// 去除空元素 空格
					if(empty($v[0]) || empty($v[1])) {
						unset($indata[$k]);
						continue;
					}

					$temp = array_slice($v, 0, 24);

					// 过滤空格
					foreach ($temp as $key => $value) {
						if($key != 1) $temp[$key] = self::trimall($value);
					}

					######################################  数据验证 star   #################################
					if(!$pro_temp && $pro_temp != $v[0]) {
						$pro_temp = $v[0];
						// 验证项目是否已经上传
						$rel = $this->wage_project_db->select(array('pro_code'=>$v[0], 'year'=>$year, 'month' =>$month));
						
						if (!empty($rel)) {

							// 验证项目是否有工资条 当月项目编号只能存在一次---妍妍定的 2017/05/26
							$tempCount = $this->db->count(array('pro_id'=>$rel[0]['id'],  'year'=>$year, 'month' =>$month/*, 'is_bonus' => $is_bonus*/));
							if($tempCount > 0) {
								$_SESSION['is_import'] = '';
								showmessage("上传失败，项目已经存在",  HTTP_REFERER, 80000);break;
							}
						}
					}
					// 验证职位名称是否为空
					if (empty($temp[5])) {
						$error[] = '列表'.$count.'行，'.$temp[6].'职位不能为空，请填写对应职位（促销员或者助督）';
						//showmessage(L('project_code').$temp[0]."，职位不能为空，请填写对应职位（促销员或者助督）", HTTP_REFERER, 80000);break;
					}
					// 验证顾问姓名、证件号、银行账号、手机号 是否为空
					if ( empty($temp[6])) {
						$error[] = '列表'.$count.'行，'.$temp[6].'顾问姓名不能为空';
						//showmessage(L('project_code').$temp[0]."，顾问姓名不能为空，请仔细核对信息", HTTP_REFERER, 80000);break;
					}

					// 验证实发工资不为空
					if (empty($temp[16])) {
						$error[] = '列表'.$count.'行，'.$temp[6].'实发工资不能为空（或者模版有问题）';
					}

					if ( empty($temp[18])) {
						$error[] = '列表'.$count.'行，'.$temp[6].'身份证号不能为空';
						//showmessage(L('project_code').$temp[0].','.L('employee_name').$temp['6']." ，身份证号不能为空，请仔细核对信息", HTTP_REFERER, 80000);break;
					} else {
						// 根据证件类型 验证证件号码是否正确
						if (in_array($temp [17], array('身份证', '护照'))) {
							$len = strlen($temp[18]);
							if ($temp[17] == "身份证") {
								if ($len != 18) {
									$error[] = '列表'.$count.'行，'.$temp[6].'身份证号信息错误';
									//if( $len != 15 ) showmessage(L('project_code').$temp[0].','.L('employee_name').$temp['6']." ，身份证号信息错误，请仔细核对信息", HTTP_REFERER, 80000);break;
								} else {
								    //这里进行身份证信息和税务身份信息的验证
                                    $sfz_code = $temp[18];//工资单身份证编号
                                    $sfz_name = trim($temp[6]);//工资单身份证姓名
                                    $where = " u_shenfen = '".$sfz_code."' ORDER BY id DESC LIMIT 1";
                                    $shuiwu_name = $this->shuiwu->select("$where", 'u_name');
                                    if(count($shuiwu_name)!=0){
                                        if($shuiwu_name[0]['u_name'] != $sfz_name){
                                            $error[] = '列表'.$count.'行，'.$temp[6].'身份证信息与数据库内税务信息不对应';
                                        }
                                    }
                                }
							} else {
								if ($len != 8 && $len != 9) {
									$error[] = '列表'.$count.'行，'.$temp[6].'身份证号信息错误';
									//showmessage(L('project_code').$temp[0].','.L('employee_name').$temp['2']." ，身份证号信息错误，请仔细核对信息", HTTP_REFERER, 80000);break;
								}
							}
						} else {
							$error[] = '列表'.$count.'行 ，'.$temp[6].'证件类型只能为身份证或者护照';
							//showmessage(L('project_code').$temp[0].','.L('employee_name').$temp['6']." ，证件类型只能为身份证或者护照，请仔细核对信息", HTTP_REFERER, 80000);break;
						}
					}

					// 验证银行账号
					if ( in_array($temp[20], array('现结', '暂扣', '招行', '建行','中行','工行','农行'))) {
						if ( in_array($temp[20], array('现结', '暂扣'))/*$temp[20] == '现结' || $temp[20] == '暂扣' */) {
							$temp [19] = 0;
						} else {
							//验证开户行全称是否为空
							if (empty($temp[22])) {
								$error[] = '列表'.$count.'行 ，'.$temp[6].'开户行全称不能为空';
							} 
							$len = strlen($temp[19]);
							if ($temp[20] == '招行') {
								if ( $len != 16) {
									$error[] = '列表'.$count.'行 ，'.$temp[6].'银行账户不正确';
									//showmessage(L('project_code').$temp[0].','.L('employee_name').$temp['6']." ，银行账户不正确，请仔细核对信息", HTTP_REFERER, 80000);break;
								}
							} else {
								if ( !in_array($len, array(19,16))/*$len != 19 || $len != 16*/) {
									$error[] = '列表'.$count.'行 ，'.$temp[6].'银行账户不正确';
									//showmessage(L('project_code').$temp[0].','.L('employee_name').$temp['6']." ，银行账户不正确，请仔细核对信息", HTTP_REFERER, 80000);break;
								}
							}
						}
						
					} else {
						$error[] = '列表'.$count.'行 ，'.$temp[6].'开户行名称不符合标准（招行，建行，暂扣，现结)';
						//showmessage(L('project_code').$temp[0].','.L('employee_name').$temp['6']." ，开户行名称不符合标准（招行，建行，暂扣，现结)，请仔细核对信息", HTTP_REFERER, 80000);break;
					}

					// 验证手机号
					if ( empty($temp[21])) {
						$error[] = '列表'.$count.'行 ，'.$temp[6].'手机号不能为空';
						//showmessage(L('project_code').$temp[0].','.L('employee_name').$temp['6']." ，手机号不能为空，请仔细核对信息", HTTP_REFERER);break;
					} else {
						$len = strlen($temp[21]);
						if($len != 11) {
							/*if ($user=='phpcms') {
								$_SESSION['is_import'] = '';
								echo "<pre>";
								echo $len;
								var_dump($indata);
								die;
							}*/
							$error[] = '列表'.$count.'行 ，'.$temp[6].'联系方式只能为手机号，手机号不对';
							//showmessage(L('project_code').$temp[0].','.L('employee_name').$temp['6']." ，联系方式只能为手机号，手机号不对，请仔细核对信息", HTTP_REFERER, 80000);break;

						}
					}
					//=验证银行所在省份===============
					if ( empty($temp[23])) {
						$error[] = '列表'.$count.'行 ，'.$temp[6].'银行所在省份不能为空';
						//showmessage(L('project_code').$temp[0].','.L('employee_name').$temp['6']." ，手机号不能为空，请仔细核对信息", HTTP_REFERER);break;
					}
					#################### 统一错误提示  #########################
					if (!empty($error)) {
						$_SESSION['is_import'] = '';
						showmessage(L('project_code').$temp[0].":\r\n<br>&nbsp;&nbsp;&nbsp;&nbsp;".implode("\r\n<br>&nbsp;&nbsp;&nbsp;&nbsp;", $error), HTTP_REFERER, 80000);break;
					}
//print_r($temp);die;
					#########################    数据验证  end   #################################
					$temp[1] = addslashes($temp[1]);
					$indata[$k] = $temp;
				}
				
				// 定义数组 验证uid重复

				$uidArr = array();
				foreach($indata as $k => $r){
					if (empty($r[0])) {
						unset($indata[$k]);
						continue;
					}
					######################## 顾问基本信息处理   star ############################

					 // 判断基本信息是否存在
					 $result = $this->employee_db->get_one(array('passport_number' => $code.$r[18], 'year'=>$year, 'month'=>$month/*, 'status' => '1'*/), 'id,name,mobile');
					 $rel = $this->employee_db->get_one(array('mobile' => $r[21], 'year'=>$year, 'month'=>$month/*, 'status' => '1'*/), 'id,name');

					 if (!empty($result)) {

					 	//array_push($indata[$k], $result['id']);
					 	$sql = "SELECT 
					 				a.project_leader as project_leader,a.pro_code as pro_code
					 			 FROM 
					 			 	v9_wage_project as a
					 			  JOIN 
					 			 v9_wage as b 
					 			  ON 
					 			  	a.id = b.pro_id AND a.status = b.status
					 			  WHERE 
					 			  	a.status=1 AND b.user_id ='".$result['id']."' ORDER BY b.id DESC limit 1";
					 	$relt = $this->db->fetch_array($this->db->query($sql));
					 	if(trim($result['name']) != trim($r[6])) {
					 			
					 		$_SESSION['is_import'] = '';
					 		/*if($user =='phpcms'){
					 			echo "<pre>";
					 			var_dump($result['name']);
					 			echo "<br>";
					 			var_dump($r[6]);
					 			die;
					 		}*/
					 		//showmessage(L('project_code').$r[0].','.L('employee_name').'：'.$r['6'].",".L('passport_number').': '.$r['18']." ，顾问姓名:<span style='color:red'>".$r['6']."</span>与已有员工 <span style='color:red'>".$result['name']." </span>信息不匹配，请联系项目负责人：<span style='color:red'> ".$relt[0]['project_leader']."</span>, 项目编码：<span style='color:red'> ".$relt[0]['pro_code']."</span>，可能身份证信息错误。请仔细核对信息进行修改。", HTTP_REFERER, 80000);die;
					 	} else {
					 		if ($result['mobile'] != $r['21']) {
					 			//showmessage(L('project_code').$r[0].','.L('employee_name').'：'.$r['6'].",".L('passport_number').': '.$r['18']." ，<span style='color:red'>手机号</span>与已有员工<span style='color:red'>".$result['name']."</span>信息不匹配，请联系项目负责人：<span style='color:red'> ".$relt[0]['project_leader']."</span>, 项目编码：<span style='color:red'> ".$relt[0]['pro_code']."</span>，请仔细核对信息进行修改。", HTTP_REFERER, 80000);break;
					 		}
					 		//array_push($indata[$k], $result['id']);
					 	}
					 	$indata[$k][6] = $result['id'];
					 	unset($relt);
					 } else {

					 	// 判断相同手机号 是否多人使用
						/*if(!empty($rel)) {
							array_push($indata[$k], $rel['id']);
							// 判断 手机号是否多人使用
							if ($rel['name'] != $r[6]) {
						 	$sql = "SELECT 
						 				a.project_leader as project_leader,a.pro_code as pro_code
						 			 FROM 
						 			 	v9_wage_project as a
						 			  JOIN 
						 			 v9_wage as b 
						 			  ON 
						 			  	a.id = b.pro_id AND a.status = b.status
						 			  WHERE 
						 			  	a.status=1 AND b.user_id ='".$rel['id']."' ORDER BY b.id DESC limit 1";
						 	$relt = $this->db->fetch_array($this->db->query($sql));
								//showmessage(L('project_code').$r[0].','.L('employee_name').'：'.$r['6'].",".L('mobile').': '.$r['21']." ，<span style='color:red'>手机号</span>与已有员工".$rel['name']."相同，请联系项目负责人：<span style='color:red'> ".$relt[0]['project_leader']."</span>, 项目编码：<span style='color:red'> ".$relt[0]['pro_code']."</span>，请仔细核对信息进行修改。", HTTP_REFERER, 80000);break;
							}
						}*/
					 	$newdata = array();
					 	$str_key = md5(uniqid(rand()));
					 	$uid = substr($str_key, 0, 32);
					 	$newdata = array(
					 				'id'				=> $uid,
					 				'name' 				=> trim($r[6]),
					 				'passport_type' 	=> $this->passport_type[$r[17]],
					 				'passport_number'	=> $code.trim($r[18]),
					 				'mobile'			=> trim($r[21]),
					 				'year'				=> $year,
					 				'month'				=> $month,
					 				'addtime'			=> SYS_TIME,
					 				'opearater'			=>$user
					 			);

					 	$rel = $this->employee_db->insert($newdata);
					 	if($rel) {
					 		$sql = "SELECT id from v9_employee_infomation where passport_number='".$newdata['passport_number']."' AND year=$year AND month=$month order by addtime desc limit 1";
					 		$relt = $this->db->fetch_array($this->db->query($sql));

					 		//$uid = $this->employee_db->insert_id();

					 		/*if ($uid != $relt[0]['id']) */$uid = $relt[0]['id'];

					 		// 判断uid 是否在uidArr中 若存在 提示报错
					 		if(in_array($uid, $uidArr)) {
					 			$_SESSION['is_import'] = '';
					 			showmessage("系统错误，请重试", HTTP_REFERER, 80000);break;
					 			die;
					 		} else {
					 			$uidArr[] = $uid;
					 			$indata[$k][6] = $uid;
					 			//array_push($indata[$k], $uid);
					 		}
					 	} else {
					 		$_SESSION['is_import'] = '';
					 		showmessage(L('project_number').$r[0].',顾问姓名：'.$r['6']."身份证:".$r[18]."信息添加操作失败", HTTP_REFERER, 80000);die;
					 	}
					 	unset($rel);
					 }

					############################  顾问基本信息处理   end ##############################
					############################  项目基本信息处理部分   star    #####################################
					// 验证项目信息是否存在
					$rel = $this->wage_project_db->get_one(array('pro_code'=>$r[0], 'pro_name'=>$r[1], 'project_leader' => $r[2],'year'=>$year,'month'=>$month, 'status'=>1), 'id');
					if (!empty($rel)) {
						$pro_id = $rel['id'];
					 	//array_push($indata[$k], $pro_id);
					 	array_push($indata[$k] , $pro_id);
					 } else {
					 	$newdata = array();
					 	$newdata = array(
					 				'pro_name' 				=> $r[1],
					 				'pro_code' 				=> $r[0],
					 				'project_leader'		=> $r[2],
					 				'year' 					=> $year,
					 				'month' 				=> $month,
					 				'addtime'				=> SYS_TIME
					 			);

					 	$rel = $this->wage_project_db->insert($newdata);
					 	if($rel) {
					 		$pid = $this->wage_project_db->insert_id();
					 		$pro_id = $pid;

					 		array_push($indata[$k], $pro_id);
					 	} else {
					 		$_SESSION['is_import'] = '';
					 		showmessage(L('project_number').$temp[0].',顾问姓名：'.$temp['6']."身份证:".$temp[18]."项目添加操作失败", HTTP_REFERER, 80000);break;
					 	}
					 	unset($rel);
					 }

					############################  项目基本信息处理部分   end     #####################################

					//判断是否与已导入数据重复
					$result = $this->db->get_one(array('user_id'=> $r[22],'pro_id'=> $r[23],'year'=>$year,'month'=>$month,'status'=>1),'id');
					if (!empty($result)) {
						$_SESSION['is_import'] = '';
						showmessage(L('project_number').$temp[0].',顾问姓名：'.$temp['6'].",身份证:".$temp[18].L('already_exists'), HTTP_REFERER);break;
					}

					if(!$pro_id) {
						$_SESSION['is_import'] = '';
						showmessage("操作失败， 获取项目失败，请重试或者联系管理员", HTTP_REFERER);break;
					}

					usleep("800");
				}
				
				// if ($user=='phpcms') {
				// echo "<pre>";						
				// var_dump($indata);die;
				// }

				######################################    新数据处理   star   #####################################################
					$newdata = array();
					foreach($indata as $k=>$r){
						$newdata[] = array(
							'year'=>$year,
							'month'=>$month,
							//'user_id'=>$r[22],
							'user_id'=>$r[6],
							'pro_id'=>$pro_id,
							'wage_cycle'=>$r[3],
							'city_name'=>$r[4],
							'job_name'=>$r[5],
							'wages_payable'=>$r[7],
							'per_pension'=>$r[8],
							'medical_treatment'=>$r[9],
							'per_unemplayment_insurance'=>$r[10],
							'other_insurance'=>$r[11],
							'per_provident_fund'=>$r[12],
							'taxable_salary'=>$r[13],
							'taxable_amount'=>$r[14],
							'taxes'=>$r[15],
							'net_pay'=>$r[16],
							'bank_num'=>$r[19],
							'bank_name'=>$r[20],
							'bank_full_name'=>$r[22],
							'operator'=>$r[2],
							'is_bonus'=> $is_bonus,
							'addtime'=>SYS_TIME,
							'bank_sheng'=>$r[23],
						);	
					}
					$insert = $this->db->insertAll($newdata);
					//die();
					if($insert){
						$content = $pro_id.'项目录入工资';
						$log = $this->wage_log_db->insert(array('user'=>$user,'content'=>$content, 'addtime'=>date("Y-m-d H:i:s")));
					} else {
						$content = $pro_id.'项目录入工资失败';
						$log = $this->wage_log_db->insert(array('user'=>$user,'content'=>$content, 'addtime'=>date("Y-m-d H:i:s")));
					}
				#####################################################  新数据处理  end    ######################################################

				if($insert){
					$sql = "SELECT SUM(wages_payable) as money FROM v9_wage WHERE pro_id=$pro_id AND month=$month AND year=$year AND is_bonus=".$is_bonus;
					$data = $this->db->fetch_array($this->db->query($sql));
					$_SESSION['is_import'] = '';
					showmessage(L('operation_success').' 一共上传'.$countNum.'条记录，'.'工资总共'.$data[0]['money'].'元', HTTP_REFERER, '80000');
					unset($data);
					unset($sql);
				}else{
					$_SESSION['is_import'] = '';
					showmessage(L('operation_failure'), HTTP_REFERER);
				}
			}else{
				$_SESSION['is_import'] = '';
				showmessage(L('operation_failure').'，模版格式错误', HTTP_REFERER);
			}	
		}else {
			$show_header = $show_validator = true;
			include $this->admin_tpl('wage/wage_import');
		}
		
	}
	
	/**
	 * export SPOCSalarySheet 
	 */
	function export() {
		if(isset($_POST['dosubmit'])) {
			$year = $_POST['year'];
			$month = $_POST['month'];

			$data = $this->db->getWageNew(array('a.month'=>$month, 'a.year'=>$year));

			// echo "<pre>";
			// var_dump(count($data));die;
			if(!empty($data)){
	
				//设置表头				
				$arr = array(
					"A1"=>"项目编号",
					"B1"=>"项目名称",
					"C1"=>"项目负责人",
					"D1"=>"工资周期",
					"E1"=>"负责城市",
					"F1"=>"职位",
					"G1"=>"顾问姓名",
					"H1"=>"应付金额",
					"I1"=>"个人养老",
					"J1"=>"个人医疗",
					"K1"=>"失业险",
					"L1"=>"其他",
					"M1"=>"个人公积金",
					"N1"=>"应税工资",
					"O1"=>"应纳税所得额",
					"P1"=>"税金",
					"Q1"=>"实发工资",
					"R1"=>"证件类型",
					"S1"=>"身份证号码",
					"T1"=>"银行账号",
					"U1"=>"开户名称",
					"V1"=>"联系方式",
					"W1"=>"银行所在省份",
				);
				$LANG['wage_sheet'] = '工资条';
				
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->setActiveSheetIndex(0);
				$key = array_keys($arr);
				for($j = 0;$j < count($arr); $j++){
					$k = $key[$j];
					//$objPHPExcel->setCellValue($k, $arr[$k]); 
					$objPHPExcel->getActiveSheet()->setCellValue($k, $arr[$k]);  
				}

				//数据输出
				$count=1;  //定义一个变量，目的是在循环输出数据是控制行数
				foreach($data as $k=>$v)
				{
					$count+=1;
					$arr_data = array(
						"A".$count=>$v['pro_code'],
						"B".$count=>$v['pro_name'],
						"C".$count=>$v['project_leader'],
						"D".$count=>$v['wage_cycle'],
						"E".$count=>$v['city_name'],
						"F".$count=>$v['job_name'],
						"G".$count=>$v['name'],
						"H".$count=>$v['wages_payable'],
						"I".$count=>$v['per_pension'],
						"J".$count=>$v['medical_treatment'],
						"K".$count=>$v['per_unemplayment_insurance'],
						"L".$count=>$v['other_insurance'],
						"M".$count=>$v['per_provident_fund'],
						"N".$count=>$v['taxable_salary'],
						"O".$count=>$v['taxable_amount'],
						"P".$count=>$v['taxes'],
						"Q".$count=>$v['net_pay'],
						"R".$count=>$this->passport_type[$v['passport_type']],
						"S".$count=>$v['passport_number'].' ',
						"T".$count=>$v['bank_num'].' ',
						"U".$count=>$v['bank_name'],
						"V".$count=>$v['mobile'].' ',
						"W".$count=>$v['bank_sheng'],
					);
					//echo "<pre>";var_dump($arr_data);die;
					$key1 = array_keys($arr_data);
					for($n = 0;$n < count($arr_data); $n++){
						$k1 = $key1[$n];
						//$objPHPExcel->setCellValue($k1, $arr_data[$k1]); 
						$objPHPExcel->getActiveSheet()->setCellValue($k1, $arr_data[$k1]);  
					}
					unset($data[$k]);  
				}
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle($year.'年'.$month.'月工资条');
				$objPHPExcel->setActiveSheetIndex(0);//设置sheet的起始位置

				ob_end_clean();//清除缓冲区,避免乱码
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				$str = $year.'年'.$month.'月工资条.xlsx';
				$str = mb_convert_encoding($str,"GBK","auto");
				header("Content-Disposition: attachment;filename=$str");
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');//通过PHPExcel_IOFactory的写函数将上面数据写出来
				$objWriter->save('php://output');
            }
            else{
				showmessage(L('temporary_no_data'), HTTP_REFERER);
			}				
		}
		else
		{
			$show_header = $show_validator = true;
			include $this->admin_tpl('wage/wage_export_new');
		}
	}
	
	/**
	 * delete SPOCSalarySheet
	 */
	function delete() {
		$idarr = isset($_POST['id']) ? $_POST['id'] : showmessage(L('illegal_parameters'), HTTP_REFERER);
		$idarr = array_map('intval',$idarr);
		$where = to_sqls($idarr, '', 'id');
		$append_where = to_sqls($idarr,'','spoc_id');
		
		//删除个数
		$count = $this->db->count($where);
		//数据条数
		$id = current($idarr);
		$data = $this->db->get_one("id = '$id'");//echo "<pre>";var_Dump($where, $idarr,$append_where);die;
		$data_where = "year = '$data[year]' and month = '$data[month]' and status=1";		
		$data_count = $this->db->count($data_where);
		
		if($count == $data_count || $count > $data_count){			
			if ($this->db->update(array('status'=> -1),$where)) {
				showmessage(L('operation_success'), HTTP_REFERER);
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}
		}else{		
			if ($this->db->update(array('status'=> -1),$where)) {
				showmessage(L('operation_success'), HTTP_REFERER);
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}
		}
	}
	
	/**
	 * detail SPOCSalarySheet
	 */
	function moreinfo() {
		$show_header = false;
		$id = !empty($_GET['id']) ? intval($_GET['id']) : false;
		$pro_id = !empty($_GET['job_number']) ? $_GET['job_number'] : false;
		$user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : false;
		//$job_number = !empty($_GET['job_number']) ? trim($_GET['job_number']) : '';
		if(!empty($id) || !empty($pro_id) || !empty($user_id)) {
			if($id) $where['a.id'] = $id;
			if($pro_id) $where['a.pro_id'] = $pro_id;
			if($user_id) $where['a.user_id'] = $user_id;
			if ($user !='phpcms') {
				//var_dump($where);die;
			}
			$wageinfo = $this->db->getWage ($where);

		} else {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		}
		if(empty($wageinfo)) {
			showmessage(L('wage_sheet').L('not_exists'), HTTP_REFERER);
		}else{
			foreach ($wageinfo as $key => $val) {
				$val['passport_type'] 	= $this->passport_type[$val['passport_type']];
				$val['addtime'] 		= date("Y-m-d H:i:s", $val['addtime']);
				$wageinfo = $val;
			}

		}

		include $this->admin_tpl('wage/wage_moreinfo');
	}

	/**
	 * 返回城市json
	 */
	public function getCityJson() {
		$pid = $_GET["pid"];
		if(isset($pid) && !empty($pid)){
			$cityInfos = GetCity($pid);
			foreach ($cityInfos as $v){
				$json[] = array("cid"=>$v['linkageid'],"city"=>urlencode($v['name']));
			}
			echo urldecode(json_encode($json));
		}else{
			echo '1';
		} 
	}

	/**
	 *  去除字符串中的空格
	 */
	protected function trimall($str) {
		$str = trim($str);
		$qian = array(" ", "　", "\t", "\n", "\r", ',');
		$place= array("", "", "", "", "", '');

		return str_replace($qian, $place, $str);
	}
}
?>