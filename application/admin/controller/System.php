<?php

namespace app\admin\controller;
use think\db;
use think\Cache;
use think\Page;
class System extends Base{

    /**
     * 清空缩略图
     */
    public function cleanThumb()
    {
        delFile(THUMB_PATH,true);
        Cache::clear();
        adminLog("清理缩略图");
        $this->success("操作完成!!!",U('Admin/Admin/index'));
        exit();
    }

	/**
	 * 清空系统缓存
	 */
	public function cleanCache(){
		delFile(RUNTIME_PATH);
		Cache::clear();
		adminLog("清理缓存");
		$this->success("操作完成!!!",U('Admin/Admin/index'));
		exit();
	}

	/*
	 * 配置入口
	 */
	public function index()
	{          
		/*配置列表*/
		$group_list = array('shop_info'=>'网站信息');
		$this->assign('group_list',$group_list);
		$inc_type =  I('get.inc_type','shop_info');
		$this->assign('inc_type',$inc_type);
		$config = tpCache($inc_type);
		if($inc_type == 'shop_info'){
			$province = M('region')->where(array('parent_id'=>0))->select();
			$city =  M('region')->where(array('parent_id'=>$config['province']))->select();
			$area =  M('region')->where(array('parent_id'=>$config['city']))->select();
			$this->assign('province',$province);
			$this->assign('city',$city);
			$this->assign('area',$area);
		}
		$this->assign('config',$config);//当前配置项
		return $this->fetch($inc_type);
	}
	
	/*
	 * 新增修改配置
	 */
	public function handle()
	{
		$param = I('post.');
		$inc_type = $param['inc_type'];
		unset($param['inc_type']);
		tpCache($inc_type,$param);
		adminLog("修改系统配置信息");
		$this->success("操作成功",U('System/index',array('inc_type'=>$inc_type)));
	}

    /**
     * 自定义导航
     */
    public function navigationList(){
           $model = M("Navigation");
           $navigationList = $model->order("id desc")->select();            
           $this->assign('navigationList',$navigationList);
           return $this->fetch('navigationList');
     }

    /**
     * 添加修改编辑 前台导航
     */
    public function addEditNav()
    {
        $model = D("Navigation");
        if (IS_POST) {
            if (I('id')){
                adminLog("编辑导航");
                $model->update(I('post.'));
            }else{
                adminLog("添加导航");
                $model->add(I('post.'));
            }
            $this->success("操作成功!!!", U('Admin/System/navigationList'));
            exit;
        }
        // 点击过来编辑时
        $id = I('id',0);
        $navigation = DB::name('navigation')->where('id',$id)->find();
        $this->assign('navigation', $navigation);
        return $this->fetch('_navigation');
    }   
    
    /**
     * 删除前台 自定义 导航
     */
    public function delNav()
    {     
        // 删除导航
        adminLog("删除导航");
        M('Navigation')->where("id",I('id'))->delete();
        $this->success("操作成功!!!",U('Admin/System/navigationList'));
    }

    /**
     * 取得控制器下的方法列表
     */
    public function ajax_get_action()
    {
        $control = I('controller');
        $advContrl = get_class_methods("app\\admin\\controller\\".str_replace('.php','',$control));
        $baseContrl = get_class_methods('app\admin\controller\Base');
        $diffArray  = array_diff($advContrl,$baseContrl); //去除从父类中继承的方法
        $html = '';
        foreach ($diffArray as $val){
            $html .= "<option value='".$val."'>".$val."</option>";
        }
        exit($html);
    }

    //权限资源列表
    public function right_list(){
        //权限组
        $group = array('system'=>'系统设置','content'=>'内容管理','goods'=>'商品中心','marketing'=>'营销推广','tools'=>'插件工具');

        //搜索
        $name = I('name');
        if($name){
            $condition['name|right'] = array('like',"%$name%");
            $right_list = M('system_menu')->where($condition)->order('id desc')->select();
        }else{
            $right_list = M('system_menu')->order('id desc')->select();
        }

        $this->assign('right_list',$right_list);
        $this->assign('group',$group);
        return $this->fetch();
    }

