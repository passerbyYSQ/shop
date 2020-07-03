<?php
namespace app\admin\model;
use think\Model;
use think\Db;

/**
 * @author passerbyYSQ
 * @create 2020年5月27日 下午8:51:08
 */
class OrderItem extends Model {
    protected $table = 'orderitem';
    
    // 某个订单的item列表
    public function orderItemList($orderId) {
        return db('orderitem')->alias('o')
        ->where('orderId', $orderId)
        ->join('goods g', 'o.goodsId=g.id')
        ->field('o.count, o.goodsId, g.goodsName, g.mainPic, g.salePrice')
        ->select();
    }
    
}

?>
