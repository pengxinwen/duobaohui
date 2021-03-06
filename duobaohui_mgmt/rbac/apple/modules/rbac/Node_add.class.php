<?php
/*
 * 
 * @author
 */
namespace Gate\Modules\Rbac;
use Gate\Package\System\Rbac;

class Node_add extends \Gate\Libs\AdmController {
    // TRUE: 输出view 页面; FALSE: 输出json格式数据
    protected $view_switch = true;

	private $newData;
	private $roleIds;
	private $msg;

	public function run() {
		if (!$this->_init()) {return FALSE;}
		if(isset($this->request->POST['name'])){ // 提交表单 
			if( $this->addCheck() && $this->add() ){ //参数检查并保存
				$this->ajaxDialog('添加成功,请添加如下sql到上线代码中@_@:<br/>' . implode(';<br/>',$GLOBALS['rbac_sql']).';');
			}else{
				$this->ajaxDialog($this->msg, false);
			}
		}
		if(isset($this->request->GET['node_id'])){
			$this->view->parent = Rbac::getInstance()->getNodeRow($this->request->GET['node_id']);
		}

		$this->view->fromDomain = $this->fromDomain;
		$this->view->roleList = $this->getRoleList();
	}

	private function _init() {
		if(isset($this->request->POST['name'])){ // 提交表单 
			$this->newData['name']		= trim($this->request->POST['name']);
			$this->newData['action']	= trim($this->request->POST['action']);
			$this->newData['sort']		= trim($this->request->POST['sort']);
			$this->newData['status']	= trim($this->request->POST['status']);
			$this->newData['type']		= trim($this->request->POST['type']);
			$this->newData['pid']		= isset($this->request->POST['pid']) ? trim($this->request->POST['pid']) : 0;

			$this->roleIds				= isset($this->request->POST['roleIds']) ? $this->request->POST['roleIds'] : array() ;
		}
		$this->fromDomain = isset($this->request->GET['fromDomain']) ? $this->request->GET['fromDomain'] : '';
		return $this->_check();
	}


	private function _check(){
		return TRUE;
	}

	private function addCheck(){
		if($this->newData['name']==''){
			$this->msg = '名称不能为空';
			return false;
		}
		if($this->newData['action']==''){
			$this->msg = 'action不能为空';
			return false;
		}
		return TRUE;
	}

	private function add(){
		return Rbac::getInstance()->addNode($this->newData, $this->roleIds);
	}

	/*
	 * 角色列表
	 */
	private function getRoleList(){
		return Rbac::getInstance()->getRoleList();
	}
}
