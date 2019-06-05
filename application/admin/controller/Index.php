<?php

namespace app\admin\controller;

class Index extends Base { 
	
	/**
	 * 
	 * 首页
	 * 
	 */
    public function index() {
        $isAdmin = isAdministrator(); 
        $list = array();
        $menuAll = $this->allMenu;

        foreach ($menuAll as $menu) {
            if ($menu['hide'] == 0) {
                if ($isAdmin) {
                    $menu['url'] = $menu['url'] ? url($menu['url']) : '';
                    $list[] = $menu;
                } else {
                    $authObj = new Auth();
                    $authList = $authObj->getAuthList($this->uid);
                    if (in_array(strtolower($menu['url']), $authList) || $menu['url'] == '') {
                        $menu['url'] = $menu['url'] ? url($menu['url']) : '';
                        $list[] = $menu;
                    }
                }
            }
        } ;

        $list = listToTree($list);
        foreach ($list as $key => $item) {
            if(empty($item['_child']) && empty($item['url'])){
                unset($list[$key]);
            }
        }
        $list = formatTree($list);
        return $this->fetch("Index/index",["list"=>$list, 'username'=>$this->userInfo['username'], 'nickname'=>$this->userInfo['nickname']]);
    }
    
    /**
     * 
     * 欢迎页面
     * 
     */
    public function welcome(){
    	echo '欢迎使用<b>'.config('APP_NAME').'</b>管理系统';
    }
}