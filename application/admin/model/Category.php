<?php
namespace app\admin\model;
use think\Model;
use think\Db;

/**
 * @author passerbyYSQ
 * @create 2020年5月27日 下午8:51:08
 */
class Category extends Model {
    
    // 拿到所有分类（不需要分页）
    public function getAll() {
        return db('category')->select();
    }
    
    public function list($conds, $count = 1, $parms) { // 每页显示的条数  
        //var_dump($parms);exit();
        return db('category')
                    ->where($conds)
                    ->order(['sort'=>'desc', 'id'=>'desc'])
                    ->paginate($count, false, ['query' => $parms]);
    }
    
    public function getCount($conds) {
        return db('category')->where($conds)->count();
    }
    
    public function add($cateName, $cateDesc, $sort) {
        $cate = [
            'cateName' => $cateName,
            'cateDesc' => $cateDesc,
            'sort' => $sort
        ];
        
        return db('category')->insert($cate);
    }
    
    public function findById($cateId) {
        return Category::get($cateId);
    }
    
    public function findByName($cateName) {
        return db('category')
                    ->where('cateName', $cateName)
                    ->find();
    }
    
    public function deleteById($id) {
        return db('category')
                    ->delete($id);
    }
    
    
    public function updateById($cate, $id) { 
        return db('category')
                    ->where('id', $id)
                    ->update($cate);
    }
    
    
    
}

?>
