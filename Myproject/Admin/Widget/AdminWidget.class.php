<?php
namespace Admin\Widget;
use Admin\Controller\AdminAuthController;

class AdminWidget extends AdminAuthController {
    /**
     * 通用菜单显示
     */
    public function commonMenu() {
        $M_menucate = M('menucate');
        $M_menuitem = M('menuitem');
        
        // 菜单栏目
        $menu_cate = $M_menucate->where('isshow=1 AND authtype LIKE \'%|'.$_SESSION['r_auth'].'|%\'')->order('listorder DESC,addtime ASC')->select();
        //echo $_SESSION['r_auth'];
        // 菜单条目
        foreach ($menu_cate as $menu_cate_key=>$menu_cate_val) {
            //  AND authtype LIKE \'%|'.$_SESSION['r_auth'].'|%\'
            $menu_cate[$menu_cate_key]['submenu'] = $M_menuitem->where('menucateid='.$menu_cate_val['id'].' AND isshow=1 AND authtype LIKE \'%|'.$_SESSION['r_auth'].'|%\'')->order('addtime ASC')->select();
        }
        
        $this->menu_lists = $menu_cate;
        
        $this->display('Public:commonMenu');
    }
    
    /**
     * 管理菜单 单页列表显示组件
     */
	public function singlepagelists($cateid){
		$cateid = intval($cateid);
        
		$M_pages = M('pages');
		
		if($cateid) {
			$pages_rows = $M_pages->where(array('cateid'=>$cateid))->order('addtime ASC')->select();
		} else {
		    $pages_rows = $M_pages->order('addtime ASC')->select();
		}
		
		$this->pages_rows = $pages_rows;
		
		$this->display('Admin/widget_pagelists');
	}

	/**
	 * 管理菜单 分类显示组件
	 */
	public function catelists($catetype) {
	    $catetype = intval($catetype);
	     
	    $M_cate = M('cate');
	     
	    if($catetype) {
	        $cate_rows = $M_cate->where(array('catetype'=>$catetype))->order('modified ASC')->select();
	    } else {
	        $cate_rows = $M_cate->order('modified ASC')->select();
	    }
	    
	    $this->cate_rows = $cate_rows;
	    
	    $this->display('Admin/widget_caterows');
	}
	
	/**
	 * 产品发行
	 */
	public function releaseprod() {
	    $M_fundpage = M('fundpage');
	    $fundpage_rows = $M_fundpage->order('addtime DESC')->field('id,title')->select();
	    
	    $this->fundpage_rows = $fundpage_rows;
	    
	    $this->display('Admin/widget_fundpage');
	}
}