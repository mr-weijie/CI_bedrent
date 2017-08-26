<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 14:50
 */
class Clientinfo_model extends CI_Model{

public function listclient($Valid){
    $data=$this->db->where(array('Valid'=>$Valid))->order_by('StopDate')->get('clientinfo')->result_array();
    return $data;
}
public function getclientinfo($clientID){
    $data=$this->db->where(array('ClientID'=>$clientID))->get('clientinfo')->result_array();
  //  p($data);
    return $data;
}
public function insertclient($data){
    $status=$this->db->insert('clientinfo',$data);
    return $status;
}
public function updateclientinfo($ClientID,$data){
    $status=$this->db->update('clientinfo', $data, array('ClientID'=>$ClientID));
    return $status;
}

public function uploadfile($clientID,$photofile){
    $data = array('IdentityPhoto' =>$photofile);
    $this->db->update('clientinfo', $data, array('ClientID'=>$clientID));
    $nums=$this->db->affected_rows();    #受影响的行数
  //  echo 'str='.$nums;die;
}
public function getbedinfo(){
    $data=$this->db->select('bedNo,ClientID,ClientName,StopDate')->where(array('Valid'=>1))->get('clientinfo')->result_array();
    foreach ($data as $item):
        $bedNo[$item['bedNo']]['ClientID']=$item['ClientID'];
        $bedNo[$item['bedNo']]['ClientName']=$item['ClientName'];
        $bedNo[$item['bedNo']]['StopDate']=$item['StopDate'];
    endforeach;
   // p($bedNo);
    return $bedNo;
}
public function chkuser($usrid,$pwd){
    $pwd=md5($pwd);
    $data=$this->db->select('usrname,usrid')->where(array('usrid'=>$usrid,'pwd'=>$pwd))->get('admin')->result_array();
    return $data;
}
}

