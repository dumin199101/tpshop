<?php
/**
 * ============================================================================
 * Created by PhpStorm.
 * User: dd
 * Date: 2017/8/31
 * Time: 18:13
 * ============================================================================
 * $Author:lieyan123091
 */


namespace app\home\controller;


class System extends Base
{
    /**
     * 关于君太
     * @return mixed
     */
    public function about()
    {
        return $this->fetch();
    }

    /**
     * 版权声明
     */
    public function copyright()
    {
        return $this->fetch();
    }

    /**
     * 君太VIP
     * @return mixed
     */
    public function vip()
    {
        return $this->fetch();
    }
}