<?php
namespace app\admin\validate;
//品牌验证器
use think\Validate;

class Brand extends Validate
{

    protected $rule=[
        ['name' ,'require'],
        ['title','require'],
        ['attach','require'],
        ['url' ,'url'],
        ['sort','number'],
        ['desc' ,'max:500']
    ];
    protected $message = [
        'title.require'    => '品牌英文名称必须',
        'attach.require'   => '品牌附加信息必须',
        'name.require'      => '品牌中文名称必须',
        'url.url'           => '品牌地址不是有效的URL地址',
        'sort.number'       => '排序必须是数字',
        'desc.max'          => '品牌描述不得大于500个字节'
    ];


}