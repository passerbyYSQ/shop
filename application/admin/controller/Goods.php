<?php
namespace app\admin\controller;
include_once '../extend/upload_inc.php'; // 当前所在目录是index.php所在目录
use think\Controller;
use app\admin\model\Category as CategoryModel;

/**
 * @author passerbyYSQ
 * @create 2020年5月28日 下午11:56:58
 */
class Goods extends Controller {
    
    public function add() {
        if (request()->isGet()) {
            $cateModel = new CategoryModel();
            $cates = $cateModel->getAll();
            
            $this->assign('cates', $cates);
            return $this->fetch();
            
        } else {
            var_dump(request()->file());
            var_dump(input('post.'));
            //var_dump($_FILES);
        }
    }
    
    public function uploadimg() {
        //echo "后端接收图片";exit();
        $save_path = 'upload/'. date('Y/m/d');
        $upload = upload($save_path, '6M', 'file');
        return json($upload);
    }
    
}

?>
