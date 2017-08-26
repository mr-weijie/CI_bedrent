<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 22:31
 */
class Cardfee_model extends CI_Model{

    public function feelist($ClientID){
        if(isset($ClientID))
        {
            $data['cardfee']=$this->db->where(array('ClientID'=>$ClientID))->order_by('ModDate','desc')->get('waterfee')->result_array();
            $data['cardfeesum']=$this->db->select_sum('Money')->where(array('ClientID'=>$ClientID))->get('waterfee')->result_array();
        }else{
            $data['cardfee']=$this->db->order_by('ModDate','desc')->get('waterfee')->result_array();
            $data['cardfeesum']=$this->db->select_sum('Money')->get('waterfee')->result_array();
        }
      //    p($data);die;
        return $data;
    }
    public function getfeeinfo($FeeID){
       // $data=$this->db->where(array('FeeID'=>$FeeID))->get('waterfee')->result_array();
        $data=$this->db->select('FeeID,BedNo,waterfee.CardNo,waterfee.ClientName,waterfee.ClientID,waterfee.IdentityID,Money,waterfee.Remarks,waterfee.ModDate,waterfee.Editor')->from('waterfee')->join('clientinfo','waterfee.IdentityID=clientinfo.IdentityID')->where(array('FeeID'=>$FeeID))->get()->result_array();
        return $data;
    }
    public function update($data,$FeeID){
        $status=$this->db->update('waterfee',$data,array('FeeID'=>$FeeID));
        return $status;
    }
    public function insert($data){
        $status=$this->db->insert('waterfee',$data);
        return $status;
    }
}