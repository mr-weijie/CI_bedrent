<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Home extends CI_Controller{

public function index(){
    $this->listclient();
}
public function listclient(){
    $Valid=$this->uri->segment(3);//取出URL中第三段
    if(!isset($Valid)) $Valid='1';//默认为有效1
    $this->load->model('clientinfo_model','clientinfo');
    $data['clientinfo']=$this->clientinfo->listclient($Valid);
    $data['Valid']=($Valid==1) ? '在住':'搬离';
    $data['title']=$data['Valid'].'房客列表';
    $this->load->view('header.html',$data);
    $this->load->view('client/listclient.html');
    $this->load->view('footer.html');

}
public function showclient(){
    $clientID=$this->uri->segment(3);//取出URL中第三段
    $this->load->model('clientinfo_model','clientinfo');
    $data['clientinfo']=$this->clientinfo->getclientinfo($clientID);
    $data['title']='房客个人信息';
 //   p($data);
    $this->load->view('header.html',$data);
    $this->load->view('client/showclient.html');
    $this->load->view('footer.html');
}

public function editclient(){
    $this->load->helper('form');//为了能显示后台返回来的出错信息，在这里载入form辅助函数。
    $clientID=$this->uri->segment(3);//取出URL中第三段
    $this->load->model('clientinfo_model','clientinfo');
    $data['clientinfo']=$this->clientinfo->getclientinfo($clientID);
    $data['title']='编辑房客个人信息';
    $this->load->view('header.html',$data);
    $this->load->view('client/editclient.html');
    $this->load->view('footer.html');
}
public function addclient(){
    $data['title']='新增房客信息';
    $this->load->helper('form');
    $this->load->view('header.html',$data);
    $this->load->view('client/addclient.html');
    $this->load->view('footer.html');
}

public function insertclient(){
    $this->load->library('form_validation');//加载表单验证类库
    $this->form_validation->set_rules('ClientName','房客姓名','required');//设置验证规则
    $this->form_validation->set_rules('IdentityID','身份证号','required|exact_length[18]');
    $this->form_validation->set_rules('BedNo','床位号','required');
    $this->form_validation->set_rules('CardNo','水卡号','required');
    $this->form_validation->set_rules('PhoneNo','本人联系电话','required');
    $this->form_validation->set_rules('Deposit','押金','required|numeric');
    $this->form_validation->set_rules('StartDate','开始日期','required');
    $this->form_validation->set_rules('StopDate','终止日期','required');
    $status = $this->form_validation->run();//执行验证
    if($status){
        $IdentityID=$this->input->post('IdentityID');
        $ClientID=strtoupper(md5($IdentityID.date("Y-m-d H:i:s")));//采用系统时间+IdentityID的方法'
        $data=array(
            'ClientID'=>$ClientID,
            'ClientName'=>$this->input->post('ClientName'),
            'IdentityID'=>$IdentityID,
            'BedNo'=>$this->input->post('BedNo'),
            'CardNo'=>$this->input->post('CardNo'),
            'PhoneNo'=>$this->input->post('PhoneNo'),
            'ContactPhoneNo'=>$this->input->post('ContactPhoneNo'),
            'ContactRelation'=>$this->input->post('ContactRelation'),
            'Deposit'=>$this->input->post('Deposit'),
            'StartDate'=>$this->input->post('StartDate'),
            'StopDate'=>$this->input->post('StopDate'),
            'Remarks'=>$this->input->post('Remarks'),
            'Valid'=>$this->input->post('Valid'),
            'ModDate'	=> mdate('%Y-%m-%d %h:%i:%s', time())
        );
        $this->load->model('clientinfo_model','clientinfo');
        $status=$this->clientinfo->insertclient($data);
        if($status){
            $msg='新增房客记录成功！';
            $url='home/';
            success($url, $msg);
        }

    }else{
        $this->load->helper('form');//为了能显示后台返回来的出错信息，在这里载入form辅助函数。
        $this->load->view('header.html');
        $this->load->view('client/addclient.html');
        $this->load->view('footer.html');
    }

}



public  function updateclient(){
    $this->load->library('form_validation');//加载表单验证类库
    $this->form_validation->set_rules('ClientName','房客姓名','required');//设置验证规则
    $this->form_validation->set_rules('IdentityID','身份证号','required|exact_length[18]');
    $this->form_validation->set_rules('BedNo','床位号','required');
    $this->form_validation->set_rules('CardNo','水卡号','required');
    $this->form_validation->set_rules('PhoneNo','联系电话','required');
    $this->form_validation->set_rules('Deposit','押金','required|numeric');
    $this->form_validation->set_rules('StartDate','开始日期','required');
    $this->form_validation->set_rules('StopDate','终止日期','required');
    $status = $this->form_validation->run('form1');//执行验证
    if($status){
        $ClientID= $this->input->post('ClientID');
        $data = array(
            'ClientName'	=> $this->input->post('ClientName'),
            'IdentityID'	=> $this->input->post('IdentityID'),
            'BedNo'	=> $this->input->post('BedNo'),
            'CardNo'=> $this->input->post('CardNo'),
            'PhoneNo'=> $this->input->post('PhoneNo'),
            'ContactPhoneNo'=> $this->input->post('ContactPhoneNo'),
            'ContactRelation'=> $this->input->post('ContactRelation'),
            'StartDate'=> $this->input->post('StartDate'),
            'StopDate'=> $this->input->post('StopDate'),
            'Deposit'=> $this->input->post('Deposit'),
            'Remarks'=> $this->input->post('Remarks'),
            'Valid'=> $this->input->post('Valid'),
            'ModDate'	=> date('Y-m-d H:i:s')
        );
        $this->load->model('clientinfo_model','client');
        $status=$this->client->updateclientinfo($ClientID,$data);
       // var_dump($status);//打印变量值并停止向下运行
        if($status){
            $msg='记录更新成功！';
            $url='home/showclient/'.$ClientID;
            success($url, $msg);
        }
    }else
    {
        $this->load->helper('form');//为了能显示后台返回来的出错信息，在这里载入form辅助函数。
        $this->load->view('header.html');
        $this->load->view('client/addclient.html');
        $this->load->view('footer.html');
    }

}
public function upload(){
    //先配置信息
$clientID=$this->uri->segment(3);//取出URL中第三段
$config['upload_path']='./photos/';
$config['allowed_types']='gif|jpg|png|jpeg';
$config['overwrite']=true;//遇到同名的覆盖
//$config['file_name']=time().mt_rand(1000,9999);
$config['file_name']=$clientID;//用clientID做为图片文件名
//载入上传类
$this->load->library('upload',$config);
$status=$this->upload->do_upload('upfile');//此外的参数必须与表单中的文件字段名字相同
    if($status){
        $photofile=$this->upload->data('file_name');//返回已保存的文件名
        $this->load->model('clientinfo_model','clientinfo');
        $this->clientinfo->uploadfile($clientID,$photofile);
        redirect(site_url('/home/userinfo/'.$clientID));
    }else
    {
        error('请正确选择图片后再上传！');
    }
}
}
?>