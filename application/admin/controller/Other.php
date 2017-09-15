<?php
/**
 * ============================================================================
 * Created by PhpStorm.
 * User: dd
 * Date: 2017/9/14
 * Time: 10:47
 * ============================================================================
 * $Author:lieyan123091
 */


namespace app\admin\controller;


use think\Db;

class Other extends Base
{
    public function aboutUpdate()
    {
        if(request()->isPost()){
            $result = Db::name('web_static')->where('id',1)->whereOr('pid',1)->select();
            foreach($result as $val){
                $temp[$val['name']] = $val['content'];
            }
            $data = input('post.');
            //看内容是否有变化:
            foreach($data as $k=>$v){
                if(strpos($k,'_')!==false)
                    continue;
                $newArr = ['content'=>$v];
                if($v!=$temp[$k])
                    Db::name('web_static')->where("name", $k)->save($newArr);//变更新此项
            }
            //看图片是否有变化：
            foreach($result as $val){
                if(empty($val['img']))
                    continue;
                $tmp[$val['name']] = $val['img'];
            }
            foreach($data as $k=>$v){
                if(strpos($k,'_')===false)
                    continue;
                $keys = explode('_',$k);
                $newTmpArr = ['img'=>$v];
                if($v!=$tmp[$keys[0]])
                    Db::name('web_static')->where("name", $keys[0])->save($newTmpArr);//变更新此项
            }
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','result'=>'']);
        }else{
            $info = Db::name('web_static')->where('id',1)->whereOr('pid',1)->select();
            $title = $content = $img = [];
            foreach($info as $v){
                $title[$v['name']] = $v['title'];
                $content[$v['name']] = $v['content'];
                $img[$v['name']] = $v['img'];
            }
            $this->assign('img',$img);
            $this->assign('content',$content);
            $this->assign('title',$title);
            $this->initEditor(); // 编辑器
            return $this->fetch('_about');
        }
     }

    public function vipUpdate()
    {
        if(request()->isPost()){
            $result = Db::name('web_static')->where('id',2)->whereOr('pid',2)->select();
            foreach($result as $val){
                $temp[$val['name']] = $val['content'];
            }
            $data = input('post.');
            //看名称是否有变化
            foreach($result as $val){
                if(empty($val['title']))
                    continue;
                $tmp_name[$val['name']] = $val['title'];
            }
            foreach($data as $k=>$v){
                if(strpos($k,'-')===false)
                    continue;
                $keys = explode('-',$k);
                $newNameTmpArr = ['title'=>$v];
                if($v!=$tmp_name[$keys[0]])
                    Db::name('web_static')->where("name", $keys[0])->save($newNameTmpArr);//变更新此项
            }
            //看内容是否有变化:
            foreach($data as $k=>$v){
                if(strpos($k,'_')!==false)
                    continue;
                $newArr = ['content'=>$v];
                if($v!=$temp[$k])
                    Db::name('web_static')->where("name", $k)->save($newArr);//变更新此项
            }
            //看图片是否有变化：
            foreach($result as $val){
                if(empty($val['img']))
                    continue;
                $tmp[$val['name']] = $val['img'];
            }
            foreach($data as $k=>$v){
                if(strpos($k,'_')===false)
                    continue;
                $keys = explode('_',$k);
                $newTmpArr = ['img'=>$v];
                if($v!=$tmp[$keys[0]])
                    Db::name('web_static')->where("name", $keys[0])->save($newTmpArr);//变更新此项
            }
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','result'=>'']);
        }else{
            $info = Db::name('web_static')->where('id',2)->whereOr('pid',2)->select();
            $title = $content = $img = [];
            foreach($info as $v){
                $title[$v['name']] = $v['title'];
                $content[$v['name']] = $v['content'];
                $img[$v['name']] = $v['img'];
            }
            $this->assign('img',$img);
            $this->assign('content',$content);
            $this->assign('title',$title);
            $this->initEditor(); // 编辑器
            return $this->fetch('_vip');
        }
     }

    public function copyrightUpdate()
    {
        if(request()->isPost()){
            $data = input('post.');
            Db::name('web_static')->where('id',3)->save($data);
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','result'=>'']);
        }else{
            $info = Db::name('web_static')->where('id',3)->find();
            $this->assign('info',$info);
            $this->initEditor(); // 编辑器
            return $this->fetch('_copyright');
        }
     }

    /**
     * 初始化编辑器链接
     * 本编辑器参考 地址 http://fex.baidu.com/ueditor/
     */
    private function initEditor()
    {
        $this->assign("URL_upload", U('admin/Ueditor/imageUp',array('savepath'=>'webstatic'))); // 图片上传目录
        $this->assign("URL_imageUp", U('admin/Ueditor/imageUp',array('savepath'=>'webstatic'))); //  不知道啥图片
        $this->assign("URL_fileUp", U('admin/Ueditor/fileUp',array('savepath'=>'webstatic'))); // 文件上传s
        $this->assign("URL_scrawlUp", U('admin/Ueditor/scrawlUp',array('savepath'=>'webstatic')));  //  图片流
        $this->assign("URL_getRemoteImage", U('admin/Ueditor/getRemoteImage',array('savepath'=>'webstatic'))); // 远程图片管理
        $this->assign("URL_imageManager", U('admin/Ueditor/imageManager',array('savepath'=>'webstatic'))); // 图片管理
        $this->assign("URL_getMovie", U('admin/Ueditor/getMovie',array('savepath'=>'webstatic'))); // 视频上传
        $this->assign("URL_Home", "");
    }

}