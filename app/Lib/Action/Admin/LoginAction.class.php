<?php
/**
 * 
 */

class LoginAction extends BaseAction
{

    function __construct()
    {
        parent::__construct();
    }

    public function Index(){

    	if(IS_POST){
    		//var_dump($_POST);
    		$username = $this->_post('username');
    		$password = $this->_post('password');
    		$admin = M('admin')->where(array('username'=>$username,'password'=>md5($password)))->find();
    		if(!empty($admin)){
    			session('adminLogin',10086);
    			M('admin')->where(array('username'=>$username,'password'=>md5($password)))->save(array('lastlogin'=>date('Y-m-d H:i:s')));
    			$this->redirect('Admin/Index/index','',1, '页面跳转中...');
    			return true;
    		}
    	}
    	$this->display('login');
    }
}

