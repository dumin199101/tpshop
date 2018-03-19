<?php


namespace app\mobile\controller;
use think\Controller;


class Web extends Controller {
    public function stop(){
        return $this->fetch();
    }
}