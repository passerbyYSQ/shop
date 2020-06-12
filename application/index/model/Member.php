<?php
namespace app\index\model;
use think\Model;
/**
 * @author passerbyYSQ
 * @create 2020年6月12日 下午8:32:27
 */
class Member extends Model {
    
    
    public function findByCond($field, $value) {
        return db('member')
        ->where($field, $value)
        ->field('id, phone, memberName, portrait, defaultAddress')
        ->find();
    }
    
}
?>
