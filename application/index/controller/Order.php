<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Cart as CartModel;
use app\index\model\Order as OrderModel;
use app\index\model\Address as AddressModel;

/**
 * @author passerbyYSQ
 * @create 2020年6月21日 下午10:12:19
 */
class Order extends Controller {
    
    public function index() {
        //var_dump(input('post.'));exit();
        $cartIds = input('post.checkItem/a');
        //var_dump($cartIds);exit();
        
        $cartModel = new CartModel();
        $items = $cartModel->selectByIds($cartIds);
        
        $this->assign('allAddress', $this->allAddress());
        $this->assign('items', $items);
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
<div class=cs-w-item>
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
