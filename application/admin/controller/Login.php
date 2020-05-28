<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Login as LoginModel;

/**
 * @author passerbyYSQ
 * @create 2020年5月25日 下午8:02:57
 */
class Login extends Controller {
    
    // 显示登录界面
    public function index() {
        return view();
    }
    
    // 登录逻辑
    public function doLogin() {
       
        //var_dump(input('post.'));
        
        $res = $this->validate(input('post.'), [
            'phone' => ['require'/*, 'regex' => '/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/'*/],
            'password' => ['require', 'between:3,32']
        ], [
            'phone.require' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确',
            'password.require' => '密码不能为空',
            'password.between' => '密码长度必须在[3, 32]之间'
        ]);
        
        if (true !== $res) {
            return $this->fetch('index', [
                'failMsg' => $res
            ]);
        }
        
        // 验证通过
        $phone = input('post.phone');
        $password = input('post.password');
        $model = new LoginModel();
        $admin = $model->login($phone, $password);
        if (!empty($admin)) {
            // 成功
            return $this->fetch('index');
        } else {
            return $this->fetch('index', [
                'failMsg' => '账号或密码错误'
            ]);
        }
        
    }
}
?>
