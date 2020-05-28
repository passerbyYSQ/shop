<?php
namespace app\admin\model;
use think\Model;
use think\Db;

/**
 * @author passerbyYSQ
 * @create 2020年5月25日 下午9:45:00
 */
class Login extends Model {
    
    public function login($phone, $password) {
        $admin = Db::table('admin')
            ->where([
                'phone' => $phone,
                'password' => $password
            ])->find();
        return $admin;
    }
}

?>
