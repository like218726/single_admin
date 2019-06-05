<?php

namespace app\admin\controller;

use think\Request;

class Log extends Base {
	
	/**
	 * 
	 * 列表
	 * 
	 */
    public function index() {
        $is_admin = 1;
        $user_administrator = config('USER_ADMINISTRATOR');
        if (is_array($user_administrator) && !empty($user_administrator)) {
        	if (!in_array(session('uid'), $user_administrator)) {
        		$where['uid'] = session('uid');
        		$is_admin = 0;
        	} 
        }
        
        if (!$user_administrator) {
        	$this->userInfo['username'] = trim(strtolower($this->userInfo['username']));
        	if ($this->userInfo['username'] !='admin') {
        		$where['uid'] = session('uid');
        		$is_admin = 0;
        	}
        }     
        $this->assign("is_admin", $is_admin);	
        return view("Log/index");
    }

    /**
     * 
     * AJAX查询列表
     * 
     */
    public function ajaxGetIndex() {
    	
   		$start = input('get.start','0','trim') ? input('get.start','0','trim') : 0;
        $limit = input('get.length', '0', 'trim') ? input('get.length', '0', 'trim') : 20;
        $draw = input('get.length', '0', 'trim');
        $where = array();

        $is_admin = 1;
        $user_administrator = config('USER_ADMINISTRATOR');
        if (is_array($user_administrator) && !empty($user_administrator)) {
        	if (!in_array(session('uid'), $user_administrator)) {
        		$where['uid'] = session('uid');
        		$is_admin = 0;
        	} 
        }
        
        if (!$user_administrator) {
        	$this->userInfo['username'] = trim(strtolower($this->userInfo['username']));
        	if ($this->userInfo['username'] !='admin') {
        		$where['uid'] = session('uid');
        		$is_admin = 0;
        	}
        }    
            
        $getInfo = input('get.');
        if (isset($getInfo['type']) && !empty($getInfo['type'])) {
            if (isset($getInfo['keyword']) && !empty($getInfo['keyword'])) {
                switch ($getInfo['type']) {
                    case 1:
                        $where['url'] = array('like', '%' . trim($getInfo['keyword']) . '%');
                        break;
                    case 2:
                        $where['nickname'] = array('like', '%' . trim($getInfo['keyword']) . '%');
                        break; 
                    case 3:
                        $where['uid'] = trim($getInfo['keyword']);
                        break;
                }
            }
        }
        if (!empty($getInfo['add_time'])) {
            $create_time =  explode(' - ', $getInfo['add_time']);
            $start_time = strtotime(trim($create_time[0]));
            $end_time = strtotime(trim($create_time[1]));
            $where['addTime'] = ['between',[$start_time,$end_time]];
        }
  
        $total = model('AdminUserAction')->where($where)->count();
        $info = model('AdminUserAction')->where($where)->limit($start, $limit)->order("addTime desc")->select();

        $data = array(
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $info
        );
        $this->assign("is_admin", $is_admin);
        return json($data);
    }

    /**
     * 
     * 删除日志
     * 
     */
    public function del() {
        $id = input('post.id');
        $res = model('AdminUserAction')->where(array('id' => $id))->delete();
        if ($res === false) {
            return $this->ajaxError('操作失败');
        } else {
            return $this->ajaxSuccess('操作成功');
        }
    }

    /**
     * 
     * 批量删除日志
     */
    public function batchDelete()
    {
        $id = rtrim(input('post.id/s'),',');
        $arrId = explode(',', $id);

        $ids = [];
        foreach ($arrId as $val) {
            $data = model('AdminUserAction')->where('id', $val)->find();
            if($data){
                $ids[] = $val;
            }
        }

        if(empty($ids))
        {
            return $this->ajaxError('操作失败');
        }else{
            model('AdminUserAction')->where('id', 'in', $ids)->delete();
            return $this->ajaxSuccess('批量删除成功');
        }
    }
}