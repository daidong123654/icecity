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
        $this->display();
    }

    public function test(){
    	echo 19;
    	$this->display();
    }

    public function newtest(){
        $this->display();
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