    /**
     * 添加编辑权限
     * @return mixed
     */
    public function edit_right(){
        if(IS_POST){
            $data = I('post.');
            $data['right'] = implode(',',$data['right']);
            if(!empty($data['id'])){
                adminLog("修改权限");
                M('system_menu')->where(array('id'=>$data['id']))->save($data); //编辑权限
            }else{
                adminLog("添加权限");
                if(M('system_menu')->where(array('name'=>$data['name']))->count()>0){
                    $this->error('该权限名称已添加，请检查',U('System/right_list'));
                }
                unset($data['id']);
                M('system_menu')->add($data); //添加权限
            }
            $this->success('操作成功',U('System/right_list'));
            exit;
        }

        //权限信息
        $id = I('id');
        if($id){
            $info = M('system_menu')->where(array('id'=>$id))->find();
            $info['right'] = explode(',', $info['right']);
            $this->assign('info',$info);
        }
        //权限组
        $group = array('system'=>'系统设置','content'=>'内容管理','goods'=>'商品中心','marketing'=>'营销推广','tools'=>'插件工具');

        //获取控制器列表
        $planPath = APP_PATH.'admin/controller';
        $planList = array();
        $dirRes   = opendir($planPath);
        while($dir = readdir($dirRes))
        {
            if(!in_array($dir,array('.','..','.svn')))
            {
                $planList[] = basename($dir,'.php');
            }
        }
        $this->assign('planList',$planList);
        $this->assign('group',$group);
        return $this->fetch();
    }

    /**
     * 删除权限
     */
    public function right_del(){
        $id = I('del_id');
        if(is_array($id)){
            $id = implode(',', $id);
        }
        if(!empty($id)){
            adminLog("删除权限");
            $r = M('system_menu')->where("id in ($id)")->delete();
            if($r){
                respose(1);
            }else{
                respose('删除失败');
            }
        }else{
            respose('参数有误');
        }
    }

    /*添加友情链接模板*/
    public function link(){
        $act = I('get.act','add');
        $this->assign('act',$act);
        $link_id = I('get.link_id/d');
        $link_info = array();
        if($link_id){
            $link_info = D('friend_link')->where('link_id', $link_id)->find();
        }
        $this->assign('info',$link_info);
        return $this->fetch();
    }

    /**
     * 友情链接列表
     * @return mixed
     */
    public function linkList(){
        $Ad =  M('friend_link');
        $p = $this->request->param('p');
        $res = $Ad->order('orderby')->page($p.',10')->select();
        if($res){
            foreach ($res as $val){
                $val['target'] = $val['target']>0 ? '开启' : '关闭';
                $list[] = $val;
            }
        }
        $this->assign('list',$list);// 赋值数据集
        $count = $Ad->count();// 查询满足要求的总记录数
        $Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $this->assign('pager',$Page);// 赋值分页输出
        $this->assign('page',$show);// 赋值分页输出
        return $this->fetch();
    }

    /**
     * 增删改友情链接
     */
    public function linkHandle(){
        $data = I('post.');
        if($data['act'] == 'add'){
            stream_context_set_default(array('http'=>array('timeout' =>2)));
            $r = D('friend_link')->insert($data);
            adminLog("添加友链");
        }
        if($data['act'] == 'edit'){
            $r = D('friend_link')->where('link_id', $data['link_id'])->save($data);
            adminLog("编辑友链");
        }

        if($data['act'] == 'del'){
            $r = D('friend_link')->where('link_id', $data['link_id'])->delete();
            adminLog("删除友链");
            if($r) exit(json_encode(1));
        }

        if($r){
            $this->success("操作成功",U('Admin/System/linkList'));
        }else{
            $this->error("操作失败",U('Admin/System/linkList'));
        }
    }
}