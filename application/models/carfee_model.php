<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 22:31
 */
class Carfee_model extends CI_Model{

    public function feelist($ClientID){
        if(isset($ClientID))
        {
            $data['carfee']=$this->db->where(array('ClientID'=>$ClientID))->order_by('ModDate','desc')->get('waterfee')->result_array();
            $data['carfeesum']=$this->db->select_sum('Money')->where(array('ClientID'=>$ClientID))->get('waterfee')->result_array();
        }else{
            $data['carfee']=$this->db->order_by('ModDate','desc')->get('waterfee')->result_array();
            $data['carfeesum']=$this->db->select_sum('Money')->get('waterfee')->result_array();
        }
      //    p($data);die;
        return $data;
    }


}