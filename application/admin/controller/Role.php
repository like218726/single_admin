<?php

namespace app\admin\controller;

class Role extends Base {

	/**
	 * 
	 * 列表
	 * 
	 */
    public function index() {
    	if ($this->request->isGet()) {
	        $listInfo = model('AuthGroup')->select();
	        return $this->fetch("Role/index", ['list'=>$listInfo]);
    	}
    }

    /**
     * 
     * 添加
     * 
     */
    public function add() {
        if ($this->request->isPost()) {
            $postData = input('post.');
            
            $row = model('AuthGroup')->get(['name'=>trim($postData['name'])]);
            if ($row) {
            	return $this->ajaxError(trim($postData['name']).'已存在');
            }
                        
            model('AuthGroup')->save(['name'=>trim($postData['name']), 'description'=>trim($postData['description'])]);
            $groupId = model('AuthGroup')->getLastInsID();

            $needAdd = [];
            if(!empty($postData['rule']))
            {
                foreach ($postData['rule'] as $key => $value) {
                    if (!empty($value)) {
                        $data['url'] = $value;
                        $data['groupId'] = $groupId;
                        $needAdd[] = $data;
                    }
                }
            }
            if (count($needAdd)) {
                model('AuthRule')->saveAll($needAdd);
            }

            if ($groupId > 0) {
                return $this->ajaxSuccess('添加成功');

            } else {
                return $this->ajaxError('添加失败');
            }
        } else {
            $originList = model('Menu')->order('sort asc')->select()->toArray();
            $list = listToTree($originList);

            return $this->fetch("Role/add", ['list'=>$list]);
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
            $detail = model('AuthGroup')->where('id', $id)->find();

            $has =  collection(model('AuthRule')->where(array('groupId' => $id))->select())->toArray();
            $hasRule = array_column($has, 'url');
            $originList = model('Menu')->order('sort asc')->select()->toArray();
            $list = listToTree($originList);

            return $this->fetch("Role/add", ['detail'=>$detail, 'hasRule'=>$hasRule, 'list'=>$list]);
        } elseif ($this->request->isPost()) {

            $postData = input('post.');
            $groupId = $postData['id'];
            
            $row = model('AuthGroup')->get(['id'=>$groupId]);
            if ($row['name'] != trim($postData['name'])) {
            	$count = model('AuthGroup')->where(['name'=>trim($postData['name'])])->count();
            	if ($count) {
            		return $this->ajaxError(trim($postData['name']).'已存在');
            	}
            }
            
            $res = model('AuthGroup')->save(['name'=>trim($postData['name']), 'description'=>trim($postData['description'])], ['id'=>$groupId]);

            $needAdd = [];
            $has = collection(model('AuthRule')->where('groupId', $groupId)->select())->toArray();
            $hasRule = array_column($has, 'url');
            $needDel = array_flip($hasRule);
            if(!empty($postData['rule']))
            {
                foreach ($postData['rule'] as $key => $value) {
                    if (!empty($value)) {
                        if (!in_array($value, $hasRule)) {
                            $data['url'] = $value;
                            $data['groupId'] = $groupId;
                            $needAdd[] = $data;
                        } else {
                            unset($needDel[$value]);
                        }
                    }
                }
            }

            if (count($needAdd)) {
                model('AuthRule')->saveAll($needAdd);
            }
            if (count($needDel)) {
                $urlArr = array_keys($needDel);
                model('AuthRule')->where(array('groupId' => $groupId, 'url' => array('in', $urlArr)))->delete();
            }

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
	        if ($id == cache('ADMIN_GROUP')) {
	            $this->error('没有权限');
	        }
	
	        $user = model('AdminUser')->get(['groupId'=>$id]);
	        if(!empty($user))
	        {
	            return $this->ajaxError('该角色下有归属人员，无法删除');
	        }
	
	        $res = model('AuthGroup')->where('id', $id)->delete();
	        $res = model('AuthRule')->where('groupId', $id)->delete();
	        if ($res === false) {
	            return $this->ajaxError('删除失败');
	        } else {
	            return $this->ajaxSuccess('删除成功');
	        }
		}
    }

}