<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Position extends CI_Controller {
    /* 连接上默认的数据库，在配置文件中配置 */
	public function __construct(){
		parent::__construct();
		$this->load->model('pos_model');
		$this->load->helper('url_helper');
	}
	
	/* 客户端上报数据，并插入到数据库中 */
	public function put_pos($client_id,$server_id,$x,$y=0,$z=0){
		$this->pos_model->put_pos($client_id,$server_id,$x,$y,$z);
		//$this->load->view('position/success');
	}
	
	public function get_pos($client_id,$server_id)
	{
		$str_json = $this->pos_model->get_pos($client_id, $server_id);
		echo $str_json;
	}
	
	public function get_n($client_id,$server_id,$n=5)
	{
		$str_json = $this->pos_model->get_n($client_id, $server_id,$n);
		echo $str_json;
	}
	
	public function show_pos(){
		$this->load->view('position/show_pos');
		$this->load->view('template/end');
	}
}
?>