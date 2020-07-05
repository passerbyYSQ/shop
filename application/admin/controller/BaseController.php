<?php
namespace app\admin\controller;
use think\Controller;
/**
 * @author passerbyYSQ
 * @create 2020年5月25日 下午8:02:57
 */
class BaseController extends Controller {
    
    public function _initialize() {
        if (empty(session('admin'))) {
            $this->error('请先登录，再访问后台管理！', 'login/index');
        }
    }
}
?>
