<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 22:28
 */
class Carfee extends CI_Controller{
    public function index(){
        $this->feelist();
    }
    public function feelist(){
        $this->load->library('pagination');
        $pageNo=$this->uri->segment(4);
        $ClientID=null;
        if(isset($pageNo)){//说明有ClientID项
            $ClientID=$this->uri->segment(3);
        }else{
            $pageNo=$this->uri->segment(3);
            if(strlen($pageNo)==32){
                //说明是具体ClientID的信息形式：index.php/fee/feelist/87533803B28F43F0E330131E2D17340C
                $ClientID=$this->uri->segment(3);
            }
        }
        $perpage=22;
        //配置项设置
        if(isset($ClientID)){
            $config['base_url']=site_url('carfee/feelist/'.$ClientID);
            $config['total_rows'] = $this->db->where(array('ClientID'=>$ClientID))->count_all_results('waterfee');
            $config['uri_segment']=4;
        }else{
            $config['base_url']=site_url('carfee/feelist');
            $config['total_rows'] = $this->db->count_all_results('waterfee');
            $config['uri_segment']=3;
        }
        $config['per_page']=$perpage;

        $config['first_link'] = '第一页';
        $config['prev_link'] = '上一页';
        $config['next_link'] = '下一页';
        $config['last_link'] = '最后一页';

        $this->pagination->initialize($config);//初始化
        $links = $this->pagination->create_links();
        $offset=$this->uri->segment( $config['uri_segment']);
        // p($offset);
        $this->db->limit($perpage, $offset);
        //p($data['links']);die;
        $this->load->model('carfee_model','carfee');
        $data=$this->carfee->feelist($ClientID);
        $data['title']='水卡费列表';
        $data['links']=$links;
        $data['disable']='carfee';
        $data['ClientID']=$ClientID;
        $this->load->view('header.html',$data);
        if(isset($ClientID)){
            $this->load->view('fee/nav_person.html');
        }else{
            $this->load->view('fee/nav.html');
        }
        $this->load->view('fee/water/feelist.html');
        $this->load->view('footer.html');

    }


}