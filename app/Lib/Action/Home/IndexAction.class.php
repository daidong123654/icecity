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
            var_dump($_POST);die;
            $data['name'] = $_POST['name'];
            $data['studentid'] = $_POST['sid'];
            $data['gid'] = $_POST['subGroup'];
            $data['courseid'] = $_POST['course'];
            $data['date'] = date('Y-m-d H:i:s');
            
            //$data = array('name'=>'dd','studentid'=>'1','gid'=>'3','courseid'=>'1','date'=>date('Y-m-d'));
            //今天有没有下载
            $isqiandao = M('qiandao')->where(array('studentid'=>$data['studentid'],'course'=>$data['courseid'],'date'=>$data['date']))->find();
            //echo M('qiandao')->getLastSql();
            if($isqiandao){
                echo json_encode(array('status'=>'-1','msg'=>'您今天已签到，请勿重复签到！'));
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
            }
            //得到mac
            $data['mac'] = "00:00:00:00:00";
            
            // 保存
            if(M('qiandao')->add($data)){
                echo json_encode(array('status'=>'0','msg'=>'签到成功！'));
                return ;
            }else{
                echo json_encode(array('status'=>'-2','msg'=>'Unexpected Error!'));
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
            
            $this->display('form');
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

/** 
 *获取网卡的MAC地址原码；目前支持WIN/LINUX系统 
 *获取机器网卡的物理（MAC）地址 
 **/ 
/*
class GetMacAddr{ 

    var $return_array = array(); // 返回带有MAC地址的字串数组 
    var $mac_addr; 

    function GetMacAddr($os_type){ 
        switch ( strtolower($os_type) ){ 
        case "linux": 
            $this->forLinux(); 
            break; 
        case "solaris": 
            break; 
        case "unix": 
            break; 
        case "aix": 
            break; 
        default: 
            $this->forWindows(); 
            break; 

        } 


        $temp_array = array(); 
        foreach ( $this->return_array as $value ){ 

            if ( 
                preg_match("/[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f]/i",$value, 
                $temp_array ) ){ 
                    $this->mac_addr = $temp_array[0]; 
                    break; 
                } 

        } 
        unset($temp_array); 
        return $this->mac_addr; 
    } 


    function forWindows(){ 
        @exec("ipconfig /all", $this->return_array); 
        if ( $this->return_array ) 
            return $this->return_array; 
        else{ 
            $ipconfig = $_SERVER["WINDIR"]."\system32\ipconfig.exe"; 
            if ( is_file($ipconfig) ) 
                @exec($ipconfig." /all", $this->return_array); 
            else 
                @exec($_SERVER["WINDIR"]."\system\ipconfig.exe /all", $this->return_array); 
            return $this->return_array; 
        } 
    } 



    function forLinux(){ 
        @exec("ifconfig -a", $this->return_array); 
        return $this->return_array; 
    } 

} 
//方法使用
$mac = new GetMacAddr(PHP_OS); 
echo $mac->mac_addr; */
