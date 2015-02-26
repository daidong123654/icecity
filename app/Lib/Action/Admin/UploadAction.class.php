<?php
/**
 * 
 */

class UploadAction extends BaseAction
{

    function __construct()
    {
        parent::__construct();
        $this->checkLogin();
    }

    public function index(){
        import('ORG.UploadFile');
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath =  './Uploads/kejian/';// 设置附件上传目录
        if(!$upload->upload()) {// 上传错误提示错误信息
            echo json_encode(array('error'=>-1,'url'=>'ERROR'));
        }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
            $url = C('site_url').'Uploads/kejian/'.$info['0']['savename'];
            echo json_encode(array('error'=>0,'url'=>$url));
        }
        // 保存表单数据 包括附件数据
    }

    public function kejian(){
        import('ORG.UploadFile');
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath =  './Uploads/kejian/';// 设置附件上传目录
        if(!$upload->upload()) {// 上传错误提示错误信息
            echo json_encode(array('error'=>-1,'url'=>'ERROR'));
        }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
            $url = C('site_url').'Uploads/kejian/'.$info['0']['savename'];
            echo json_encode(array('error'=>0,'url'=>$url));
        }

    }

    private function checkLogin(){
        if(session('adminLogin')!='10086'){
            echo json_encode(array('error'=>-1,'url'=>'ERROR PLEAST LOGIN!'));
            die;
        }else{
            return true;
        }
    }
}

