<?php

class Device{
	public $id;
	public $udid;
	public $channel;
	public $channel_id;
	public $version;
	public $version_id;
	public $brand;
	public $model;
	public $model_id;
	public $os_version;
	public $os_version_id;
	public $resolution;
	public $resolution_id;
	public $carrier;
	public $carrier_id;
	public $network;
	public $network_id;
	
	public $is_new = false;
	public $is_update = false; // 是不是一个升级用户
	
	/**
	 * save this model into database
	 * @param string date and time when the device accessed
	 */
	public function save($accessed_at=null){
		if($this->id) return; // already saved into database
		
		$sql = "select * from {devices} where udid=:udid";
		$stmt = TCClick::app()->db->query($sql, array(":udid"=>$this->udid));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if($row){ // 这是一个老用户
			$this->id = $row['id'];
			if($this->version_id > $row['version_id']){ // 这是一个升级用户
				$this->is_update = true;
				$sql = "update {devices} set version_id={$this->version_id} where id={$this->id}";
				TCClick::app()->db->execute($sql);
			}
		}else{
			$sql = "insert into {devices} (udid, channel_id, version_id, created_at) values
					(:udid, :channel_id, :version_id, :created_at)
					on duplicate key update id=last_insert_id(id)";
			$params = array(":udid"=>$this->udid, ":channel_id"=>$this->channel_id,
					":created_at"=>$accessed_at, ":version_id"=>$this->version_id);
			$result = TCClick::app()->db->execute($sql, $params);
			$this->is_new = $result === 1; // 确定是插入到数据库里面了(并发环境下)
			$this->id = TCClick::app()->db->lastInsertId();
		}
	}
}

