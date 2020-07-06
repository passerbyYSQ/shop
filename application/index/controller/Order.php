<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Cart as CartModel;
use app\index\model\Order as OrderModel;
use app\index\model\OrderItem as OrderItemModel;
use app\index\model\Address as AddressModel;
use app\index\model\Goods as GoodsModel;;
use think\Exception;

/**
 * @author passerbyYSQ
 * @create 2020年6月21日 下午10:12:19
 */
class Order extends Controller {
    public function receive() {
        if (empty(session('member'))) {
            $this->error('请先登录');
        }
        
        $orderId = input('get.orderId');
        $order = OrderModel::get( // 已发货的订单才可以确认收货
            ['id'=>$orderId, 'memberId'=>session('member.id'), 'status'=>2]);
        if (empty($order)) {
            $this->error('订单号错误或无权限操作');
        }
        
        $order->status = 3; // 未评价
        $order->save();
        $this->success('确认收货成功');
    }
    
    // 删除订单
    public function delete() {
        if (empty(session('member'))) {
            $this->error('请先登录');
        }
        
        $orderId = input('get.orderId');
        $order = OrderModel::get( // 未支付的订单才可以删除
            ['id'=>$orderId, 'memberId'=>session('member.id'), 'status'=>0]); 
        if (empty($order)) {
            $this->error('订单号错误或无权限操作');
        }
        
        $items = OrderItemModel::where('orderId', $order->id)->select();
        Db::startTrans();
        try{
            foreach ($items as $item) {
                $item->delete();
            }
            
            $order->delete();
            // 提交事务
            Db::commit();
            $this->success('删除订单成功');
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->error('删除订单失败');
        }
    }
    
    // 支付订单。不要忘了修改出售数量！！！！！
    public function pay() {
        if (empty(session('member'))) {
            $this->error('请先登录');
        }
        
        $orderId = input('get.orderId');
        $order = OrderModel::get(['id'=>$orderId, 'memberId'=>session('member.id')]);
        if (empty($order)) {
            $this->error('订单号错误或无权限操作');
        }
        
        $items = OrderItemModel::where('orderId', $order->id)->select();
        Db::startTrans();
        try{   
            
            foreach ($items as $item) {
                $goods = GoodsModel::get($item->goodsId);
                
                // 库存是否充足
                if ($item->count > $goods->count) {
                    // 回滚事务
                    Db::rollback();
                    $this->error("下单失败，{$goods->goodsName} 商品库存不足");
                    return;
                }
                
                // 库存减少
                $goods->count -= $item->count;
                // 出售数量增加
                $goods->saleCount += $item->count;
                
                $goods->save();
            }
            
            // 修改订单状态
            $order->status = 1;
            $order->save();
            
            // 提交事务
            Db::commit();
            
            $this->success('支付成功');
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->error('支付失败');
        }
    }
    
    public function myorder() {
        //var_dump(input());exit();
        
        if(empty(session('member'))){
            $this->error('请先登录!','member/login');
            //return '请先登录';
        }
        
        $status = input('status', '');
        $cond['status'] = $status;
        $period = input('period', '');
        $cond['period'] = $period;
        $orderId = input('orderId', '');
        $cond['orderId'] = $orderId;
        
        $orderModel = new OrderModel();
        $paginator = $orderModel
            ->allOrderIds(session('member.id'), $status, $period, $orderId, input());
        $orderIds = $paginator->items();
        $pageHtml = $paginator->render();
        
        $itemModel = new OrderItemModel();
        $res = array();
        foreach ($orderIds as $orderId) {
            $id = $orderId['id'];
            $orderDetail = $orderModel->orderDetail($id);
            $itemList = $itemModel->allOrderItems($id);
            
            $res[$id] = array($orderDetail, $itemList);
        }
        
        
        $this->assign('orderList', $res);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('cond', $cond);
        $this->assign('cartItemsCount', CartModel::count());
        return $this->fetch('my_order');
        
    }
    
    public function test() {
        //var_dump(empty(0));
    }
    
