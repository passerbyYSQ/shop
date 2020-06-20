<?php
namespace app\index\model;
use think\Model;
/**
 * @author passerbyYSQ
 * @create 2020年6月12日 下午8:32:27
 */
class Cart extends Model {
    
    // 获取某个用户的购物车内容
    public function list($memberId) {
        return db('cart')
        ->alias('c')
        ->join('goods g', 'c.goodsId=g.id')
        ->where('memberId', $memberId)
        // last：商品库存
        ->field('c.id as cartId, c.count, g.id as goodsId, goodsName, mainPic, salePrice, g.count as last')
        ->select();
    }
    
    // 返回一个Model对象
    public function findOne($memberId, $goodsId) {
        return Cart::where([
            'memberId' => $memberId,
            'goodsId' => $goodsId
        ])->find();
    }
    
    // 插入一条数据，返回Model对象
    public function add($memberId, $goodsId, $count) {
        $item = new Cart();
        $item->memberId = $memberId;
        $item->goodsId = $goodsId;
        $item->count = $count;
        //return $item;
        if ($item->save()) { 
            return $item; // 添加成功，返回一个Model对象
        } else {
            return null;
        }
    }
    
    
    
}
?>
