<?php
/**
 * ============================================================================
    管理员登陆模块：完成管理员的登陆，退出系统，修改密码
 *  权限管理模块：完成后台系统的权限管理
 * ============================================================================
 * Author: lieyan123091
 * Date: 2017-07-20
 */

namespace app\admin\controller;

use think\Page;
use think\Verify;
use think\Db;
use think\Session;

class Admin extends Base {

	/*
     * 管理员登陆
     */
	public function login(){
		if(session('?admin_id') && session('admin_id')>0){
			$this->error("您已登录",U('Admin/Index/index'));
		}
		if(IS_POST){
			$verify = new Verify();
			if (!$verify->check(I('post.vertify'), "admin_login")) {
				exit(json_encode(array('status'=>0,'msg'=>'验证码错误')));
			}
			$condition['user_name'] = I('post.username/s');
			$condition['password'] = I('post.password/s');
			if(!empty($condition['user_name']) && !empty($condition['password'])){
				$condition['password'] = encrypt($condition['password']);
				$admin_info = M('admin')->join(PREFIX.'admin_role', PREFIX.'admin.role_id='.PREFIX.'admin_role.role_id','INNER')->where($condition)->find();
				if(is_array($admin_info)){
					session('admin_id',$admin_info['admin_id']);
					//缓存权限列表到session
					session('act_list',$admin_info['act_list']);
					M('admin')->where("admin_id = ".$admin_info['admin_id'])->save(array('last_login'=>time(),'last_ip'=>  getIP()));
					session('last_login_time',$admin_info['last_login']);
					session('last_login_ip',$admin_info['last_ip']);
					adminLog('后台登录');
					$url = session('from_url') ? session('from_url') : U('Admin/Index/index');
					exit(json_encode(array('status'=>1,'url'=>$url)));
				}else{
					exit(json_encode(array('status'=>0,'msg'=>'账号密码不正确')));
				}
			}else{
				exit(json_encode(array('status'=>0,'msg'=>'请填写账号密码')));
			}
		}
		return $this->fetch();
	}

