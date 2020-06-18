<?php
namespace app\index\model;
use think\Model;
/**
 * @author passerbyYSQ
 * @create 2020年6月12日 下午8:32:27
 */
class Cart extends Model {
    
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
