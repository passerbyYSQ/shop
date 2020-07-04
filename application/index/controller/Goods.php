<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Category as CategoryModel;
use app\index\model\Goods as GoodsModel;
use app\index\model\Cart;

class Goods extends Controller
{
    public function test() {
        
//         $arr = [1, 2, 3];
        
//         $res = implode('&cate[]=', $arr);
//         $arr1['cate[]'] = $res;
//         var_dump($arr1);
        
        var_dump($_GET);
    }
    
    public function index() {
        $goodsId = input('get.id');
        $goodsModel = new GoodsModel();
        $goods = $goodsModel->findById($goodsId);
        if (empty($goods)) {
            $this->error('商品不存在');
        } 
        
        $this->assign('goods', $goods);
        
        return $this->fetch('goods');
    }
    
    public function list() {
        // 获取所有分类
        $cateModel = new CategoryModel();        
        $cates = $cateModel->getAll();
        
        $conds = array();
       
        $conds['goodsName'] = input('goodsName', '');
        
        $conds['cates'] = array();
        if (request()->isPost()) {
            $conds['cates']  = input('cate/a', array()); // 勾选中的分类的id的数组
        } else if (request()->isGet()){
            //var_dump(input());exit();
            if (!empty(input('cate'))) {
                $conds['cates'] = explode(',', input('cate')); 
            }
        }
        $conds['minPrice'] = input('minPrice', ''); // 最低价
        $conds['maxPrice'] = input('maxPrice', ''); // 最高价
        $conds['sort'] = input('sort', 'onSaleTime desc');   // 排序方式
        
        $goodsModel = new GoodsModel();
        $res = $goodsModel->listPage($conds, 1);
        
        $this->assign('cates', $cates);
        $this->assign('goodsList', $res['items']);
        $this->assign('pageHtml', $res['pageHtml']);
        
        $conds['sort'] = explode(' ', $conds['sort']);
        
        //var_dump($conds);exit();
        $cartItemsCount = Cart::count();
        
        $this->assign('conds', $conds);
        $this->assign('cartItemsCount', $cartItemsCount);
        return $this->fetch();
    }
}