    // 提交订单
    public function submit() {
        //var_dump(input('post.'));
        
        if (empty(session('member'))) {
            $this->error('请先登录');
        }
        
        $addressId = input('post.addressId');
        $payMethod = input('post.payment');
        $cartIds = input('post.cartIds/a');
        
        $address = AddressModel::get(
            ['id'=>$addressId, 'memberId'=>session('member.id')]);
        if (empty($address)) {
            $this->error('收货地址不存在', 'cart/index');
        }
        
        if ($payMethod != 0 || $payMethod != 1 || $payMethod != 2) {
            $payMethod = 0;
        }
        
        $orderId = $this->produceOrderId();
        $totalPay = 0;
        //var_dump('111');
        // 启动事务
        Db::startTrans();
        try{
            
            foreach ($cartIds as $cartId) {
                $cartModel = new CartModel();
                // 注意返回的是一个数组，而并非对象
                $cartItem = $cartModel->findOneNeedPrice($cartId, session('member.id'));
                //var_dump($cartItem);
                if (empty($cartItem)) {
                    //$flag = false;
                    // 回滚事务
                    Db::rollback();
                    $this->error('购物车无该商品，请勿重复下单！');
                    return;
                }
                
                $orderItem = new OrderItemModel();
                //var_dump($orderItem);
                
                $orderItem->goodsId = $cartItem['goodsId'];
                $orderItem->orderId = $orderId; 
                $orderItem->count = $cartItem['count'];
                $orderItem->memberId = session('member.id');
                $orderItem->save();
                
                $totalPay += ($cartItem['count'] * $cartItem['salePrice']);
                
                // 删除购物车
                //$cartItem->delete();
                CartModel::destroy($cartItem['id']);
                
            }
            
            // 插入订单
            $order = new OrderModel();
            $order->id = $orderId;
            $order->createTime = date('Y-m-d H:i:s');
            $order->postscript = input('post.postscript');
            $order->totalPay = $totalPay;
            $order->payMethod = $payMethod;
            $order->addressId = $address->id;
            $order->status = 0; // 未付款
            $order->memberId = session('member.id'); // 未付款
            $order->save();
            
            // 提交事务
            Db::commit();
            
            $this->success('下单成功，请前往付款', 'order/myorder');
            
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->error('提交订单失败');
        }
        
    }
    
    // 生成订单号
    public function produceOrderId() {
        $orderId = date('YmdHis') . sprintf("%06d", random_int(0, 999999));
        return $orderId;
    }
    
    public function index() {
        
        //var_dump(input('post.'));exit();
        $cartIds = input('post.checkItem/a');
        if (empty($cartIds)) {
            $this->error('请选择购物车中的商品');
        }
        //var_dump($cartIds);exit();
        
        $cartModel = new CartModel();
        $items = $cartModel->selectByIds($cartIds);
        
        
        $this->assign('allAddress', $this->allAddress());
        $this->assign('items', $items);
        $this->assign('itemCount', count($items));
        return $this->fetch('order');
    }
    
    // 删除一个地址
    public function deleteAddress() {
        if (empty(session('member'))) {
            return;
        }
        
        $addressId = input("post.addressId");
        $address = AddressModel::get($addressId);
        
        $res['status'] = true;
        // 不是本人，没有权限操作
        if ($address->memberId != session('member.id')) {
            $res['status'] = false;
            $res['msg'] = '您没有权限操作';
        }
        
        if ($address->delete() != 1) {
            $res['status'] = false;
            $res['msg'] = $address->getError();
        }
        
        return json($res);
        
    }
    
    // 获取所有收获地址，并拼接成Html代码返回给页面
    public function allAddress() {
        
        $addrs = AddressModel::where('memberId', session('member.id'))
        ->order('id', 'desc')->select();
        
        $html = '';
        foreach ($addrs as $addr) {
            $html .= $this->addressHtml($addr->consigneeName, $addr->mobilePhone, $addr->telephone,
                $addr->province, $addr->city, $addr->area, $addr->detail, $addr->id);
        }
        
        return $html;
    }
    
    // 添加收获地址
    public function addAddress() {
        if (empty(session('member'))) {
            return;
        }
        
        $model = new AddressModel();
        
        $model->memberId = session('member.id');
        $model->consigneeName = input('post.consigneeName');
        $model->mobilePhone = input('post.mobilePhone');
        $model->telephone = input('post.telephone', ''); // 有可能没有
        $model->province = input('post.province');
        $model->city = input('post.city'); 
        $model->area = input('post.area',  '');  // 有可能没有
        $model->detail = input('post.detail');
        
        $res['status'] = true;
        //$model->save();
        //return json($model);
        if ($model->save()) {
            // $this->addressHtml()。注意$this->不能少，否则500错误
            $res['data'] = $this->addressHtml($model->consigneeName, $model->mobilePhone, $model->telephone,
                $model->province, $model->city, $model->area, $model->detail, $model->id);
        } else {
            $res['status'] = false;
            $res['msg'] = '添加地址失败：' . $model->getError();
        }
        return $res;
    }
    
    function addressHtml($consigneeName, $mobilePhone, $telephone, 
        $province, $city, $area, $detail, $addressId) {
        $html = "
<div class=cs-w-item onClick='selectAddress(this, {$addressId})'>
   <div class=item-tit><h3 class=username>{$consigneeName}</h3></div>
   <div class=item-tel>
      <span class=contact>{$mobilePhone}</span>
      <span class=contact>{$telephone}</span>
   </div>
   <div class=item-address>{$province}  {$city} {$area} &nbsp;&nbsp;&nbsp; {$detail}</div>
   <i class=icon></i> <a href=javascript:void(0); class=edit onClick='showAddressDialog(false)'>修改</a>
   <a href=javascript:void(0) class=delete onClick='deleteAddress({$addressId}, this)'>删除</a>
</div>";
        return $html;
    }
    
}
?>
