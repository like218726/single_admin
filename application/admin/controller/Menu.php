<?php

namespace app\admin\controller;

class Menu extends Base {

    /**
     * 
     * 获取菜单列表
     * 
     */
    public function index() {
    	if ($this->request->isGet()) {
	        $list = model('Menu')->order('sort asc')->select()->toArray();
	        $list = formatTree(listToTree($list));	
	        return $this->fetch("Menu/index",["list"=>$list]);    		
    	}
    }

    /**
     * 
     * 新增菜单
     * 
     */
    public function add() {
        if ($this->request->isPost()) {
            $data = input('post.');
            $data['hide'] = isset($data['hide']) ? 1 : 0;
            $res = model('Menu')->save($data);
            $id = model('Menu')->getLastInsID();
            $menu_info = model('Menu')->get(['fid'=>$data['fid']]); 
            $level = 0;
            if($data['fid'] > 0)
            {
                $parent = model('Menu')->where('id', $data['fid'])->find();
                $idpath = $parent['idpath'].$id.'||';
                $level = $menu_info['level']==0 ? 1 : ($menu_info['level']==1 ? 2 : 0);
            }else{
                $idpath = '||'.$id.'||';
            }                       
            model('Menu')->save(['idpath'=>$idpath, 'level'=>$level], ['id'=>$id]);
            if ($res === false) {
                return $this->ajaxError('操作失败');
            } else {
                return $this->ajaxSuccess('操作成功');
            }
        } else {
            $originList = model('Menu')->order('sort asc')->select()->toArray();
            $fid = '';
            $id = input('get.id', '0', 'trim');
            if (!empty($id)) {
                $fid = $id;
            }
            $options = array_column(formatTree(listToTree($originList)), 'showName', 'id');

            return $this->fetch("Menu/add",["options"=>$options, 'fid'=>$fid]);
        }
    }

    /**
     * 
     * 显示菜单
     * 
     */
    public function open() {
    	if ($this->request->isPost()) {
    	    $id = input('post.id', '0', 'trim');
	        $res = model('Menu')->save(['hide' => 0], ['id'=>$id]);
	        if ($res === false) {
	            return $this->ajaxError('操作失败');
	        } else {
	            return $this->ajaxSuccess('操作成功');
	        }   		
    	}
    }

    /**
     * 
     * 隐藏菜单
     * 
     */
    public function close() {
    	if ($this->request->isPost()) {
	        $id = input('post.id', '0', 'trim');
	        $res = model('Menu')->save(['hide' => 1], ['id'=>$id]);
	        if ($res === false) {
	            return $this->ajaxError('操作失败');
	        } else {
	            return $this->ajaxSuccess('操作成功');
	        }
    	}
    }

    /**
     * 
     * 编辑菜单
     * 
     */
    public function edit() {
        if ($this->request->isGet()) {
        	$id = input('get.id' , '0', 'trim');
            $originList = model('Menu')->order('sort asc')->select()->toArray();
            $list = $this->buildArrByNewKey($originList);
            $listInfo = $list[$id];
            $options = array_column(formatTree(listToTree($originList)), 'showName', 'id');

            return $this->fetch("Menu/add",["detail"=>$listInfo, 'options'=>$options]);
        } elseif ($this->request->isPost()) {
            $postData = input('post.');
            $postData['hide'] = isset($postData['hide']) ? 1 : 0;
            $menu_info = model('Menu')->get(['fid'=>$postData['fid']]);  
            if($postData['fid'] > 0)
            {
                $parent = model('Menu')->where('id', $postData['fid'])->find();
                $idpath = $parent['idpath'].$postData['id'].'||';
                $postData['level'] = $menu_info['level']==0 ? 1 : ($menu_info['level']==1 ? 2 : 0);     
            }else{
            	$postData['level'] = 0;
                $idpath = '||'.$postData['id'].'||';
            }                
            $postData['idpath'] = $idpath;           
            $res = model('Menu')->save($postData, ['id'=>$postData['id']]);
            if ($res === false) {
                return $this->ajaxError('操作失败');
            } else {
                return $this->ajaxSuccess('操作成功');
            }
        }
    }

    /**
     * 
     * 删除菜单
     * 
     */
    public function del() {
    	if ($this->request->isPost()) {
	        $id = input('post.id', '0', 'trim');
	        $childNum = model('Menu')->where('fid', $id)->count();
	        if ($childNum) {
	            return $this->ajaxError("当前菜单存在子菜单,不可以被删除!");
	        } else {
	            model('Menu')->where('id', $id)->delete();
	            return $this->ajaxSuccess('操作成功');
	        }
    	}
    }
}