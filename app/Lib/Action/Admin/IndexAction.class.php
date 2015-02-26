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
    	$ip = get_client_ip();
    	$ipwhite = array('127.0.0.1','localhost','::1','0.0.0.0');
        if(!in_array($ip,$ipwhite)){
            exit('非法');
        }else{
            $this->display();
        }
        
    }
    /*****************课程**************************/
    //课程设置
    public function course(){
    	$course = M('course')->select();
    	$this->assign('course',$course);
    	$this->display();
    }
    //新增
    public function courseAdd(){
    	if(IS_POST){
    		$data['name'] = $this->_post('name');
    		$data['starttime'] = $this->_post('starttime');
    		$data['scorerule'] = $this->_post('scorerule');
    		$data['teacher'] = $this->_post('teacher');
    		$id = M('course')->add($data);
    		if($id){
    			//echo json_encode(array('status'=>1,'msg'=>'Success!'));
    			echo "添加成功";
    		}else{
    			//echo json_encode(array('status'=>0,'msg'=>'Failed!'));
    			//echo "Failed";
    			echo "添加失败";

    		}
    		return;
    	}else{
    		$this->display();
    	}
    }

    //课程修改
    public function courseEdit(){
    	$cid = $this->_get('cid');
    	if(IS_POST){
    		$data['name'] = $this->_post('name');
    		$data['starttime'] = $this->_post('starttime');
    		$data['scorerule'] = $this->_post('scorerule');
    		$data['teacher'] = $this->_post('teacher');
    		$rid = M('course')->where(array('id'=>$cid))->save($data);
    		if($rid){
    			//echo json_encode(array('status'=>1,'msg'=>'Success!'));
    			echo "修改成功";
    		}else{
    			//echo json_encode(array('status'=>0,'msg'=>'Failed!'));
    			//echo "Failed";
    			echo "修改失败";
    		}
    		return;
    	}else{
    		$course = M('course')->where(array('id'=>$cid))->find();
    		$this->assign('course',$course);
    		$this->display();
    	}
    }

    //课程修改
    public function courseDel($id){
    	if($id){
    		$rid = M('course')->where(array('id'=>$id))->delete();
    		if($rid){
    			echo json_encode(array('status'=>0,'msg'=>'Success!'));
    		}else{
    			echo json_encode(array('status'=>-1,'msg'=>'Failed!'));
    		}
    	}else{
    		echo json_encode(array('status'=>-2,'msg'=>'Failed!'));	
    	}
    	return;
    }

    
    private function checkLogin(){
    	if(session('adminLogin')!='10086'){
    		$this->redirect('Admin/Login/index');
    	}else{
    		return true;
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

