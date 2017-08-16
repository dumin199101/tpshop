<?php
namespace app\admin\validate;
//品牌验证器
use think\Validate;

class Brand extends Validate
{

    protected $rule=[
        ['name' ,'require'],
        ['url' ,'url'],
        ['sort','number'],
        ['desc' ,'max:200']
    ];
    protected $message = [
        'name.require'      => '品牌名称必须',
        'url.url'           => '品牌地址不是有效的URL地址',
        'sort.number'       => '排序必须是数字',
        'desc.max'          => '品牌描述不得大于200个字节'
    ];


}