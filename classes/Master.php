<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,['id','content'])){
				$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .= ", ";
				$data .= "`{$k}` = '{$v}'";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `category_list` set {$data}";
		}else{
			$sql = "UPDATE `category_list` set {$data} where id = '{$id}'";
		}
		$save = $this->conn->query($sql);
		if($save){
			$cid= empty($id) ? $this->conn->insert_id : $id ;
			$resp['status'] = 'success';
			$resp['cid'] = $cid;
			if(empty($id))
				$this->settings->set_flashdata('success',"Category has been added successfully. ");
			else
				$this->settings->set_flashdata('success',"Category has been updated. ");
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'Saving Category failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->conn->query("UPDATE `category_list` set delete_flag = 1 where id = '{$id}' ");
		if($delete){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Category has been deleted successfully");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;

		}
		return json_encode($resp);
	}
	function dt_category(){
		extract($_POST);
 
		$totalCount = $this->conn->query("SELECT * FROM `category_list` where  delete_flag = 0 ")->num_rows;
		$search_where = "";
		if(!empty($search['value'])){
			$search_where .= "name LIKE '%{$search['value']}%' ";
			$search_where .= " OR description LIKE '%{$search['value']}%' ";
			$search_where .= " OR date_format(date_updated,'%M %d, %Y') LIKE '%{$search['value']}%' ";
			$search_where = " and ({$search_where}) ";
		}
		$columns_arr = array("unix_timestamp(date_updated)",
							"unix_timestamp(date_updated)",
							"name",
							"description",
							"status",
							"unix_timestamp(date_updated)");
		$query = $this->conn->query("SELECT * FROM `category_list`  where  delete_flag = 0  {$search_where} ORDER BY {$columns_arr[$order[0]['column']]} {$order[0]['dir']} limit {$length} offset {$start} ");
		$recordsFilterCount = $this->conn->query("SELECT * FROM `category_list`  where  delete_flag = 0  {$search_where} ")->num_rows;
		
		$recordsTotal= $totalCount;
		$recordsFiltered= $recordsFilterCount;
		$data = array();
		$i= 1 + $start;
		while($row = $query->fetch_assoc()){
			$row['no'] = $i++;
			$row['date_updated'] = date("F d, Y H:i",strtotime($row['date_updated']));
			$data[] = $row;
		}
		echo json_encode(array('draw'=>$draw,
							'recordsTotal'=>$recordsTotal,
							'recordsFiltered'=>$recordsFiltered,
							'data'=>$data
							)
		);
	}
	function save_user(){
		if(empty($_POST['id'])){
			$_POST['password'] = password_hash(strtolower(substr($_POST['firstname'],0 ,1).$_POST['lastname']), PASSWORD_DEFAULT);
		}
		if(isset($_POST['reset_password'])){
			$get=$this->conn->query("SELECT * FROM `users` where id = '{$_POST['id']}'")->fetch_array();
			$_POST['password'] = password_hash(strtolower(substr($get['firstname'],0 ,1).$get['lastname']), PASSWORD_DEFAULT);
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,['id','reset_password']) && !is_array($_POST[$k])){
				$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .= ", ";
				$data .= "`{$k}` = '{$v}'";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `users` set {$data}";
		}else{
			$sql = "UPDATE `users` set {$data} where id = '{$id}'";
		}
		$save = $this->conn->query($sql);
		if($save){
			$uid= empty($id) ? $this->conn->insert_id : $id ;
			$resp['uid'] = $uid;
			$err = "";
			$resp['status'] = 'success';
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				if(!is_dir(base_app."uploads/users"))
					mkdir(base_app."uploads/users");
				$fname = 'uploads/users/avatar-'.$uid.'.png';
				$dir_path =base_app. $fname;
				$upload = $_FILES['img']['tmp_name'];
				$type = mime_content_type($upload);
				$allowed = array('image/png','image/jpeg');
				if(!in_array($type,$allowed)){
					$err.=" But Image failed to upload due to invalid file type.";
				}else{
					$new_height = 200; 
					$new_width = 200; 
			
					list($width, $height) = getimagesize($upload);
					$t_image = imagecreatetruecolor($new_width, $new_height);
					imagealphablending( $t_image, false );
					imagesavealpha( $t_image, true );
					$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
					imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					if($gdImg){
							if(is_file($dir_path))
							unlink($dir_path);
							$uploaded_img = imagepng($t_image,$dir_path);
							if(isset($uploaded_img)){
								$this->conn->query("UPDATE users set `avatar` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$uid}' ");
							}
							imagedestroy($gdImg);
							imagedestroy($t_image);
					}else{
					$err.=" But Image failed to upload due to unknown reason.";
					}
				}
			}
			if(empty($id))
				$this->settings->set_flashdata('success',"User has been added successfully.");
			else
				$this->settings->set_flashdata('success',"User has been updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'Saving User failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_user(){
		extract($_POST);
		$get = $this->conn->query("SELECT * `users` where id = '{$id}' ")->fetch_array();
		$delete = $this->conn->query("UPDATE `users` where id = '{$id}' ");
		if($delete){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," User has been deleted successfully");
			if(isset($get['avatar'])){
				$get['avatar'] = explode("?",$get['avatar'])[0];
				if(is_file(base_app.$get['avatar']))
				unlink(base_app.$get['avatar']);
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;

		}
		return json_encode($resp);
	}
	function dt_users(){
		extract($_POST);
		$totalCount = $this->conn->query("SELECT * FROM `users` where id != '{$this->settings->userdata('id')}' ")->num_rows;
		$search_where = "";
		$columns_arr = array("unix_timestamp(date_updated)",
							"unix_timestamp(date_updated)",
							"CONCAT(lastname, ', ',firstname,' ',COALESCE(middlename,''))",
							"status",
							"unix_timestamp(date_updated)");
		if(!empty($search['value'])){
			$search_where .= "firstname LIKE '%{$search['value']}%' ";
			$search_where .= " OR lastname LIKE '%{$search['value']}%' ";
			$search_where .= " OR middlename LIKE '%{$search['value']}%' ";
			$search_where .= " OR CONCAT(lastname, ', ',firstname,' ',COALESCE(middlename,'')) LIKE '%{$search['value']}%' ";
			$search_where .= " OR CONCAT(firstname,' ',COALESCE(middlename,''), ' ', lastname) LIKE '%{$search['value']}%' ";
			$search_where .= " OR date_format(date_updated,'%M %d, %Y') LIKE '%{$search['value']}%'";
			$search_where = " and ({$search_where}) ";
		}
		$query = $this->conn->query("SELECT *,CONCAT(lastname, ', ',firstname,' ',COALESCE(middlename,'')) as `name` FROM `users`  where id != '{$this->settings->userdata('id')}'  {$search_where} ORDER BY {$columns_arr[$order[0]['column']]} {$order[0]['dir']} limit {$length} offset {$start} ");
		$query2 = $this->conn->query("SELECT * FROM `users`  where id != '{$this->settings->userdata('id')}'  {$search_where} ORDER BY {$columns_arr[$order[0]['column']]} {$order[0]['dir']} limit {$length} offset {$start} ");
		$recordsFilterCount = $this->conn->query("SELECT * FROM `users`  where id != '{$this->settings->userdata('id')}'  {$search_where} ")->num_rows;
		
		$recordsTotal= $totalCount;
		$recordsFiltered= $recordsFilterCount;
		$data = array();
		$i= 1 + $start;
		while($row = $query->fetch_assoc()){
			$row['no'] = $i++;
			$row['avatar'] = validate_image(isset($row['avatar']) ? $row['avatar'] : '');
			$row['date_updated'] = date("F d, Y H:i",strtotime($row['date_updated']));
			$data[] = $row;
		}
		echo json_encode(array('draw'=>$draw,
							'recordsTotal'=>$recordsTotal,
							'recordsFiltered'=>$recordsFiltered,
							'data'=>$data
							)
		);
	}
	function save_product(){
		if(empty($_POST['id'])){
			$_POST['user_id'] = $this->settings->userdata('id');
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k,['id','content']) && !is_array($_POST[$k])){
				$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .= ", ";
				$data .= "`{$k}` = '{$v}'";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `product_list` set {$data}";
		}else{
			$sql = "UPDATE `product_list` set {$data} where id = '{$id}'";
		}
		$save = $this->conn->query($sql);
		if($save){
			$pid= empty($id) ? $this->conn->insert_id : $id ;
			$resp['pid'] = $pid;
			$err = "";
			$resp['status'] = 'success';
			if(!is_dir(base_app."contents/"))
					mkdir(base_app."contents/");
			file_put_contents(base_app."contents/$pid.html",$content);
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				if(!is_dir(base_app."uploads/thumbnails"))
					mkdir(base_app."uploads/thumbnails");
				$fname = 'uploads/thumbnails/'.$pid.'.png';
				$dir_path =base_app. $fname;
				$upload = $_FILES['img']['tmp_name'];
				$type = mime_content_type($upload);
				$allowed = array('image/png','image/jpeg');
				if(!in_array($type,$allowed)){
					$err.=" But Image failed to upload due to invalid file type.";
				}else{
					list($width, $height) = getimagesize($upload);
					$new_width = $width; 
					$new_height = $height; 

					if($height > 200)
					$new_height = 200; 

					if($width > 200)
					$new_width = 200; 
			
					$t_image = imagecreatetruecolor($new_width, $new_height);
					imagealphablending( $t_image, false );
					imagesavealpha( $t_image, true );
					$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
					imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					if($gdImg){
							if(is_file($dir_path))
							unlink($dir_path);
							$uploaded_img = imagepng($t_image,$dir_path);
							imagedestroy($gdImg);
							imagedestroy($t_image);
					}else{
					$err.=" But Image failed to upload due to unknown reason.";
					}
				}
			}
			if(empty($id))
				$this->settings->set_flashdata('success',"Product has been added successfully. ". $err);
			else
				$this->settings->set_flashdata('success',"Product has been updated. ". $err);
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = 'Saving product failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_product(){
		extract($_POST);
		$delete = $this->conn->query("DELETE from `product_list` where id = '{$id}' ");
		if($delete){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Product has been deleted successfully");
			if(is_file(base_app."contents/$id.html"))
			unlink(base_app."contents/$id.html");
			if(is_file(base_app."uploads/thumbnails/$id.png"))
			unlink(base_app."uploads/thumbnails/$id.png");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;

		}
		return json_encode($resp);
	}
	function dt_product_admin(){
		extract($_POST);
		$totalCount = $this->conn->query("SELECT * FROM `product_list` ")->num_rows;
		$search_where = "";
		$columns_arr = array("unix_timestamp(date_updated)",
							"title",
							"status",
							"unix_timestamp(date_updated)");
		if(!empty($search['value'])){
			$search_where .= "title LIKE '%{$search['value']}%' ";
			$search_where .= " OR short_description LIKE '%{$search['value']}%' ";
			$search_where .= " OR selling_price LIKE '%{$search['value']}%' ";
			$search_where .= " OR date_format(date_updated,'%M %d, %Y') LIKE '%{$search['value']}%' or category_id in (SELECT id FROM category_list where name LIKE '%{$search['value']}%' ) ";
			$search_where = " where ({$search_where}) ";
		}
		$query = $this->conn->query("SELECT * FROM `product_list`   {$search_where} ORDER BY {$columns_arr[$order[0]['column']]} {$order[0]['dir']} limit {$length} offset {$start} ");
		$query2 = $this->conn->query("SELECT * FROM `product_list`   {$search_where} ORDER BY {$columns_arr[$order[0]['column']]} {$order[0]['dir']} limit {$length} offset {$start} ");
		$query3 = $this->conn->query("SELECT `user_id` FROM `product_list`   {$search_where} ORDER BY {$columns_arr[$order[0]['column']]} {$order[0]['dir']} limit {$length} offset {$start} ");
		$recordsFilterCount = $this->conn->query("SELECT * FROM `product_list`   {$search_where} ")->num_rows;
		
		$recordsTotal= $totalCount;
		$recordsFiltered= $recordsFilterCount;
		$data = array();
		$i= 1 + $start;
		$category_arr = [];
		$user_arr = [];
		$cids = array_column($query2->fetch_all(MYSQLI_ASSOC),'category_id');
		$uids = array_column($query3->fetch_all(MYSQLI_ASSOC),'user_id');
		if(count($cids) > 0){
			$category = $this->conn->query("SELECT `name` as category,id FROM category_list where id in (".(implode(",",$cids)).")")->fetch_all(MYSQLI_ASSOC);
			$category_arr = array_column($category,'category','id');
		}
		if(count($uids) > 0){
			$user = $this->conn->query("SELECT id, username FROM `users` where id in (".(implode(",",$uids)).")")->fetch_all(MYSQLI_ASSOC);
			$user_arr = array_column($user,'username','id');
		}
		while($row = $query->fetch_assoc()){
			$row['no'] = $i++;
			$row['category'] = isset($category_arr[$row['category_id']]) ? $category_arr[$row['category_id']] : "N/A";
			$row['seller'] = isset($user_arr[$row['user_id']]) ? $user_arr[$row['user_id']] : "N/A";
			$row['thumbnail'] = validate_image( is_file(base_app."uploads/thumbnails/".($row['id']).".png") ? "uploads/thumbnails/".($row['id']).".png?v=".(strtotime($row['date_updated'])) : '');
			$row['date_updated'] = date("F d, Y H:i",strtotime($row['date_updated']));
			$data[] = $row;
		}
		echo json_encode(array('draw'=>$draw,
							'recordsTotal'=>$recordsTotal,
							'recordsFiltered'=>$recordsFiltered,
							'data'=>$data
							)
		);
	}
	function dt_product_seller(){
		extract($_POST);
		$totalCount = $this->conn->query("SELECT * FROM `product_list` where user_id = '{$this->settings->userdata('id')}' ")->num_rows;
		$search_where = "";
		$columns_arr = array("unix_timestamp(date_updated)",
							"title",
							"status",
							"unix_timestamp(date_updated)");
		if(!empty($search['value'])){
			$search_where .= "title LIKE '%{$search['value']}%' ";
			$search_where .= " OR short_description LIKE '%{$search['value']}%' ";
			$search_where .= " OR selling_price LIKE '%{$search['value']}%' ";
			$search_where .= " OR date_format(date_updated,'%M %d, %Y') LIKE '%{$search['value']}%' or category_id in (SELECT id FROM category_list where name LIKE '%{$search['value']}%' ) ";
			$search_where = " and ({$search_where}) ";
		}
		$query = $this->conn->query("SELECT * FROM `product_list`  where user_id = '{$this->settings->userdata('id')}'  {$search_where} ORDER BY {$columns_arr[$order[0]['column']]} {$order[0]['dir']} limit {$length} offset {$start} ");
		$query2 = $this->conn->query("SELECT * FROM `product_list`  where user_id = '{$this->settings->userdata('id')}'  {$search_where} ORDER BY {$columns_arr[$order[0]['column']]} {$order[0]['dir']} limit {$length} offset {$start} ");
		$recordsFilterCount = $this->conn->query("SELECT * FROM `product_list`  where user_id = '{$this->settings->userdata('id')}'  {$search_where} ")->num_rows;
		
		$recordsTotal= $totalCount;
		$recordsFiltered= $recordsFilterCount;
		$data = array();
		$i= 1 + $start;
		$category_arr = [];
		$cids = array_column($query2->fetch_all(MYSQLI_ASSOC),'category_id');
		if(count($cids) > 0){
			$category = $this->conn->query("SELECT `name` as category,id FROM category_list where id in (".(implode(",",$cids)).")")->fetch_all(MYSQLI_ASSOC);
			$category_arr = array_column($category,'category','id');
		}
		while($row = $query->fetch_assoc()){
			$row['no'] = $i++;
			$row['category'] = isset($category_arr[$row['category_id']]) ? $category_arr[$row['category_id']] : "N/A";
			$row['thumbnail'] = validate_image( is_file(base_app."uploads/thumbnails/".($row['id']).".png") ? "uploads/thumbnails/".($row['id']).".png?v=".(strtotime($row['date_updated'])) : '');
			$row['date_updated'] = date("F d, Y H:i",strtotime($row['date_updated']));
			$data[] = $row;
		}
		echo json_encode(array('draw'=>$draw,
							'recordsTotal'=>$recordsTotal,
							'recordsFiltered'=>$recordsFiltered,
							'data'=>$data
							)
		);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_category':
		echo $Master->save_category();
	break;
	case 'delete_category':
		echo $Master->delete_category();
	break;
	case 'dt_category':
		echo $Master->dt_category();
	break;
	case 'save_user':
		echo $Master->save_user();
	break;
	case 'delete_user':
		echo $Master->delete_user();
	break;
	case 'dt_users':
		echo $Master->dt_users();
	break;
	case 'save_product':
		echo $Master->save_product();
	break;
	case 'delete_product':
		echo $Master->delete_product();
	break;
	case 'dt_product_seller':
		echo $Master->dt_product_seller();
	break;
	case 'dt_product_admin':
		echo $Master->dt_product_admin();
	break;
	default:
		// echo $sysset->index();
		break;
}