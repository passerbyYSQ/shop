<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Member as MemberModel;

/**
 * @author passerbyYSQ
 * @create 2020年6月12日 下午4:14:31
 */
class Member extends Controller {
    
    public function checkCaptcha() {
        return captcha_check(input('post.captcha'));
    }
    
    public function isPhoneExist() {
        $model = new MemberModel();
        return empty($model->findByCond('phone', input('post.mobile_phone')));
    }
    
    public function isMemberNameExist() {
        $model = new MemberModel();
        return empty($model->findByCond('memberName', input('post.username')));
    }
    
    public function register() {
        if (request()->isPost()) {
            //var_dump(input('post.'));            
            $memberName = input('post.username');
            $phone = input('post.mobile_phone');
            $password = input('post.password');
            
            $model = new MemberModel();
            $model->phone = $phone;
            $model->password = md5($password);
            $model->memberName = $memberName;
            if ($model->save()) {
                //session('member', $model->findById($model->id));
                //var_dump(session('member'));
                $this->success('注册成功，请手动登录', 'login');
            } else {
                $this->error('注册失败' . $model->getError());
            }
        } else {
            return view();
        }
    }
    
    public function login() {
        if (request()->isPost()) {
            $res['status'] = 1;
            
            // ajax 
            $captcha = input('post.captcha');
            $phone = input('post.phone');
            $password = input('post.passowrd');
            
            if (!captcha_check($captcha)) {
                $res['status'] = 0;
                $res['msg'] = '验证码错误'; 
            }
            
            $model = new MemberModel();
            $member = $model->findByCond('phone', $phone);
            if (empty($member)) {
                $res['status'] = -1;
                $res['msg'] = '手机号尚未注册';
            }
            
            if (md5($password) != $member['password']) {
                $res['status'] = -2;
                $res['msg'] = '密码错误';
            }
            
            return json($res);
        } else {
            return view();
        }
        
    }
    
}

?>
