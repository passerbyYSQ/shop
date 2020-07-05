<?php
namespace app\admin\model;
use think\Model;
use think\Db;

/**
 * @author passerbyYSQ
 * @create 2020年5月27日 下午8:51:08
 */
class Goods extends Model {
    
    public function list($conds, $parms, $count = 1) { // 每页显示的条数
        //var_dump($parms);exit();
        return db('goods')
                    ->where($conds)
                    ->join('category cate', 'goods.cateId=cate.id')
                    ->field('goods.*, cate.cateName')
                    ->order(['onSale'=>'desc', 'id'=>'desc'])
                    ->paginate($count, false, ['query' => $parms]);
    }
    
    public function getCount($conds) {
        return db('goods')->where($conds)->count();
    }

    public function add($goods) {
        return db('goods')->insert($goods);
    }
    
    public function deleteById($id) {
        return db('goods')
                    ->delete($id);
    }
    
    public function updateById($goods, $id) {
        return db('goods')
                    ->where('id', $id)
                    ->update($goods);
    }

    public function findByName($goodsName) {
        return db('goods')
                    ->where('goodsName', $goodsName)
                    ->find();
    }
    
    public function findById($id) {
        return db('goods')->where('id', $id)->find();
    }
    
}

?>