    /**
     * 验证码获取
     */
    public function vertify()
    {
        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'useCurve' => true,
            'useNoise' => false,
            'reset' => false
        );
        $Verify = new Verify($config);
        $Verify->entry("admin_login");
        exit();
    }

	/**
	 * 退出登陆
	 */
	public function logout(){
	    adminLog("退出系统");
		session_unset();
		session_destroy();
		session::clear();
		$this->success("退出成功",U('Admin/Admin/login'));
	}

	/**
	 * 修改管理员密码
	 * @return \think\mixed
	 */
	public function modify_pwd(){
		$admin_id = I('admin_id/d',0);
		$oldPwd = I('old_pw/s');
		$newPwd = I('new_pw/s');
		$new2Pwd = I('new_pw2/s');

		if($admin_id){
			$info = D('admin')->where("admin_id", $admin_id)->find();
			$info['password'] =  "";
			$this->assign('info',$info);
		}

		if(IS_POST){
			//修改密码
			$enOldPwd = encrypt($oldPwd);
			$enNewPwd = encrypt($newPwd);
			$admin = M('admin')->where('admin_id' , $admin_id)->find();
			if(!$admin || $admin['password'] != $enOldPwd){
				exit(json_encode(array('status'=>-1,'msg'=>'旧密码不正确')));
			}else if($newPwd != $new2Pwd){
				exit(json_encode(array('status'=>-1,'msg'=>'两次密码不一致')));
			}else{
				$row = M('admin')->where('admin_id' , $admin_id)->save(array('password' => $enNewPwd));
				adminLog("修改密码");
				if($row){
					exit(json_encode(array('status'=>1,'msg'=>'修改成功')));
				}else{
					exit(json_encode(array('status'=>-1,'msg'=>'修改失败')));
				}
			}
		}
		return $this->fetch();
	}

    /**
     * 管理员列表
     * @return mixed
     */
    public function index(){
    	$list = array();
    	$keywords = I('keywords/s');
    	if(empty($keywords)){
    		$res = D('admin')->select();
    	}else{
			$res = DB::name('admin')->where('user_name','like','%'.$keywords.'%')->order('admin_id')->select();
    	}
    	$role = DB::name('admin_role')->column('role_name','role_id');
    	if($res && $role){
    		foreach ($res as $val){
    			$val['role'] =  $role[$val['role_id']];
    			$val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
    			$list[] = $val;
    		}
    	}
    	$this->assign('list',$list);
        return $this->fetch();
    }
    

    /**
     * 编辑|添加管理员信息表单
     * @return mixed
     */
    public function admin_info(){
    	$admin_id = I('get.admin_id/d',0);
    	if($admin_id){
    		$info = D('admin')->where("admin_id", $admin_id)->find();
			$info['password'] =  "";
    		$this->assign('info',$info);
    	}
    	$act = empty($admin_id) ? 'add' : 'edit';
    	$this->assign('act',$act);
    	$role = D('admin_role')->select();
    	$this->assign('role',$role);
    	return $this->fetch();
    }

    /**
     * 编辑添加删除管理员信息
     */
    public function adminHandle(){
    	$data = I('post.');
    	if(empty($data['password'])){
    		unset($data['password']);
    	}else{
    		$data['password'] = encrypt($data['password']);
    	}
    	if($data['act'] == 'add'){
    		unset($data['admin_id']);    		
    		$data['add_time'] = time();
    		if(D('admin')->where("user_name", $data['user_name'])->count()){
    			$this->error("此用户名已被注册，请更换",U('Admin/Admin/admin_info'));
    		}else{
    		    adminLog("添加管理员:" . $data['user_name']);
    			$r = D('admin')->add($data);
    		}
    	}
    	
    	if($data['act'] == 'edit'){
    	    adminLog("编辑管理员");
    		$r = D('admin')->where('admin_id', $data['admin_id'])->save($data);
    	}
    	
        if($data['act'] == 'del' && $data['admin_id']>1){
    	    adminLog("删除管理员");
    		$r = D('admin')->where('admin_id', $data['admin_id'])->delete();
    		exit(json_encode(1));
    	}
    	
    	if($r){
    		$this->success("操作成功",U('Admin/Admin/index'));
    	}else{
    		$this->error("操作失败",U('Admin/Admin/index'));
    	}
    }
    

    /**
     * 角色列表
     * @return mixed
     */
    public function role(){
    	$list = D('admin_role')->order('role_id desc')->select();
    	$this->assign('list',$list);
    	return $this->fetch();
    }

    /**
     * 角色详细信息(包括角色的权限信息)
     * @return mixed
     */
    public function role_info(){
    	$role_id = I('get.role_id/d');
    	$detail = array();
    	if($role_id){
    		$detail = M('admin_role')->where("role_id",$role_id)->find();
    		$detail['act_list'] = explode(',', $detail['act_list']);  //当前角色拥有的权限信息
    		$this->assign('detail',$detail);
    	}
        //获取权限节点列表
    	$right = M('system_menu')->order('id')->select();
		foreach ($right as $val){
			if(!empty($detail)){
				$val['enable'] = in_array($val['id'], $detail['act_list']);   //判断当前角色所拥有的权限,做好标识
			}
			$modules[$val['group']][] = $val; //按照group字段对权限进行分组
		}
		//权限组
        $group = array('system'=>'系统设置','content'=>'内容管理','goods'=>'商品中心','marketing'=>'营销推广','tools'=>'插件工具');

		$this->assign('group',$group);
		$this->assign('modules',$modules);
    	return $this->fetch();
    }

    /**
     * 编辑角色权限分配
     */
    public function roleSave(){
    	$data = I('post.');
    	$res = $data['data']; //角色名称+角色描述
    	$res['act_list'] = is_array($data['right']) ? implode(',', $data['right']) : ''; //分配权限
        if(empty($res['act_list']))
            $this->error("请选择权限!");        
    	if(empty($data['role_id'])){ //添加角色
			$admin_role = Db::name('admin_role')->where(['role_name'=>$res['role_name']])->find();
			if($admin_role){
				$this->error("已存在相同的角色名称!");
			}else{
                adminLog('添加角色');
				$r = D('admin_role')->add($res);
			}
    	}else{ //修改角色
			$admin_role = Db::name('admin_role')->where(['role_name'=>$res['role_name'],'role_id'=>['<>',$data['role_id']]])->find();
			if($admin_role){
				$this->error("已存在相同的角色名称!");
			}else{
			    adminLog("修改角色");
				$r = D('admin_role')->where('role_id', $data['role_id'])->save($res);
			}
    	}
		if($r){
			$this->success("操作成功!",U('Admin/Admin/role_info',array('role_id'=>$data['role_id'])));
		}else{
			$this->error("操作失败!",U('Admin/Admin/role'));
		}
    }

    /**
     * 角色删除
     */
    public function roleDel(){
    	$role_id = I('post.role_id/d');
    	$admin = D('admin')->where('role_id',$role_id)->find();
    	if($admin){
    	    return json(['status'=>0,'info'=>"请先清空所属该角色的管理员"]);
    	}else{
    		$d = M('admin_role')->where("role_id", $role_id)->delete();
    		adminLog("删除角色");
    		if($d){
                return json(['status'=>1,'info'=>"角色删除成功"]);
    		}else{
                return json(['status'=>0,'info'=>"角色删除失败"]);
    		}
    	}
    }

    /**
     * 管理员日志:对后台的一系列增删改
     * @return mixed
     */
    public function log(){
    	$p = I('p/d',1);
    	$logs = DB::name('admin_log')->alias('l')->join('__ADMIN__ a','a.admin_id =l.admin_id')->order('log_time DESC')->page($p.',20')->select();
    	$this->assign('list',$logs);
    	$count = DB::name('admin_log')->count();
    	$Page = new Page($count,20);
    	$show = $Page->show();
		$this->assign('pager',$Page);
		$this->assign('page',$show);
    	return $this->fetch();
    }

}