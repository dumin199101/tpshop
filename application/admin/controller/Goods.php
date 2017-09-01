<?php
/**
 * ============================================================================
 *  太潮志管理模块：完成后台系统的太潮人管理
 * ============================================================================
 * Author: lieyan123091
 * Date: 2017-07-20
 */
namespace app\admin\controller;
use think\AjaxPage;
use think\Loader;
use think\Page;
use think\Db;

class Goods extends Base {
    /**
     * 品牌列表
     */
    public function brandList(){
        $model = M("Brand");
        $where = "";
        $keyword = I('keyword');
        $where = $keyword ? " name like '%$keyword%' " : "";
        $count = $model->where($where)->count();
        $Page = $pager = new Page($count,10);
        $brandList = $model->where($where)->order("`sort` asc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $show  = $Page->show();
        $this->assign('pager',$pager);
        $this->assign('show',$show);
        $this->assign('brandList',$brandList);
        return $this->fetch('brandList');
    }

    /**
     * 添加修改编辑  商品品牌
     */
    public  function addEditBrand(){
        $id = I('id');
        if(IS_POST)
        {
            $data = I('post.');
            $brandVilidate = Loader::validate('Brand');
            $data['letter'] = getFirstCharter($data['title']);
            if(!$brandVilidate->batch()->check($data)){
                $return = ['status'=>0,'msg'=>'操作失败','result'=>$brandVilidate->getError()];
                $this->ajaxReturn($return);
            }
            if($id){
                M("Brand")->update($data);
            }else{
                M("Brand")->insert($data);
            }
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','result'=>'']);
        }
        $brand = M("Brand")->find($id);
        $this->assign('brand',$brand);
        return $this->fetch('_brand');
    }

    /**
     * 删除品牌
     */
    public function delBrand()
    {
        $model = M("Brand");
        $model->where('id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);
        $this->ajaxReturn($return_arr);
    }

    /**
     *  商品列表
     */
    public function goodsList(){
        return $this->fetch();
    }
    
    /**
     *  ajax获取商品列表
     */
    public function ajaxGoodsList(){
        $where = ' 1 = 1 '; // 搜索条件
        (I('is_recommend') !== '') && $where = "$where and is_recommend = ".I('is_recommend') ;
        // 关键词搜索               
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (name like '%$key_word%')" ;
        }
        $model = M('Goods');
        $count = $model->where($where)->count();
        $Page  = new AjaxPage($count,10);
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $goodsList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($goodsList as &$val){
            $val['add_time'] = date('Y-m-d',$val['add_time']);
        }
        $this->assign('goodsList',$goodsList);
        $this->assign('page',$show);// 赋值分页输出
        return $this->fetch();
    }


    /**
     * 添加修改商品
     */
    public function addEditGoods()
    {
        $Goods = new \app\admin\model\Goods();
        $goods_id = I('id');
        $type = $goods_id > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
        //ajax提交验证
        if (request()->isAjax()) {
            // 数据验证
            $return_url = U('admin/Goods/goodsList');
            $data = input('post.');
            $validate = Loader::validate('Goods');
            if (!$validate->batch()->check($data)) {
                $return_arr = ['status'=>0,'msg'=>'操作失败','result'=>$validate->getError()];
                $this->ajaxReturn($return_arr);
            }
            $data['add_time'] = strtotime($data['add_time']);
            if ($type == 2) {
                $Goods->isUpdate(true)->save($data); // 写入数据到数据库
            } else {
                $data['click'] = mt_rand(1000,1300);
                $Goods->save($data); // 写入数据到数据库
            }
            $return_arr = array(
                'status' => 1,
                'msg' => '操作成功',
                'data' => array('url' => $return_url),
            );
            $this->ajaxReturn($return_arr);
        }
        $goodsInfo = M('Goods')->where('id=' . I('GET.id', 0))->find();
        if($type==1)
            $goodsInfo['add_time'] = time();

        $this->assign('goodsInfo', $goodsInfo);  // 商品详情
        $this->initEditor(); // 编辑器
        return $this->fetch('_goods');
    } 

    /**
     * 删除商品
     */
    public function delGoods()
    {
        $goods_id = input('id');
        // 删除此商品        
        M("Goods")->where('id ='.$goods_id)->delete();  //商品表
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);
        $this->ajaxReturn($return_arr);
    }

    /**
     * 初始化编辑器链接     
     * 本编辑器参考 地址 http://fex.baidu.com/ueditor/
     */
    private function initEditor()
    {
        $this->assign("URL_upload", U('admin/Ueditor/imageUp',array('savepath'=>'goods'))); // 图片上传目录
        $this->assign("URL_imageUp", U('admin/Ueditor/imageUp',array('savepath'=>'goods'))); //  不知道啥图片
        $this->assign("URL_fileUp", U('admin/Ueditor/fileUp',array('savepath'=>'goods'))); // 文件上传s
        $this->assign("URL_scrawlUp", U('admin/Ueditor/scrawlUp',array('savepath'=>'goods')));  //  图片流
        $this->assign("URL_getRemoteImage", U('admin/Ueditor/getRemoteImage',array('savepath'=>'goods'))); // 远程图片管理
        $this->assign("URL_imageManager", U('admin/Ueditor/imageManager',array('savepath'=>'goods'))); // 图片管理
        $this->assign("URL_getMovie", U('admin/Ueditor/getMovie',array('savepath'=>'goods'))); // 视频上传
        $this->assign("URL_Home", "");
    }    

}