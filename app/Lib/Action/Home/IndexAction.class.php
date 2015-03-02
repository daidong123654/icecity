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
    //签到方法
    public function index(){
        //签到，处理数据
        if(IS_POST){
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
            
            //签到成功 保存
            $qiandaoid = M('qiandao')->add($data);
            if($qiandaoid){
                //session('sid',$data['studentid']);
                //session('name',$data['name']);
                session(array('name'=>'session_id','expire'=>7200));
                session('sid',$data['studentid']);
                session('name',$data['name']);
                session('isreg','10086');
                session('qiandaoid',$qiandaoid);
                session('courseid',$data['courseid']);
                session('isreg',10086);
                echo json_encode(array('status'=>'0','msg'=>'<p>签到时间:'.$data['time'].'</p><p>签到得分:'.$data['score'].'</p>'));
                return ;
            }else{
                echo json_encode(array('status'=>'-2','msg'=>'未知错误，请联系老师签到。'));
                return ;
            }
        }else{
            //未签到
            //session('isreg',null);
            if(!session('isreg')){
                $course = M('Course')->select();
                /*foreach ($course as $key => $value) {
                  $group = $this->getClassGroup($value);
                  $course[$key]['Group'] = $group;
                }*/
                $courseJson = urldecode(json_encode($this->url_encode($course)));
                $this->assign('CourseJson',$courseJson);
                $this->assign('Course',$course);
                
                $this->display('bootstrapform');    
            }else{
                //已签到
                $qiandaoid = session('qiandaoid');
                $qiandaoinfo = M('qiandao')->where(array('id'=>$qiandaoid))->find();
                if(!empty($qiandaoinfo)){
                    $course = M('course')->where(array('id'=>$qiandaoinfo['courseid']))->find();
                    $qiandaoinfo['course'] = $course;
                }
                //var_dump($qiandaoinfo);
                $this->assign('qiandaoinfo',$qiandaoinfo);
                $this->display('hasreged');
            }
        }
    }

    //获取课程知识点
    public function infoList($courseid=false){
        //没有签到,列出全部课程的知识点
        if(!$courseid){
            $courses = M('course')->select();
            $list = array();
            foreach ($courses as $key => $value) {
                //var_dump($value);
                //array_push($list, C('site_url').U('Home/Index/infoList').'/courseid/'.$value['id']);
                $list[$key]['course'] = $value;
                $list[$key]['url'] = U('Home/Index/infoList').'/courseid/'.$value['id'];
            }
            $this->assign('courseid',false);
            $this->assign('list',$list);
            $this->display();
        }else{
            //已经签到,直接列出本课程的知识点
            //var_dump($courseid);
            $course = M('course')->where(array('id'=>$courseid))->find();
            $coursename = $course['name'];
            $this->assign('coursename',$coursename);
            $this->assign('courseid',$courseid);

            $list = array();
            $infos = M('kejian')->field('id,title,courseid')->where(array('courseid'=>$courseid))->order('indexid asc')->select();
            foreach ($infos as $key => $value) {
                $list[$key]['title']=$value['title'];
                $list[$key]['url'] = U('Home/Index/infoDetail').'/kejianid/'.$value['id'];
            }
            $this->assign('list',$list);
            $this->display();
        }
    }

    public function infoDetail($kejianid=false){
        //if(!$kejianid){

        //}else{
            $kejian = M('kejian')->where(array('id'=>$kejianid))->find();
            $this->assign('kejian',$kejian);

            $course = M('course')->where(array('id'=>$kejian['courseid']))->find();
            $coursename = $course['name'];
            $this->assign('coursename',$coursename);
            $this->assign('courseid',$course['id']);

            $this->display();
        //}
    }

    //随堂测验
    public function test(){
        $courseid = $this->_get('courseid');

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

    //递归将数组每项urlencode,辅助函数
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

