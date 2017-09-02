<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/20
 * Time: 7:50
 */
class Fee_model extends CI_Model {

    public function feelist($ClientID){
        if(isset($ClientID))
        {
         // $data['fee']=$this->db->get_where('fee',array('ClientID'=>$ClientID))->result_array();
         // $data['fee']=$this->db->select('FeeID,ClientName,IdentityID,Rent,Bedding,StartDate,StopDate,Remarks,ModDate')->from('fee')->where(array('ClientID'=>$clientID))->get()->result_array();
         // $data['fee']=$this->db->order_by('ModDate','desc')->get('fee')->result_array();
            $data['fee']=$this->db->where(array('ClientID'=>$ClientID))->order_by('ModDate','desc')->get('fee')->result_array();
            $data['feesum']=$this->db->select_sum('Rent')->where(array('ClientID'=>$ClientID))-> get('fee')->result_array();
            $data['Bedding']=$this->db->select_sum('Bedding')->where(array('ClientID'=>$ClientID))->get('fee')->result_array();
        }else{
            $data['fee']=$this->db->order_by('ModDate','desc')->get('fee')->result_array();
            $data['feesum']=$this->db->select_sum('Rent')->get('fee')->result_array();
            $data['Bedding']=$this->db->select_sum('Bedding')->get('fee')->result_array();
        }
       // var_dump($ClientID);
       // p($data);
        return $data;
    }
    public function getfeeinfo($FeeID){
        //$data=$this->db->where(array('FeeID'=>$FeeID))->get('fee')->result_array();
        $data=$this->db->select('FeeID,fee.ClientName,fee.ClientID,fee.IdentityID,Rent,Bedding,fee.StartDate,fee.StopDate,fee.Remarks,BedNo,fee.ModDate,fee.Editor')->from('fee')->join('clientinfo','fee.IdentityID=clientinfo.IdentityID')->where(array('FeeID'=>$FeeID))->get()->result_array();
         // p($data);
        return $data;
    }
    public function update($FeeID,$data){
        $status=$this->db->update('fee',$data,array('FeeID'=>$FeeID));
        return $status;
    }
    public function insert($data){
        $status=$this->db->insert('fee',$data);
        return $status;
    }
}