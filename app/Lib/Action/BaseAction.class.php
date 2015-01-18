<?php
class BaseAction extends Action{
	public function __construct(){
		parent::__construct();
		define('RES',THEME_PATH.'common');
		define('STATICS',C('site_url').'/'.APP_NAME.'/tpl/'.'static');

	}


}