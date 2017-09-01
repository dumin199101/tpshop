<?php
/**
 * ============================================================================
 * Created by PhpStorm.
 * User: dd
 * Date: 2017/9/1
 * Time: 14:55
 * ============================================================================
 * $Author:lieyan123091
 */


namespace app\home\controller;


class Vip extends Base
{
    /**
     * 君太VIP
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }
}