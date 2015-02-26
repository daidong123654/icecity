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

    /*****************学生**************************/
    public function student(){
    	$courseid = $this->_get('courseid');
    	$date = $this->_get('date');
    	if($courseid||$date){
    		$where = array();
    		if($courseid)
    			$where['courseid'] = $courseid;
    		if($date)
    			$where['date'] = $date;
    		if(!empty($where)){
    			$students = M('qiandao')->where($where)->order('id desc')->select();	
    		}else{
    			$students = M('qiandao')->where(array('date'=>date('Y-m-d')))->order('id desc')->select();	
    		}
    	}else{
    		$students = M('qiandao')->where(array('date'=>date('Y-m-d')))->order('id desc')->select();	
    	}
    	foreach ($students as $key => $value) {
    		$students[$key]['course'] = M('course')->where(array('id'=>$value['courseid']))->order('id desc')->find();
    	}
    	$this->assign('students',$students);

    	$courses = M('course')->select();
    	$this->assign('courses',$courses);
    	//var_dump($students);
    	$this->display();
    }

    //新增
    public function studentAdd(){
    	if(IS_POST){
    		$data['name'] = $_POST['name'];
            $data['studentid'] = $_POST['sid'];
            $data['pgid'] = $_POST['parentGroup'];
            $data['sgid'] = $_POST['subGroup'];
            $data['courseid'] = $_POST['courseid'];
            $data['date'] = date('Y-m-d');
            $data['time'] = date('Y-m-d H:i:s');
            $data['ip'] = get_client_ip();
            $data['score'] = $_POST['score'];

            $isreg = M('qiandao')->where(array('studentid'=>$data['studentid'],'date'=>$data['date'],'courseid'=>$data['courseid']))->find();

            if(!$isreg){
                $id = M('qiandao')->add($data);    
                if($id){
                    echo json_encode(array('status'=>'0','msg'=>'<p>签到时间:'.$data['time'].'</p><p>签到得分:'.$data['score'].'</p>'));
                }else{
                    echo json_encode(array('status'=>'-1','msg'=>'<p>签到失败！</p>'));                    
                }
            }else{
                echo json_encode(array('status'=>'-1','msg'=>'<p>重复签到！</p>'));
            }
    		return;
    	}else{
    		$course = M('Course')->select();
            foreach ($course as $key => $value) {
              $group = $this->getClassGroup($value);
              $course[$key]['Group'] = $group;
            }
            $courseJson = urldecode(json_encode($this->url_encode($course)));
            $this->assign('CourseJson',$courseJson);
            $this->assign('Course',$course);
            $this->display();    
    	}
    }

    //修改
    public function studentEdit(){
        $qid = $this->_get('qid');
        if(IS_POST){
            $data['name'] = $_POST['name'];
            $data['studentid'] = $_POST['sid'];
            $data['pgid'] = $_POST['parentGroup'];
            $data['sgid'] = $_POST['subGroup'];
            $data['courseid'] = $_POST['courseid'];
            $data['ip'] = get_client_ip();
            $data['score'] = $_POST['score'];
            
            $id = M('qiandao')->where(array('id'=>$qid))->save($data);  

            if($id){
                echo json_encode(array('status'=>'0','msg'=>'<p>修改成功:'.date('Y-m-d H:i:s')));
            }else{
                echo json_encode(array('status'=>'-1','msg'=>'<p>修改失败！</p>'));                    
            }
            return;
        }else{

            $course = M('Course')->select();
            foreach ($course as $key => $value) {
              $group = $this->getClassGroup($value);
              $course[$key]['Group'] = $group;
            }
            $courseJson = urldecode(json_encode($this->url_encode($course)));
            $this->assign('CourseJson',$courseJson);
            $this->assign('Course',$course);

            
            $qiandao = M('qiandao')->where(array('id'=>$qid))->find();
            $this->assign('qiandao',$qiandao);

            //得到初始化之后用户的分组信息
            foreach ($course as $key => $value) {
                if($value['id']==$qiandao['courseid']){
                    $initpg = $value;
                    $this->assign('initpg',$initpg);
                    foreach ($value['Group'] as $gkey => $gvalue) {
                        if($gvalue['id']==$qiandao['pgid']){
                            $initsubg = $gvalue;
                            $this->assign('initsubg',$initsubg);
                        }
                    }
                }
            }   
            $this->display();    
        }
    }

    /******************课件管理**************************/
    public function kejian(){

    }

    /******************课件管理**************************/
    public function test(){
        
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

