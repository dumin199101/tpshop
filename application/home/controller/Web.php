<?php


namespace app\home\controller;
use think\Controller;


class Web extends Controller {
    public function stop(){
        return $this->fetch();
    }
}