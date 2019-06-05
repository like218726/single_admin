<?php
/**
 * Created by PhpStorm.
 * User: hutuo
 * Date: 2018/11/13
 * Time: 09:21
 */

namespace app\admin\controller;

use app\common\datas\Banner as B;

class Banner extends Base {

	/**
	 * 
	 * 列表
	 * 
	 */
    public function index() {
    	if ($this->request->isGet()) {
	        $listInfo = model('Banner')->order('banner_id desc')->select();
			if ($listInfo) {
			    foreach ($listInfo as $key => $value) {
		          	$listInfo[$key]['status'] = B::$_banner['status'][$value['status']];
		        }			
			}
	        return $this->fetch("Banner/index", ['list'=>$listInfo]);
    	}
    }

    /**
     * 
     * 添加
     * 
     */
    public function add() {
        if ($this->request->isPost()) {
            $title = input('post.title', '', 'trim');
            $pic = input('post.pic', '', 'trim');
            $is_url= input('post.is_url', '', 'trim');
            $url = input('post.url', '', 'trim');

            if ($is_url == 1 && !$url) {
            	return $this->ajaxError("url地址必填");
            }
            
            $count = model('Banner')->where(['title'=>$title])->count();
            if ($count) {
            	return $this->ajaxError("标题已存在,请重新填写");
            }
            $data = [];
            $data['title'] = $title;
            $data['pic'] = $pic;
            $data['is_url'] = $is_url;
            $data['url'] = $url;
            $res = model('Banner')->save($data);
            if ($res === false) {
                return $this->ajaxError('操作失败');
            } else {
                return $this->ajaxSuccess('操作成功');
            }
        } else {
            return $this->fetch("Banner/add");
        }
    }

    /**
     * 
     * 上架
     * 
     */
    public function batchOn() {
    	if ($this->request->isPost()) {
    		$ids = input('post.ids', '', 'trim');
    		$ids = substr($ids, 0, -1);
    		
    		$count = model('Banner')->where('banner_id', 'in', $ids)->count();
    		if ($count != count(explode(",", $ids))) {
    			return $this->ajaxError("参数非法");
    		}
    		
    		$result = model('Banner')->where('banner_id', 'in', $ids)->select();
    		foreach ($result as $k=>$v) {
    			if ($v['status'] == 0) {
    				return $this->ajaxError($v['title'].'状态为上架,请重新选择后在上架');
    			}
    		}
    		
    		$res = model('Banner')->save(['status'=>0],['banner_id'=>['in',$ids]]);
    	    if ($res === false) {
                return $this->ajaxError('操作失败');
            } else {
                return $this->ajaxSuccess('操作成功');
            }    		
    	}
    }

    /**
     * 
     * 下架
     * 
     */
    public function batchClose() {
    	if ($this->request->isPost()) {
    		$ids = input('post.ids', '', 'trim');
    		$ids = substr($ids, 0, -1);
    		
    		$count = model('Banner')->where('banner_id', 'in', $ids)->count();
    		if ($count != count(explode(",", $ids))) {
    			return $this->ajaxError("参数非法");
    		}
    		
    		$result = model('Banner')->where('banner_id', 'in', $ids)->select();
    		foreach ($result as $k=>$v) {
    			if ($v['status'] == 1) {
    				return $this->ajaxError($v['title'].'状态为下架,请重新选择后在上架');
    			}
    		}
    		
    		$res = model('Banner')->save(['status'=>1],['banner_id'=>['in',$ids]]);
    	    if ($res === false) {
                return $this->ajaxError('操作失败');
            } else {
                return $this->ajaxSuccess('操作成功');
            }    		
    	}
    }    
    
    /**
     * 
     * 编辑
     * 
     */
    public function edit() {
        if ($this->request->isGet()) {
            $id = input('get.id', '0', 'trim');
            $detail = model('Banner')->where('banner_id',$id)->find();
            return $this->fetch("Banner/add", ['detail'=>$detail]);
        } elseif ($this->request->isPost()) {
        	$id = input('post.banner_id', '0', 'trim');
            $title = input('post.title', '', 'trim');
            $pic = input('post.pic', '', 'trim');
            $is_url= input('post.is_url', '', 'trim');
            $url = input('post.url', '', 'trim');

            if ($is_url == 1 && !$url) {
            	return $this->ajaxError("url地址必填");
            }
            
            $row = model("Banner")->where(['banner_id'=>$id])->find();
            if ($row['title'] != $title) {
                $count = model('Banner')->where(['title'=>$title])->count();
	            if ($count) {
	            	return $this->ajaxError("标题已存在,请重新填写");
	            }            	
            }
            
            $data = [];
            $data['banner_id'] = $id;
            $data['title'] = $title;
            $data['pic'] = $pic;
            $data['is_url'] = $is_url;
            $data['url'] = $url;

            $res = model('Banner')->save($data,['banner_id'=>$data['banner_id']]); 
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
     * 删除
     * 
     */
    public function del() {
    	if ($this->request->isPost()) {
    	    $id = input('post.id', '0', 'trim'); 
			$result = model('Banner')->get(['banner_id'=>$id]);
	        if (!$result) {
	        	return $this->ajaxError('参数非法');
	        }
	        
	        $res = model('Banner')->where(array('banner_id' => $id))->delete();
	        if ($res === false) {
	            return $this->ajaxError('操作失败');
	        } else {
	            return $this->ajaxSuccess('操作成功');
	        }   		
    	}
    }
}