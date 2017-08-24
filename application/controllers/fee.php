<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/20
 * Time: 7:01
 */
class Fee extends CI_Controller{

    public function index(){
        $this->feelist();
    }

    public function  feelist(){//缴费列表
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
            $config['base_url']=site_url('fee/feelist/'.$ClientID);
            $config['total_rows'] = $this->db->where(array('ClientID'=>$ClientID))->count_all_results('fee');
            $config['uri_segment']=4;
        }else{
            $config['base_url']=site_url('fee/feelist');
            $config['total_rows'] = $this->db->count_all_results('fee');
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
        $this->load->model('fee_model','fee');
        $data=$this->fee->feelist($ClientID);
        $data['title']='床费列表';
        $data['links']=$links;
        $data['disable']='bedfee';
        $data['ClientID']=$ClientID;
        $this->load->view('header.html',$data);
        if(isset($ClientID)){
            $this->load->view('fee/nav_person.html');
        }else{
            $this->load->view('fee/nav.html');
        }
        $this->load->view('fee/bed/feelist.html');
        $this->load->view('footer.html');
    }
    public function showfee(){//显示具体缴费记录页
        $feeID=$this->uri->segment(3);
        $this->load->model('fee_model','fee');
        $data['fee']=$this->fee->getfeeinfo($feeID);
        $data['title']='房客缴费详情';
        $this->load->view('header.html',$data);
        $this->load->view('fee/bed/showfee.html');
        $this->load->view('footer.html');

    }
    public function editfee(){//编辑缴费记录表单页
        $this->load->helper('form');
        $FeeID=$this->uri->segment(3);
        $this->load->model('fee_model','fee');
        $data['feeinfo']=$this->fee->getfeeinfo($FeeID);
        $this->load->view('header.html',$data);
        $this->load->view('fee/bed/editfee.html');
        $this->load->view('footer.html');
    }

    public function addfee(){//新增缴费记录表单页
        $ClientID=$this->uri->segment(3);
        $this->load->model('clientinfo_model','clientinfo');
        $data['clientinfo']=$this->clientinfo->getclientinfo($ClientID);
        $this->load->helper('form');
        $this->load->view('header.html',$data);
        $this->load->view('fee/bed/addfee.html');
        $this->load->view('footer.html');
    }
    public function insert(){//新增缴费记录储存模块
        $status=$this->validation();//调用验证函数
        if($status){
            $IdentityID=$this->input->post('IdentityID');
            $ClientID=$this->input->post('ClientID');
            $data=array(
                'FeeID'=>strtoupper(md5($IdentityID.date("Y-m-d H:i:s"))),//采用系统时间+IdentityID的方法
                'ClientID'=>$ClientID,
                'IdentityID'=>$IdentityID,
                'ClientName'=>$this->input->post('ClientName'),
                'Rent'=>$this->input->post('Rent'),
                'Bedding'=>$this->input->post('Bedding'),
                'StartDate'=>$this->input->post('StartDate'),
                'StopDate'=>$this->input->post('StopDate'),
                'Remarks'=>$this->input->post('Remarks'),
                'ModDate'	=> date('Y-m-d H:i:s')//注意格式中的H:i:s为24小时制，h:i:s为12小时制,Y为四位年，y为二位年
            );
            $this->load->model('fee_model','fee');
            $status=$this->fee->insert($data);
            if($status){
                $msg='新增缴费记录成功！';
                $url='fee/feelist/'.$ClientID;
                success($url, $msg);
            }

        }else
        {
            $this->load->helper('form');//加载显示表单错误类
            $this->load->view('header.html');
            $this->load->view('fee/bed/editfee.html');
            $this->load->view('footer.html');
        }

    }
    public function update(){
        $status=$this->validation();//调用验证函数
        if($status){
            $FeeID=$this->input->post('FeeID');
            $data=array(
                'Rent'=>$this->input->post('Rent'),
                'Bedding'=>$this->input->post('Bedding'),
                'StartDate'=>$this->input->post('StartDate'),
                'StopDate'=>$this->input->post('StopDate'),
                'Remarks'=>$this->input->post('Remarks'),
                'ModDate'	=> date('Y-m-d H:i:s')//注意格式中的H:i:s为24小时制，h:i:s为12小时制,Y为四位年，y为二位年
            );
            $this->load->model('fee_model','fee');
            $status=$this->fee->update($FeeID,$data);
            if($status){
                $msg='记录更新成功！';
                $url='fee/showfee/'.$FeeID;
                success($url, $msg);
            }

        }else
        {
            $this->load->helper('form');//加载显示表单错误类
            $this->load->view('header.html');
            $this->load->view('fee/bed/editfee.html');
            $this->load->view('footer.html');
        }
       // $data=$this->input->post('');
    }
    private function validation(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('Rent',"床位租金",'required|numeric');
        $this->form_validation->set_rules('Bedding',"被褥租金",'required|numeric');
        $this->form_validation->set_rules('StartDate',"起始日期",'required');
        $this->form_validation->set_rules('StopDate',"终止日期",'required');
        $status=$this->form_validation->run();
        return $status;
    }
}