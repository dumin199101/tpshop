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


class Other extends Base
{
    public function aboutUpdate()
    {
        return $this->fetch();
     }

    public function vipUpdate()
    {
        return $this->fetch();
     }

    public function copyrightUpdate()
    {
        return $this->fetch();
     }
}