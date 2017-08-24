<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 22:31
 */
class Carfee_model extends CI_Model{

    public function feelist(){
        $data['carfee']=$this->db->order_by('ModDate','desc')->get('waterfee')->result_array();
        $data['carfeesum']=$this->db->select_sum('Money')->get('waterfee')->result_array();
      //    p($data);die;
        return $data;
    }


}