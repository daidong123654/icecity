<?php
/**
 * 
 */

class IndexAction extends BaseAction
{

    function __construct()
    {
        parent::__construct();
        $this->checkLogin();
    }

    public function index(){
        if(IS_POST){
            $data['ip'] = get_client_ip();
            
        }else{
            
        }
        
    }

    private function checkLogin(){
    	if(!session('adminLogin')!='10086'){
    		$this->display('login');
    	}else{
    		return true;
    	}
    }

    public function Login(){
    	if(IS_POST){

    	}else{
    		$this->display('login');
    	}
    }
    //根据class递归得到其分组
    private function getClassGroup($class){
        $parentGroup = M('Group')->where(array('classid'=>$class['id'],'parentid'=>'0'))->select();
        foreach ($parentGroup as $key => $value) {
            if(!$value['parentid']){
                $subGroup = M('Group')->where(array('parentid'=>$value['id']))->order(' id asc ')->select();
                $parentGroup[$key]['subGroup'] = $subGroup;
            }
        }
        return $parentGroup;
    }

    //递归将数组每项urlencode
    private function url_encode($str){
         if(is_array($str)) {
             foreach($str as $key=>$value) {
                 $str[urlencode($key)] = $this->url_encode($value);
             }
         } else {
             $str = urlencode($str);
         }
         return $str;
    }

    
}

