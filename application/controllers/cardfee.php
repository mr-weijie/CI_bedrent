<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 22:28
 */
class Cardfee extends CI_Controller
{
    public function index()
    {
        $this->feelist();
    }

    public function feelist()
    {
        $this->load->library('pagination');
        $pageNo = $this->uri->segment(4);
        $ClientID = null;
        if (isset($pageNo)) {//说明有ClientID项
            $ClientID = $this->uri->segment(3);
        } else {
            $pageNo = $this->uri->segment(3);
            if (strlen($pageNo) == 32) {
                //说明是具体ClientID的信息形式：index.php/fee/feelist/87533803B28F43F0E330131E2D17340C
                $ClientID = $this->uri->segment(3);
            }
        }
        $perpage = 22;
        //配置项设置
        if (isset($ClientID)) {
            $config['base_url'] = site_url('cardfee/feelist/' . $ClientID);
            $config['total_rows'] = $this->db->where(array('ClientID' => $ClientID))->count_all_results('waterfee');
            $config['uri_segment'] = 4;
        } else {
            $config['base_url'] = site_url('cardfee/feelist');
            $config['total_rows'] = $this->db->count_all_results('waterfee');
            $config['uri_segment'] = 3;
        }
        $config['per_page'] = $perpage;

        $config['first_link'] = '第一页';
        $config['prev_link'] = '上一页';
        $config['next_link'] = '下一页';
        $config['last_link'] = '最后一页';

        $this->pagination->initialize($config);//初始化
        $links = $this->pagination->create_links();
        $offset = $this->uri->segment($config['uri_segment']);
        // p($offset);
        $this->db->limit($perpage, $offset);
        //p($data['links']);die;
        $this->load->model('cardfee_model', 'cardfee');
        $data = $this->cardfee->feelist($ClientID);
        $data['title'] = '水卡费列表';
        $data['links'] = $links;
        $data['disable'] = 'cardfee';
        $data['ClientID'] = $ClientID;
        $this->load->view('header.html', $data);
        if (isset($ClientID)) {
            $this->load->view('fee/nav_person.html');
        } else {
            $this->load->view('fee/nav.html');
        }
        $this->load->view('fee/water/feelist.html');
        $this->load->view('footer.html');

    }

    public function showfee()
    {
        $FeeID = $this->uri->segment(3);
        $this->load->model('cardfee_model', 'cardfee');
        $data['cardfee'] = $this->cardfee->getfeeinfo($FeeID);
        $this->load->view('header.html', $data);
        $this->load->view('fee/water/showfee.html');
        $this->load->view('footer.html');
    }

    public function editfee()
    {
        $this->load->helper('form');
        $FeeID = $this->uri->segment(3);
        $this->load->model('cardfee_model', 'cardfee');
        $data['feeinfo'] = $this->cardfee->getfeeinfo($FeeID);
        $this->load->view('header.html', $data);
        $this->load->view('fee/water/editfee.html');
        $this->load->view('footer.html');

    }
    public function addfee(){
        $this->load->helper('form');
        $ClientID = $this->uri->segment(3);
        $this->load->model('clientinfo_model','clientinfo');
        $data['clientinfo']=$this->clientinfo->getclientinfo($ClientID);
        $this->load->view('header.html', $data);
        $this->load->view('fee/water/addfee.html');
        $this->load->view('footer.html');
    }
    public function insert(){
        $this->load->library('form_validation');//加载表单验证类库
        $this->form_validation->set_rules('CardNo', '水卡号码', 'required');//设置验证规则
        $this->form_validation->set_rules('Money', '缴费金额', 'required|numeric');//设置验证规则
        $status = $this->form_validation->run();//执行验证
        if ($status) {
            $IdentityID=$this->input->post('IdentityID');
            $ClientID=$this->input->post('ClientID');
            $data=array(
                'IdentityID'=>$IdentityID,
                'FeeID'=>strtoupper(md5($IdentityID.date("Y-m-d H:i:s"))),
                'ClientID'=>$ClientID,
                'ClientName'=>$this->input->post('ClientName'),
                'CardNo'=>$this->input->post('CardNo'),
                'Money'=>$this->input->post('Money'),
                'Remarks'=>$this->input->post('Remarks'),
                'ModDate'=>date('Y-m-d H:i:s')
            );
            $this->load->model('cardfee_model','cardfee');
            $status=$this->cardfee->insert($data);
            if($status){
                $msg='新增水费记录成功！';
                $url='cardfee/feelist/'.$ClientID;
                success($url, $msg);
            }

        }else{
            $this->load->view('header.html');
            $this->load->view('fee/water/addfee.html');
            $this->load->view('footer.html');
        }

    }



    public function update()
    {
        $this->load->library('form_validation');//加载表单验证类库
        $this->form_validation->set_rules('CardNo', '水卡号码', 'required');//设置验证规则
        $this->form_validation->set_rules('Money', '缴费金额', 'required|numeric');//设置验证规则
        $status = $this->form_validation->run();//执行验证
        if ($status) {
            $FeeID=$this->input->post('FeeID');
            $data=array(
                'CardNo'=>$this->input->post('CardNo'),
                'Money'=>$this->input->post('Money'),
                'Remarks'=>$this->input->post('Remarks'),
                'ModDate'=>date('Y-m-d H:i:s')
            );
            $this->load->model('cardfee_model','cardfee');
            $status=$this->cardfee->update($data,$FeeID);
            if($status){
                $msg='记录更新成功！';
                $url='cardfee/showfee/'.$FeeID;
                success($url, $msg);
            }
        }else{
            $this->load->view('header.html');
            $this->load->view('fee/water/editfee.html');
            $this->load->view('footer.html');
        }
    }
}