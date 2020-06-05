<?php
namespace app\index\model;
use think\Model;
use think\db\Query;
/**
 * @author passerbyYSQ
 * @create 2020年6月5日 上午9:52:20
 */

class Goods extends Model {
    
    public function listPage($conds, $count = 12) {
        // 组织Query对象。并将【不为空的条件】，存到$parms数组，用于拼接到url后面和回显数据
        // 最好不要直接拼接sql，而是采用tp5封装的Query对象
        // 这个部分应该放在Model中完成，最好不要在Controller中
        $param = array();
        
        $query = new Query();
        $query = $query->table('goods');
        if (!empty($conds['cates'])) {
            $query = $query->where('cateId', 'in', $conds['cates']);
            $param['cates'] = $conds['cates'];
        }
        if (!empty($conds['minPrice'])) {
            $query = $query->where('salePrice', '>=', intval($conds['minPrice']));
            $param['minPrice'] = $conds['minPrice'];
        }
        if (!empty($conds['maxPrice'])) {
            $query = $query->where('salePrice', '<=', intval($conds['maxPrice']));
            $param['maxPrice'] = $conds['maxPrice'];
        }
        $query = $query->order($conds['sort']);
        
        // tp 5的分页逻辑已经封装好了。直接调用paginate()即可
        // 第3个参数：拼接在分页的url后面的参数。'query'，不能自己改
        
        $paginator = $query->field('id, goodsName, mainPic, salePrice, saleCount')
                        ->paginate($count);

        // 获取分页按钮栏的Html代码
        $data['pageHtml'] = $paginator->render();
        // 当前页的数据
        $data['items'] = $paginator->items();
        
        return $data;
    }
    
}

?>
