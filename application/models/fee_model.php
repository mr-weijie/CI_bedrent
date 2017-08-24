<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/20
 * Time: 7:50
 */
class Fee_model extends CI_Model {

    public function feelist(){
        $data['fee']=$this->db->order_by('ModDate','desc')->get('fee')->result_array();
        $data['feesum']=$this->db->select_sum('Rent')->get('fee')->result_array();
        $data['Bedding']=$this->db->select_sum('Bedding')->get('fee')->result_array();
      //  p($data);die;
        return $data;
    }
    public function getfeeinfo($FeeID){
        //$data=$this->db->where(array('FeeID'=>$FeeID))->get('fee')->result_array();
        $data=$this->db->select('FeeID,fee.ClientName,fee.IdentityID,Rent,Bedding,fee.StartDate,fee.StopDate,fee.Remarks,BedNo,fee.ModDate')->from('fee')->join('clientinfo','fee.IdentityID=clientinfo.IdentityID')->get()->result_array();
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