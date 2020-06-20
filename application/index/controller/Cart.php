<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Cart as CartModel;
use app\index\model\Goods as GoodsModel;
/**
 * @author passerbyYSQ
 * @create 2020年6月18日 下午4:21:56
 */
class Cart extends Controller {
    
    // 购物车显示
    public function index() {
        // 未登录不允许操作
        if (empty(session('member'))) {
            $this->error('请先登录');
        }
        
        $model = new CartModel();
        //var_dump(session('member'));exit();
        $itemList = $model->list(session('member.id'));
        
        $this->assign('itemList', $itemList);
        return $this->fetch('cart_list');
    }
    
    // 删除购物车中的某个商品
    public function deleteOne() {
        if (empty(session('member')) || !request()->isAjax()) {
            return;
        }
        
        // 有一个安全bug。假如有人登录了自己的账号，修改前端页面的cartId，可以删除别人购物车的商品。
        // 暂时不考虑这么多。。。
        
        $cartId = input('post.cartId');
        $res = CartModel::destroy($cartId);
        return json($res == 1 ? true : false);
    }
    
    public function deleteMore() {
        if (empty(session('member')) && !request()->isAjax()) {
            return;
        }
        
        $cartIds = input('post.cartIds/a');
        $items = CartModel::where('id', 'in', $cartIds)->select();
        
        $res['status'] = true;
        foreach($items as $item) {
            if (empty($item) || $item->delete() != 1) {
                $res['status'] = false;
                $res['msg'] = '部分删除失败';
                return json($res);
            }
        }
        
        return json($res);
    }
    
    // ajax更新购物车某个商品的购买数量
    public function updateNum() {
        if (empty(session('member')) && !request()->isAjax()) {
            return;
        }

        $res['status'] = true;
        
        $cartId = input('post.cartId');
        $count = input('post.count');
        if (!is_numeric($count)) {
            $res['status'] = false;
            $res['msg'] = '参数非法，数量不是纯数字';
            return json($res);
        }
        
        $item = CartModel::get($cartId);

        // 判断是否超出库存
        $goods = GoodsModel::get($item->goodsId);
        if ($count > $goods->count) {
            $res['status'] = false;
            $res['msg'] = '库存不足，剩余 ' . $goods->count . '件';
            return json($res);
        }
            
        $item->count = $count;
        if (!$item->save()) {
            $res['status'] = false;
            $res['msg'] = $item->getError();
        }
        return json($res);
    }
    
    // 异步ajax往购物车里面添加商品
    public function add() { 
        //return json(input('post.'));

        // 未登录不允许操作
        if (empty(session('member'))) {
            $res['status'] = false;
            $res['msg'] = '请先登录';
            return json($res);
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
            
            $goods = GoodsModel::get($goodsId);
            if ($item->count >= $goods->count) {
                $res['status'] = false;
                $res['msg'] = '购物车中的商品数量超出现有库存';
            } else {
                // 该记录的'count'字段+1
                if (!$item->setInc('count', $count)) {
                    $res['status'] = false;
                    $res['msg'] = $item->getError();
                }
            }
        }
        return json($res);
    }
}
?>
