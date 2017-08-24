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
        $perpage=22;
        //配置项设置
        $config['base_url']=site_url('carfee/index');
        $config['total_rows'] = $this->db->count_all_results('waterfee');
        $config['per_page']=$perpage;
        $config['uri_segment']=3;

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
        $data=$this->carfee->feelist();
        $data['title']='水卡费列表';
        $data['links']=$links;
        $data['disable']='carfee';
        $this->load->view('header.html',$data);
        $this->load->view('fee/nav.html');
        $this->load->view('fee/water/feelist.html');
        $this->load->view('footer.html');

    }


}