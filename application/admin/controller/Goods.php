<?php
namespace app\admin\controller;
include_once '../extend/upload_inc.php'; // 当前所在目录是index.php所在目录
use think\Controller;
use app\admin\model\Category as CategoryModel;
use app\admin\model\Goods as GoodsModel;

/**
 * @author passerbyYSQ
 * @create 2020年5月28日 下午11:56:58
 */
class Goods extends BaseController {
    
    public function list() {
        $keyword = input('get.keyword');
        $conds = array();
        if (!empty($keyword)) {
            // 注意key要与表的字段名一致
            $conds['goodsName'] = ['like', '%' . $keyword. "%"];
        }
        
        $goodsModel = new GoodsModel();
        // 当前页的数据
        $goodsList = $goodsModel->list($conds, request()->param(), 5);
        // 按钮栏的Html代码
        $btnHtml = $goodsList->render();
        //var_dump($cates->render());exit();
        // 总共多少条记录
        $totalCount = $goodsModel->getCount($conds);
        
        // 绑定变量
        $this->assign('goodsList', $goodsList);
        $this->assign('btnHtml', $btnHtml);
        $this->assign('totalCount', $totalCount);
        return $this->fetch();
    }
    
    public function delete() {
        if (session('admin.permission') != 0) {
            $this->error('您无权限操作');
        }
        
        $cateId = input('get.id');
        $goodsModel = new GoodsModel();
        $count = $goodsModel->deleteById($cateId);
        if ($count == 1) {
            $this->success('删除成功', 'list');
        } else {
            $this->error('删除失败');
        }
    }
    
    public function update() {
        $goodsModel = new GoodsModel();
        
        if (request()->isPost()) {
            // 参数判断
            $res = $this->validate(input('post.'), [
                'goodsName' => ['length:2,64'],
                'count' => ['regex' => '/^[1-9]\d*$/']
            ], [
                'goodsName.length' => '商品长度必须在[2, 32]之间',
                'count.regex' => '库存数量必须为非负的整数'
            ]);
            
            if (true !== $res) {
                $this->error($res);
            }
            
            // 检查商品名称是否已存在
            $goodsModel = new GoodsModel();
            $res = $goodsModel->findByName(input('post.goodsName'));
//             var_dump($res);
//             var_dump(input('post.id'));exit();
            if (!empty($res) && $res['id'] != input('post.id') ) {
                $this->error('商品名称已存在！！');
            }
            
            // 接收post表单
            $goods = input('post.');
            // 有就复写掉，没有就新增
            $goods['onSale'] = input('post.onSale') !== null ? 1 : 0;
            $goods['onSaleTime'] = date('Y-m-d H:i:s');
            // 移动上传的主图
            if (isset($_FILES['mainPic'])) {
                $save_path = 'upload/'. date('Y/m/d'); // 当前目录在index.php所在目录下
                $upload = upload($save_path, '2M', 'mainPic');
                if (!$upload['result']) {
                    $this->error('商品主图上传失败：' . $upload['error']);
                }
                $goods['mainPic'] = $upload['save_path'];
            }

            $res = $goodsModel->updateById($goods, $goods['id']);
            if (!empty($res)) {
                $this->success('修改成功', 'list');
            } else {
                $this->error('修改失败');
            }  
            
        } else {            
            // get请求，表示显示修改页面
            $goodsId = input('get.id');
            //var_dump($goodsId);exit();
            
            $goods = $goodsModel->findById($goodsId);
            if (empty($goods)) {
                $this->error('商品不存在');
            }
            
            $cateModel = new CategoryModel();
            $cates = $cateModel->getAll();
            
            $this->assign('goods', $goods);
            $this->assign('cates', $cates);
            return $this->fetch();
        }
        
    }
    
    public function add() {
        if (request()->isGet()) {
            $cateModel = new CategoryModel();
            $cates = $cateModel->getAll();
            
            $this->assign('cates', $cates);
            return $this->fetch();
            
        } else {
//             var_dump(request()->file());
            //var_dump(input('post.'));exit();
//             var_dump($_FILES);

            // 参数判断
            $res = $this->validate(input('post.'), [
                'goodsName' => ['length:2,64'],
                'count' => ['regex' => '/^[1-9]\d*$/']
            ], [
                'goodsName.length' => '商品长度必须在[2, 32]之间',
                'count.regex' => '库存数量必须为非负的整数'
            ]);
            
            if (true !== $res) {
                $this->error($res);
            }

            // 检查商品名称是否已存在
            $goodsModel = new GoodsModel();
            $res = $goodsModel->findByName(input('post.goodsName'));
            if (!empty($res)) {
                $this->error('商品名称已存在');
            }
            
            // 移动上传的主图
            $save_path = 'upload/'. date('Y/m/d'); // 当前目录在index.php所在目录下
            $upload = upload($save_path, '2M', 'mainPic');
            if (!$upload['result']) {
                $this->error('商品主图上传失败：' . $upload['error']);
            }

            // 接收post表单
            $goods = input('post.');
            // 有就复写掉，没有就新增
            $goods['onSale'] = input('post.onSale') !== null ? 1 : 0;
            $goods['mainPic'] = $upload['save_path'];
            if ($goods['onSale']) {
                $goods['onSaleTime'] = date('Y-m-d H:i:s');
            }
            
            $res = $goodsModel->add($goods);
            if (!empty($res)) {
                $this->success('添加商品成功', 'list');
            } else {
                $this->error('添加失败');
            }         
        }
    }
    
    public function uploadimg() {
        //echo "后端接收图片";exit();
        $save_path = 'upload/'. date('Y/m/d'); // 当前目录在index.php所在目录下
        $upload = upload($save_path, '6M', 'file');
        return json($upload);
    }
    
}

?>
