<?php
/**
 * 
 */

class IndexAction extends BaseAction
{

    function __construct()
    {
        parent::__construct();
    }

    public function index(){
        if(IS_POST){
        //if(1){
            
            $data['name'] = $_POST['name'];
            $data['studentid'] = $_POST['sid'];
            $data['pgid'] = $_POST['parentGroup'];
            $data['sgid'] = $_POST['subGroup'];
            $data['courseid'] = $_POST['courseid'];
            $data['date'] = date('Y-m-d');
            $data['time'] = date('Y-m-d H:i:s');
            $data['ip'] = get_client_ip();
            //var_dump($data);
            //$data = array('name'=>'dd','studentid'=>'1','gid'=>'3','courseid'=>'1','date'=>date('Y-m-d'));
            //今天有没有
            $isqiandao = M('qiandao')->where(array('studentid'=>$data['studentid'],'courseid'=>$data['courseid'],'date'=>$data['date']))->find();
            //echo M('qiandao')->getLastSql();
            if($isqiandao){
                echo json_encode(array('status'=>'-1','msg'=>'您今天已签到，请勿重复签到！'));
                return ;
            }

            //IP鉴定
            $ipuse = M('qiandao')->where(array('courseid'=>$data['courseid'],'date'=>$data['date'],'ip'=>$data['ip']))->find();
            //echo M('qiandao')->getLastSql();
            if($ipuse){
                echo json_encode(array('status'=>'-2','msg'=>'您今天已签到，请勿替别人签到！'));
                return ;
            }

            
            //得到上课时间，计算分数
            $thisCourse = M('course')->where(array('id'=>$data['courseid']))->find();
            $now = time();
            $startarr = explode(':', $thisCourse['starttime']);
            $courseStartTime = mktime($startarr[0],$startarr[1],'00',date('m'),date('d'),date('Y'));

            $late = $now - $courseStartTime;
            
            if($late>0){
                $latescore = intval($late/(60*$thisCourse['scorerule']));
                $data['score'] = 100 - $latescore;
                $data['score'] = $data['score']>=0?$data['score']:0;
            }
            //得到mac
            $data['mac'] = "00:00:00:00:00:00";
            
            // 保存
            if(M('qiandao')->add($data)){
                echo json_encode(array('status'=>'0','msg'=>'<p>签到时间:'.$data['time'].'</p><p>签到得分:'.$data['score'].'</p>'));
                return ;
            }else{
                echo json_encode(array('status'=>'-2','msg'=>'未知错误，请联系老师签到。'));
                return ;
            }
        }else{
            $course = M('Course')->select();
            foreach ($course as $key => $value) {
              $group = $this->getClassGroup($value);
              $course[$key]['Group'] = $group;
            }
            $courseJson = urldecode(json_encode($this->url_encode($course)));
            $this->assign('CourseJson',$courseJson);
            $this->assign('Course',$course);
            
            $this->display('bootstrapform');
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

    public function db(){
        $this->display();
    }


    public function test(){
    	$this->display();
    }

    public function newtest(){
        $this->display();
    }

    public function login(){
        if(IS_POST){
            $studentid = $this->_post('sid');
            $password = $this->_post('pass');
            $r = M('Login')->where(array('studentid'=>$sid,'password'=>md5($password)))->find();
            if($r){
                session('sid',$sid);   
            }
        }else{
            if(session('sid')){
                $this->display('main');
            }else{
                $this->display('login');
            }
        }
    }

    public  function ha(){
        echo "<br/>你们不要总是想弄个大新闻！然后再把我批判一番！据说已经钦定啦。今天我在这里，作为一个长者，我跟你们新闻界的说，你门呀，毕竟too young to simple,sometimes naive!";
    }
}

