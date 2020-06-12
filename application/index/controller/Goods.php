<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Category as CategoryModel;
use app\index\model\Goods as GoodsModel;

class Goods extends Controller
{
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
        // 接收checkbox（勾选的多个分类）
        $conds['cates']  = input('cate/a', array()); // 勾选中的分类的id的数组
        $conds['minPrice'] = input('minPrice', ''); // 最低价
        $conds['maxPrice'] = input('maxPrice', ''); // 最高价
        $conds['sort'] = input('sort', 'onSaleTime desc');   // 排序方式
        
        $goodsModel = new GoodsModel();
        $res = $goodsModel->listPage($conds);
        
        $this->assign('cates', $cates);
        $this->assign('goodsList', $res['items']);
        $this->assign('pageHtml', $res['pageHtml']);
        
        $conds['sort'] = explode(' ', $conds['sort']);
        
        //var_dump($conds);exit();
        
        $this->assign('conds', $conds);
        
        return $this->fetch();
    }
}
