<?php
/**
 * ============================================================================
 *  太潮人管理模块：完成后台系统的太潮人管理
 * ============================================================================
 * Author: lieyan123091
 * Date: 2017-07-20
 */
namespace app\admin\controller;

use think\Page;
use app\admin\logic\ArticleCatLogic;
use think\Db;

class Person extends Base {

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 太潮人列表
     * @return mixed
     */
    public function personList(){
        $Person =  M('Person');
        $list = array();
        $p = input('p/d', 1);
        $size = input('size/d', 20);
        $where = array();
        $keywords = trim(I('keywords'));
        $keywords && $where['name'] = array('like', '%' . $keywords . '%');
        (I('is_recommend') !== '') && $where['is_recommend'] = I('is_recommend') ;
        $res = $Person->where($where)->order('id desc')->page("$p,$size")->select();
        $count = $Person->where($where)->count();// 查询满足要求的总记录数
        $pager = new Page($count,$size);// 实例化分页类 传入总记录数和每页显示的记录数
        $page = $pager->show();//分页显示输出
        if($res){
        	foreach ($res as $val){
        		$val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);        		
        		$list[] = $val;
        	}
        }
        $this->assign('is_recommend',I('is_recommend'));
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$page);// 赋值分页输出
        $this->assign('pager',$pager);
        return $this->fetch('personList');
    }

    /**
     * 添加太潮人表单
     * @return mixed
     */
    public function person(){
        $act = I('get.act','add');
        $info = array();
        $info['publish_time'] = time()+3600*24;
        $id = I('get.id/d');
        if($id){
            $info = D('Person')->where('id', $id)->find();
            $activity_list =  D('Activity')->field('a.act_id,a.act_name,a.start_time,a.end_time')
                ->alias('a')
                ->join('__PERSON_ACTIVITY__ b','a.act_id=b.act_id')
                ->where('b.person_id',$id)
                ->select();
            $this->assign('activity_list',$activity_list);
        }
        $this->assign('act',$act);
        $this->assign('info',$info);
        $this->initEditor();
        return $this->fetch();
    }

    /**
     * 增删改太潮人
     */
    public function personHandle(){
        $data = I('post.');
        $data['publish_time'] = strtotime($data['publish_time']);
        $url = $this->request->server('HTTP_REFERER');
        $referurl = !empty($url) ? $url : U('Admin/Person/personList');
        if($data['act'] == 'add'){
            $data['click'] = mt_rand(1000,1300);
            $data['add_time'] = time();
            $r = D('Person')->add($data);
            if(is_array($data['act_id']) && $r){
                foreach($data['act_id'] as $v){
                    D('personActivity')->add(
                        [
                            'person_id'=>$r,
                            'act_id'=>$v
                        ]
                    );
                }
            }
        }

        if($data['act'] == 'edit'){
            $r = M('Person')->where('id', $data['id'])->save($data);
            //先删除原来的在添加：
            D('personActivity')->where('person_id',$data['id'])->delete();
            if(is_array($data['act_id']) && $data['id']){
                foreach($data['act_id'] as $v){
                    D('personActivity')->add(
                        [
                            'person_id'=>$data['id'],
                            'act_id'=>$v
                        ]
                    );
                }
            }
        }

        if($data['act'] == 'del'){
            $r = D('Person')->where('id', $data['del_id'])->delete();
            D('personActivity')->where('person_id',$data['del_id'])->delete();
            if($r) exit(json_encode(1));
        }
        if($r!==FALSE){
            $this->success("操作成功",$referurl);
        }else{
            $this->error("操作失败",$referurl);
        }
    }

    /**
     * 初始化编辑器链接
     * @param $post_id post_id
     */
    private function initEditor()
    {
        $this->assign("URL_upload", U('Admin/Ueditor/imageUp',array('savepath'=>'person')));
        $this->assign("URL_fileUp", U('Admin/Ueditor/fileUp',array('savepath'=>'person')));
        $this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp',array('savepath'=>'person')));
        $this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage',array('savepath'=>'person')));
        $this->assign("URL_imageManager", U('Admin/Ueditor/imageManager',array('savepath'=>'person')));
        $this->assign("URL_imageUp", U('Admin/Ueditor/imageUp',array('savepath'=>'person')));
        $this->assign("URL_getMovie", U('Admin/Ueditor/getMovie',array('savepath'=>'person')));
        $this->assign("URL_Home", "");
    }

    /**
     * 太潮人选择活动
     * @return mixed
     */
    public function search_activity()
    {
        $acts_id = I('acts_id');
        $where = ' enable = 1';//搜索条件
        if (!empty($acts_id)) {
            $where .= " and act_id not in ($acts_id) ";
        }

        if (!empty($_REQUEST['keywords'])) {
            $this->assign('keywords', I('keywords'));
            $where = "$where and (act_name like '%" . I('keywords')  . "%')";
        }
        $count = M('activity')->where($where)->count();
        $Page = new Page($count, 10);
        $activityList = M('activity')->where($where)->order('act_id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $show = $Page->show();//分页显示输出
        $this->assign('page', $show);//赋值分页输出
        $this->assign('activityList', $activityList);
        $this->assign('pager', $Page);//赋值分页输出
        $tpl = I('get.tpl', 'search_activity');
        return $this->fetch($tpl);
    }

    

    
    


}