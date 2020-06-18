<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Cart as CartModel;

/**
 * @author passerbyYSQ
 * @create 2020年6月18日 下午4:21:56
 */
class Cart extends Controller {
    
    // 异步ajax往购物车里面添加商品
    public function add() { 
        //return json(input('post.'));
        
        // 未登录不允许操作
        if (empty(session('member'))) {
            return;
        }
        
        $memberId= session('member.id');
        $goodsId = input('post.goodsId');
        $count = input('post.count');
        
        $model = new CartModel();
        $res['status'] = true;

        $item = $model->findOne($memberId, $goodsId);
        
        // 购物车中没有
        if (empty($item)) {
            $item = $model->add($memberId, $goodsId, $count);
            if (empty($item)) { // 添加失败
                $res['status'] = false;
                $res['msg'] = $item->getError();
            } 
        } else {
            // 该记录的'count'字段+1
            if (!$item->setInc('count', $count)) {
                $res['status'] = false;
                $res['msg'] = $item->getError();
            }
        }
        return json($res);
    }

}
?>
