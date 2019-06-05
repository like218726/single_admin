<?php

namespace app\admin\controller;

class AdminUser extends Base {

    public function index() {
        $listInfo = model('AdminUser')->alias('a')->field('a.id,a.username,a.nickname,a.status,a.last_login,a.last_ip,a.login_count,b.name')
                            ->join('AuthGroup b', 'a.groupId = b.id', 'left')
                            ->order('a.id', 'desc')
                            ->select();  

        foreach ($listInfo as $key => $value) {
            $listInfo[$key]['lastLoginTime'] = $value['last_login'] > 0 ? date('Y-m-d H:i:s', $value['last_login']) : '';
        }

        return $this->fetch("AdminUser/index", ['list'=>$listInfo]);
    }

    /**
     * 
     * 添加
     * 
     */
    public function add() {
        if ($this->request->isPost()) {

            $username = input('post.username', '', 'trim');
            $password = input('post.password', '', 'trim');
            $mobile = input('post.mobile', '', 'trim');
            $groupId = input('post.groupId', '0', 'trim');
            $nickname = input('post.nickname', '', 'trim');
                       
            $user = model('AdminUser')->where(['username'=>$username])->find();
            if(!empty($user)){
                return $this->ajaxError('用户已存在');
            }
			if (!is_mobile($mobile)) {
				return $this->ajaxError('手机号码格式不正确');
			}
			$password == '' ? $password = '123456' : $password;
	
            if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $password)) {	
	    		return $this->ajaxError('用户密码不能含有中文');
	    	}
	    	if (strlen($password)<6 || strlen($password)>18) {
	    		return $this->ajaxError('用户密码长度在6-18位之间');
	    	}   			
			$data = [];
	    	$data['username'] = $username;
	    	$data['nickname'] = $nickname;
	    	$data['mobile'] = $mobile;
            $data['password'] = user_md5($password);
            $data['groupId'] = $groupId;
            $data['regIp'] = $_SERVER['REMOTE_ADDR'];
            $data['regTime'] = time();
            $res = model('AdminUser')->save($data);
            if ($res === false) {
                return $this->ajaxError('操作失败');
            } else {
                return $this->ajaxSuccess('添加成功');
            }
        } else {
            $roles = model('auth_group')->select();
            return $this->fetch("AdminUser/add", ['roles'=>$roles]);
        }
    }

    /**
     * 
     * 编辑
     * 
     */
    public function edit() {
        if ($this->request->isGet()) {
            $id = input('get.id' ,'0', 'trim');
            $detail = model('AdminUser')->where('id', $id)->find();

            $roles = model('auth_group')->select();

            return $this->fetch("AdminUser/add", ['detail'=>$detail, 'username'=>$detail['username'], 'roles'=>$roles]);
        } elseif ($this->request->isPost()) {

            $id = input('post.id', '0', 'trim');
            $username = input('post.username', '', 'trim');
            $password = input('post.password', '', 'trim');
            $mobile = input('post.mobile', '', 'trim');
            $groupId = input('post.groupId', '0', 'trim');
            $nickname = input('post.nickname', '', 'trim');            
            
            if (!empty($password)) {
	            if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $password)) {	
		    		return $this->ajaxError('用户密码不能含有中文');
		    	}
		    	if (strlen($password)<6 || strlen($password)>18) {
		    		return $this->ajaxError('用户密码长度在6-18位之间');
		    	}               	
            }
            
        	if (!is_mobile($mobile)) {
				return $this->ajaxError('手机号码格式不正确');
			}              
          
            $user = model('AdminUser')->where(['username'=>$username])->find();
            if (!empty($user) && $user['id'] != $id){
                return $this->ajaxError('用户已存在');
            }

            $postData = [];
            $postData['username'] = $username;
            $postData['nickname'] = $nickname;
            $postData['mobile'] = $mobile;
            $postData['groupId'] = $groupId;
            $password != '' ? $postData['password'] = user_md5($password) : '';
            $res = model('AdminUser')->save($postData, ['id'=>$id]);

            if ($res === false) {
                return $this->ajaxError('修改失败');
            } else {
                return $this->ajaxSuccess('修改成功');
            }
        } else {
            return $this->ajaxError('非法操作');
        }
    }

    /**
     * 
     * 停用
     * 
     */
    public function close() {
    	if ($this->request->isPost()) {
	        $id = input('post.id', '0', 'trim');
	        $isAdmin = isAdministrator($id);
	        if ($isAdmin) {
	            return $this->ajaxError('超级管理员不可以被操作');
	        }
	        $res = model('AdminUser')->save(['status' => 0], ['id' => $id]);
	        if ($res === false) {
	            return $this->ajaxError('操作失败');
	        } else {
	            return $this->ajaxSuccess('操作成功');
	        }
    	}
    }

    /**
     * 
     * 启用
     * 
     */
    public function open() {
    	if ($this->request->isPost()) {
	        $id = input('post.id', '0', 'trim');
	        $isAdmin = isAdministrator($id);
	        if ($isAdmin) {
	            return $this->ajaxError('超级管理员不可以被操作');
	        }
	        $res = model('AdminUser')->save(['status' => 1], ['id' => $id]);
	        if ($res === false) {
	            return $this->ajaxError('操作失败');
	        } else {
	            return $this->ajaxSuccess('操作成功');
	        }
    	}
    }

    /**
     * 
     * 删除
     * 
     */
    public function del() {
    	if ($this->request->isPost()) {
    	    $id = input('post.id', '0', 'trim');
	        $isAdmin = isAdministrator($id);
	        if ($isAdmin) {
	            return $this->ajaxError('超级管理员不可以被操作');
	        }
	
	        $res = model('AdminUser')->where(array('id' => $id))->delete();
	        if ($res === false) {
	            return $this->ajaxError('操作失败');
	        } else {
	            return $this->ajaxSuccess('操作成功');
	        }    		
    	}

    }
}