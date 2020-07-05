<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Category as CategoryModel;

/**
 * @author passerbyYSQ
 * @create 2020年5月26日 下午9:52:16
 */
class Category extends BaseController {
    
    public function add() {
        // 助手函数
        if (request()->isPost()) {
            $cateName = input('post.cateName');
            $cateDesc = input('post.cateDesc');
            $sort = input('post.sort');

            $model = new CategoryModel();

            $res = $model->findByName($cateName);
            if (empty($cateName)) {
                $this->error('分类名不能为空');
            } else if (!empty($res)) {
                // 判断分类名是否已经存在
                $this->error('分类名已存在');
            } 
            
            $res = $model->add($cateName, $cateDesc, $sort);
            if (!empty($res)) {
                $this->success('添加分类成功', 'list');
            } else {
                $this->error('添加分类失败');
            }
            return;
        }
        
        return view();
    }
    
    public function list() {
        
        $keyword = input('get.keyword');
        $conds = array();
        if (!empty($keyword)) {
            // 注意key要与表的字段名一致
            $conds['cateName'] = ['like', '%' . $keyword. "%"];
        }
        
        $model = new CategoryModel();
        // 当前页的数据
        $cates = $model->list($conds, request()->param(), 5);
        // 按钮栏的Html代码
        $btnHtml = $cates->render();
        //var_dump($cates->render());exit();
        // 总共多少条记录
        $totalCount = $model->getCount($conds);
        
        // 绑定变量
        $this->assign('cates', $cates);
        $this->assign('btnHtml', $btnHtml);
        $this->assign('totalCount', $totalCount);
        return $this->fetch();
    }
    
    public function delete() {
        
        if (session('admin.permission') != 0) {
            $this->error('您无权限操作');
        }
        
        $cateId = input('get.id');
        $model = new CategoryModel();
        $count = $model->deleteById($cateId);
        if ($count == 1) {
            $this->success('删除成功', 'list');
        } else {
            $this->error('删除失败');
        }
    }
    
    public function update() {
        $model = new CategoryModel();

        if (request()->isPost()) {
            $cateId = input('post.cateId');
            $cateName = input('post.cateName');
            $cateDesc = input('post.cateDesc');
            $sort = input('post.sort');
            
            // 检查参数
            if (empty($cateName)) {
                $this->error('分类名不能为空');
            } 

            // 重新包装需要的参数，传递给Model
            $cate = [
                'cateName' => $cateName,
                'cateDesc' => $cateDesc,
                'sort' => $sort
            ];
            
            $count = $model->updateById($cate, $cateId);
            //var_dump(input('get.'));exit();
            //var_dump(db('category')->getLastSql());exit();

            if ($count == 1) {
                $this->success('修改成功', 'list');
            } else {
                $this->error('修改失败', 'list');
            }
            
        } else {
            // get请求，表示显示修改页面
            $cateId = input('get.id');
            
            $cate = $model->findById($cateId);
            if (empty($cate)) {
                $this->error('分类不存在');
            }
            
            $this->assign('cate', $cate);
            return $this->fetch();
        } 
        
    }
    
}

?>
