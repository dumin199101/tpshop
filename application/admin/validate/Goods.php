<?php
namespace app\admin\validate;
use think\Validate;
class Goods extends Validate
{
    
    // 验证规则
    protected $rule=[
        ['name' ,'require'],
        ['author' ,'require'],
        ['thumb','require'],
        ['add_time','require'],
        ['sort','require|number'],
        ['content' ,'require']
    ];
    protected $message = [
        'name.require'      => '名称必填',
        'thumb.require'=>'请上传图片',
        'author.require'=> '作者名称必填',
        'add_time.require'=>'发表日期必填',
        'sort.require'=>'排序必填',
        'sort.number'       => '排序必须是数字',
        'content.require'          => '商品详情描述必填'
    ];
}