<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Order as OrderModel;
use app\admin\model\OrderItem;
/**
 * @author passerbyYSQ
 * @create 2020年7月3日 下午3:03:09
 */

class Order extends Controller {
    
    // 发货
    public function send() {
        $deliveryId = input('get.deliveryId');
        $orderId = input('get.orderId');
        if (empty($deliveryId)) {
            $this->error('快递单号不能为空');
        }
        
        $order = OrderModel::get($orderId);
        if (empty($order)) {
            $this->error('订单号错误');
        }
        
        $order->deliveryId = $deliveryId;
        $order->status = 2;
        if ($order->save()) {
            $this->success('录入快递单号成功');
        } else {
            $this->error('录入快递单号失败');
        }
    }
    
    public function list() {
        // 由于通过url打开该网页，并没有表单项，所以需要默认值
        $isSend = input('isSend', 0);
        $orderId = input('orderId', '');
        
        // 用于数据回显
        $cond['isSend'] = $isSend;
        $cond['orderId'] = $orderId;
        
        $orderModel = new OrderModel();
        $paginator = $orderModel->orderIds($isSend, $orderId, input());
        $orderIds = $paginator->items();
        $pageHtml = $paginator->render();
        
        $itemModel = new OrderItem();
        $res = array();
        foreach ($orderIds as $orderId) {
            $id = $orderId['id'];
            $orderDetail = $orderModel->orderDetail($id);
            $itemList = $itemModel->orderItemList($id);
            
            $res[$id] = array($orderDetail, $itemList);
        }
        
        $this->assign('orderList', $res);
        //$this->assign('totalCount', );
        $this->assign('pageHtml', $pageHtml);
        $this->assign('cond', $cond);
        return $this->fetch('list');
    }
    
}

?>
