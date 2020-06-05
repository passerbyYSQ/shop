<?php
namespace app\index\model;
use think\Model;

/**
 * @author passerbyYSQ
 * @create 2020年6月5日 上午9:32:16
 */
class Category extends Model {
    
    // 获取所有分类，不分页
    public function getAll() {
        return db('category')
                    ->order(['sort' => 'desc', 'id' => 'desc'])
                    ->select();
    }
    
}

?>
