<?php

namespace app\admin\controller;

class Login extends Base {

	/**
	 * 
	 * 登陆页面
	 * 
	 */
    public function index() {
    	if ($this->request->isGet()) {
        	return $this->fetch("Login/index");
    	}
    }

    /**
     * 
     * 提交登陆
     * 
     */
    public function login() {
    	if ($this->request->isPost()) {
	    	$user = input('post.username', '', 'trim');
	    	$pass = input('post.password', '', 'trim');
	    	
	    	if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $pass)) {	
	    		return $this->ajaxError('登录不能含有中文');
	    	}
	    	if (strlen($pass)<6 || strlen($pass)>18) {
	    		return $this->ajaxError('登录长度在6-18位之间');
	    	}
	
	        $userInfo = model('AdminUser')->where(array('username' => $user, 'password' => user_md5($pass)))->find();
	
	        if (!empty($userInfo)) {
	            if ($userInfo['status']) {
	
	                //保存用户信息和登录凭证
	                cache($userInfo['id'], session_id(), config('ONLINE_TIME'));
	                session('uid', $userInfo['id']);
	
	                //更新用户数据
	                $ip = $this->request->ip();
	
	                $data['login_count'] = $userInfo['login_count'] + 1;
	                $data['last_ip'] = $ip;
	                $data['last_login'] = time();
	                model('AdminUser')->where(array('id' => $userInfo['id']))->update($data);
	
	                return $this->ajaxSuccess('登录成功');
	            } else {
	                return $this->ajaxError('用户已被封禁，请联系管理员');
	            }
	        } else {
	            return $this->ajaxError('用户名密码不正确');
	        }
    	}
    }

    /**
     * 
     * 退出登陆
     * 
     */
    public function logOut() {
    	if ($this->request->isGet()) {
	        cache(session('uid'), null);
	        session('[destroy]');
	        $this->redirect('Login/index');
    	}
    }

    /**
     * 
     * 修改个人信息
     * 
     */
    public function changeUser() {
        if ($this->request->isPost()) {
            $nickname = input('post.nickname', '', 'trim');
            $password = input('post.password', '', 'trim');

            if ($password != '') {
	            if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $password)) {	
		    		return $this->ajaxError('密码不能含有中文');
		    	}
		    	if (strlen($password)<6 || strlen($password)>18) {
		    		return $this->ajaxError('密码长度在6-18位之间');
		    	}            
            }
            
            $row = model("AdminUser")->get(['id'=>session('uid')]);
            
            $newData = array();
            if (!empty($nickname)) {
                $newData['nickname'] = $nickname;
            }
                        
            if ($row['updateTime']==0) {
                $newData['password'] = empty($password) ? user_md5('123456') : user_md5($password);
                $newData['updateTime'] = time();
            } else {
            	if (!empty($password)) {
            		$newData['password'] = user_md5($password);
                	$newData['updateTime'] = time();
            	}
            }

            $res = model('AdminUser')->where(array('id' => session('uid')))->update($newData);

            if ($res === false) {
                return $this->ajaxError('修改失败');
            } else {
                return $this->ajaxSuccess('修改成功');
            }
        } else {
            $userInfo = model('AdminUser')->where(array('id' => session('uid')))->find();
            return $this->fetch("Login/add",["uname"=>$userInfo['username']]);
        }
    }

    /**
     * 
     * 权限提示页
     * 
     */
    public function ruleTip(){
    	if ($this->request->isGet()) {
	        $this->display();
	        return $this->fetch("Login/ruleTip");
    	}
    }

}