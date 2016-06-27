<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* 模型，提供插入记录和获取最新一条记录的接口 */
class Pos_model extends CI_Model{
    public function __construct(){
		$this->load->database();
    }
    /* 写入数据到数据库 */
	public function put_pos($client_id,$server_id,$x,$y,$z){
	    $t_timestamp = time();
        $data = array(
		    'client_id' => $client_id,
			'server_id' => $server_id,
			'x' => $x,
			'y' => $y,
			'z' => $z
		);
		//echo var_dump($data);
		return $this->db->insert('pos_20160625',$data);
	}
	
	/* 获取客户端最后一条的坐标 */
	public function get_pos($client_id,$server_id){
		$sql = "select * from pos_20160625 where client_id=".$this->db->escape($client_id)." AND server_id=".$this->db->escape($server_id)." order by timestamp desc limit 1";
		$query = $this->db->query($sql,$client_id,$server_id);
		$row = $query->row_array();
		$str_json = json_encode($row,JSON_FORCE_OBJECT);
		//echo $str_json;
		return $str_json;
	}
	
	/* 获取客户端最后30条的坐标 */
	public function get_n($client_id,$server_id,$n){
		$sql = "select * from pos_20160625 where client_id=".$this->db->escape($client_id)." AND server_id=".$this->db->escape($server_id)." order by timestamp desc limit ".$n;
		$query = $this->db->query($sql,$client_id,$server_id);
		//echo var_dump($query->result_array());
		$str_json = json_encode($query->result_array(),JSON_FORCE_OBJECT);
		//echo $str_json;
		return $str_json;
	}
}
?>