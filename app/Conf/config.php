<?php
return array(
	//'配置项'=>'配置值'
	'LOAD_EXT_CONFIG'       => 'info',
	// 添加数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   	 => 'icecity', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'daidong', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'apms_', // 数据库表前缀
	'DEFAULT_THEME'  => 'default',
 	'TMPL_DETECT_THEME' => true, // 自动侦测模板主题
	
	'APP_GROUP_LIST' => 'Home,Admin,User',
	'DEFAULT_GROUP'  => 'Home',
	'DEFAULT_MODULE' => 'Index',
	'DEFAULT_ACTION' => 'index',
	'TMPL_L_DELIM'          =>'{apms:',         //模板引擎普通标签开始标记
	'TMPL_R_DELIM'          =>'}',              //模板引擎普通标签结束标记


	//默认错误跳转对应的模板文件
	//'TMPL_ACTION_ERROR' => THINK_PATH . 'Tpl/dispatch_jump.tpl',
	//默认成功跳转对应的模板文件
	//'TMPL_ACTION_SUCCESS' => THINK_PATH . 'Tpl/dispatch_jump.tpl',
	//'SHOW_PAGE_TRACE' =>true, // 显示页面Trace信息

	//URL
	'URL_CASE_INSENSITIVE' =>TRUE,
	'URL_HTML_SUFFIN'=>'html',//url後綴  或用U('index/index',array('uid'=>1,'id'=>2),'htm',1)第三個參數為後綴，第四個為是否跳轉
	//URL重写
	/*'URL_MODEL'=>2,
	'URL_ROUTER_ON'=>true,
	'URL_ROUTE_RULES'=>array(
		'/^c_(\d+)$/'=>'Index/List/index?id=:1',
		':id\d'=>'Index/Show/'
		),*/
	//模板路径
	 //'TMPL_FILE_DEPR'=>'_',
	 //'TMPL_TEMPLATE_SUFFIX'=>'.html',//模板後綴

	
	
);

?>