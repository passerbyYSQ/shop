<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\db\Query;

/**
 * @author passerbyYSQ
 * @create 2020年5月27日 下午8:51:08
 */
class Order extends Model {
    
    public function orderIds($isSend, $orderId, $params, $count = 2) {
        $query = new Query();
        $query = $query->table('order');
        
        $query = $query->where('status', ($isSend == 0 ? 'eq' : 'neq') , 1);
        $query = $query->where('id', 'like', '%'.$orderId.'%');
        
        $paginator = $query->field('id')
            ->order('createTime desc')
            ->paginate($count, false,
                ['query'=>$params]);
        return $paginator;
    }
    
    public function orderDetail($orderId) {
        return db('order')->alias('o')
        ->where('o.id', $orderId)
        ->join('member m', 'o.memberId=m.id')
        ->join('address a', 'o.addressId=a.id')
        ->field('o.*, m.memberName, m.phone, a.consigneeName, a.mobilePhone consigneePhone,
            a.province, a.city, a.area, a.detail')
        ->find();
    }
    
}

?>
