<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/26
 * Time: 17:25
 */
class Login extends CI_Controller{


    public function index(){
        $this->load->helper('form');
        if(!isset($_SESSION)){
            session_start();//开启session,只有开启session后，所有session操作才能有效
        }
        $data['title']='用户登录窗口';
        $this->load->view('header.html');
        $this->load->view('login.html');
        $this->load->view('footer.html');
        // var_dump($_SESSION);
    }
    public function captcha(){
        $config=array(
            'width'=>80,
            'height'=>30,
            'codeLen'=>4,
            'fontSize'=>18,
            'bgColor' =>'',
            'fontColor'=> ''
        );
        $this->load->library('code',$config);//加载自定义的验证码库
        $this->code->show();
        //  var_dump($_SESSION);
    }

    public function chkuser(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('usrid',"登录账号",'required');
        $this->form_validation->set_rules('pwd',"登录密码",'required');
        $this->form_validation->set_rules('captcha',"验证码",'required');
        $status=$this->form_validation->run();
        if($status){
            $usrid=$this->input->post('usrid');
            $pwd=$this->input->post('pwd');
            $captcha=strtoupper($this->input->post('captcha'));

            if(!isset($_SESSION)){
                session_start();//开启session,只有开启session后，所有session操作才能有效
            }
            if($captcha!=$_SESSION ['code']){
                error('验证码错误！');
            }
            $this->load->model('clientinfo_model','clientinfo');
            $data=$this->clientinfo->chkuser($usrid,$pwd);
            if(!empty($data)){
                //使用CI框架的session类存储session信息更安全
                $session_data=array(
                    'usrid'=>$data[0]['usrid'],
                    'usrname'=>$data[0]['usrname'],
                    'logintime'=>time()
                );
                $this->session->set_userdata($session_data);//写入系统变量中，使用$this->session->userdata('usrid')即可。
                $msg='登录成功！';
                $url='/';
                success($url, $msg);
            }else{
                error('登录账号或密码错误！');
            }
        }else{
            $this->load->view('header.html');
            $this->load->view('login.html');
            $this->load->view('footer.html');
        }

    }
}