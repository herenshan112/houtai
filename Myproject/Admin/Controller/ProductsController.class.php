<?php
namespace Admin\Controller;
use Think\Controller;
use PublicClass\PagesAjax;
use PublicClass\PublicSub;

class ProductsController extends Controller {
    /**
     * 项目列表
     */
    public function showlists() {
        $M_products = M('products');
        
        $count = $M_products->where(array('cateid'=>'0'))->count();  //查出总是
        $Page = new \Think\Page($count, 10);  // 实例化 分页类
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        $products_list = $M_products->where(array('cateid'=>'0'))->limit($Page->firstRow.','.$Page->listRows)->order('addtime DESC')->select();
        //$kcval=$products_list['totalnum'] - $products_list['salenum'];
        $this->products_list = $products_list;
        $this->page = $show;
        
        $this->display();
    }
    
    /**
     * 添加项目
     */
    public function add() {
        if(IS_POST) {
            $M_products = M('products');
            
            $data = I("post.");
            
            $data['addtime'] = date("Y-m-d H:i:s");
            $data['productnum']=numndle();
            $data['imgary']=$data['imgvales'];

            if($data['tejiaprice'] == ''){
                $data['tejiaprice'] = 0;
            }
            if($data['type'] == 0){
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo '<script>alert("请选择产品类型！");history.go(-1);</script>';
                return;
            }

            if($data['spec'] == 0){
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo '<script>alert("请选择产品规格");history.go(-1);</script>';
                return;
            }
            
            $added = $M_products->add($data);
            
            if ($added){
                $this->success("添加成功", U("showlists"));
            } else {
                $this->error("添加失败");
            }
        }  else {
            $cpls=M('produtype')->where(array('setval'=>1))->select();
            $this->assign('cpls',$cpls);
            $cpgg=M('proguige')->where(array('gg_set'=>1))->select();
            $this->assign('cpgg',$cpgg);
            $this->display();
        }
    }
    
    /**
     * 修改项目
     */
    public function mod() {
        $M_products = M('products');
        
        if(IS_POST){
            $data = I("post.");
            if($data['spec'] == ''){
                $data['spec']=0;
            }
            if($data['parts'] == ''){
                $data['parts']=0;
            }
            if($data['tejiaprice'] == ''){
                $data['tejiaprice'] = 0;
            }
            if($data['type'] == 0){
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo '<script>alert("请选择产品类型！");history.go(-1);</script>';
                return;
            }

            if($data['spec'] == 0){
                echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                echo '<script>alert("请选择产品规格！");history.go(-1);</script>';
                return;
            }
            
            $data['imgary']=$data['imgvales'];

            $moded = $M_products->save($data);
            if($moded){
                $this -> success("修改成功", U("showlists"));
            }else{
                $this -> error("修改失败");
            }
        } else {
            $id = I("get.id");
            	
            $data = $M_products->where(array("id"=>$id))->find();
            if($data['spec'] == 0){
                $data['spec'] = '';
            }
            if($data['parts'] == 0){
                $data['parts'] = '';
            }
            $this->data = $data;
            $cpls=M('produtype')->where(array('setval'=>1))->select();
            $this->assign('cpls',$cpls);
            $cpgg=M('proguige')->where(array('gg_set'=>1))->select();
            $this->assign('cpgg',$cpgg);
            $this->display();
        }
    }
    
    /**
     * 删除项目
     */
    public function del() {
        $M_products = M("products");
        $id = I("get.id");
        $del_row = $M_products->where(array('id'=>$id))->delete();
        if ($del_row) {
            $this->success("删除成功", U("showlists"));
        } else {
            $this->error("删除失败");
        }
    }
    /*
    *配件API
     */
    public function partsapi(){
        $shopdb=M('products');
        $action=I('post.action')?I('post.action'):'list';
        switch ($action) {
            case 'oneshop':
                $id=I('post.id');
                if($id != ''){
                    $list=$shopdb->field('id,title,titlepic,price,totalnum,salenum,tejia,tejiaprice,spec')->where(array('id'=>$id))->find();
                    if($list){
                        $ajaxcont=array(
                            'code'              =>              1,
                            'msg'               =>              '获取成功',
                            'infor'             =>              array(
                                'sum'           =>                  1,
                                'cont'          =>                  $list
                            )
                        );
                    }else{
                        $ajaxcont=array(
                            'code'              =>              0,
                            'msg'               =>              '没有找到该商品'
                        );
                    }
                }else{
                    $ajaxcont=array(
                        'code'              =>              0,
                        'msg'               =>              '请选择商品！'
                    );
                }
                break;
            case 'sousuo':
                $pagenum=I('post.p');
                $ssval=I('post.ssval');
                $type=I('post.type');
                switch ($type) {
                    case 'value':
                        # code...
                        break;
                    
                    default:
                        $where['title']=array('LIKE','%'.$ssval.'%');
                        break;
                }
                $shopsum=$shopdb->where($where)->count();
                $page=new PagesAjax($shopsum,1,array('action'=>$action,'ssval'=>$ssval));
                //$Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $list=$shopdb
                        ->field('id,title,titlepic,price,totalnum,salenum,tejia,tejiaprice,spec')
                        ->where($where)
                        ->order(array('id'=>'desc'))
                        ->limit($page->firstRow.','.$page->listRows)
                        ->select();
                $paglist=$page->show();
                if($list){
                    $ajaxcont=array(
                        'code'              =>              1,
                        'msg'               =>              '获取成功',
                        'infor'             =>              array(
                            'sum'           =>                  count($list),
                            'page'          =>                  $paglist,
                            'cont'          =>                  $list
                        )
                    );
                    
                }else{
                    $ajaxcont=array(
                        'code'              =>              0,
                        'msg'               =>              '还没有商品，请添加...'
                    );
                }
                break;
            default:
                $pagenum=I('post.p');
                $ssval=I('post.ssval');
                $shopsum=$shopdb->count();
                $page=new PagesAjax($shopsum,7,array('action'=>$action,'ssval'=>$ssval));
                //$Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $list=$shopdb
                        ->field('id,title,titlepic,price,totalnum,salenum,tejia,tejiaprice,spec')
                        ->order(array('id'=>'desc'))
                        ->limit($page->firstRow.','.$page->listRows)
                        ->select();
                $paglist=$page->show();
                if($list){
                    $ajaxcont=array(
                        'code'              =>              1,
                        'msg'               =>              '获取成功',
                        'infor'             =>              array(
                            'sum'           =>                  count($list),
                            'page'          =>                  $paglist,
                            'cont'          =>                  $list
                        )
                    );
                    
                }else{
                    $ajaxcont=array(
                        'code'              =>              0,
                        'msg'               =>              '还没有商品，请添加...'
                    );
                }
                break;
        }
        echo json_encode($ajaxcont);
    }

    /*
    *库存预警管理
     */
    public function early(){
        $action=I('get.action');
        if($action == ''){
            $action=I('post.action');
        }
        switch ($action) {
            case 'add':
                $this->action='addcl';
                $this->display('contearly');
                break;
            case 'addcl':
                
                $data=I('post.');
                if($data['title'] == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入预警名称");history.go(-1);</script>';
                    return;
                }
                if($data['earlyval'] == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入临界值");history.go(-1);</script>';
                    return;
                }
                $M_products = M('early');
                if($M_products->field('earlyval')->where(array('earlyval'=>$data['earlyval']))->find()){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("该临界值已经存在请更换！");history.go(-1);</script>';
                    return;
                }
                if($data['coloval'] == ''){
                    $data['coloval']='#FF0000';
                }

                if($M_products->add($data)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.U('Admin/Products/early/action/add').'";}else{location.href="'.U('Admin/Products/early/action/list').'";}</script>';
                }
                break;
            case 'eite':
                $id=I('get.id');
                if($id == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！请检查");history.go(-1);</script>';
                    return;
                }
                $M_products = M('early')->where(array('id'=>$id))->find();
                $this->list=$M_products;
                $this->action='eitecl';
                $this->id=$id;
                $this->display('contearly');
                break;
            case 'eitecl':
                $id=I('get.id');
                $data=I('post.');
                if($data['title'] == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入预警名称");history.go(-1);</script>';
                    return;
                }
                if($data['earlyval'] == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入临界值");history.go(-1);</script>';
                    return;
                }
                if($data['coloval'] == ''){
                    $data['coloval']='#FF0000';
                }
                $M_products = M('early');

                if($M_products->where(array('id'=>$id))->save($data)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("操作完成！");location.href="'.U('Admin/Products/early/action/list').'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("操作完成！信息没有变动！");location.href="'.U('Admin/Products/early/action/list').'"</script>';
                }
                break;
            default:
                $M_products = M('early');
        
                $count = $M_products->count();  //查出总是
                $Page = new \Think\Page($count, 10);  // 实例化 分页类
                //定制分页类
                $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $Page->setConfig('prev','上一页');
                $Page->setConfig('next','下一页');
                $Page->setConfig('first','首页');
                $Page->setConfig('last','末页');
                $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
                $show = $Page -> show();
                
                $products_list = $M_products->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
                
                $this->products_list = $products_list;
                $this->page = $show;

                $this->display();
                break;
        }
    }
    /*
    *产品分类管理
     */
    public function typelist(){
        $action=I('get.action');
        if($action == ''){
            $action=I('post.action');
        }
        switch ($action) {
            case 'add':
                $this->action='addcl';
                $this->display('typecont');
                break;
            case 'addcl':
                $dat['title']=I('post.title');
                $dat['setval']=I('post.setval')?1:0;
                $dat['xuhao']=I('post.xuhao')?I('post.xuhao'):50;
                if($dat['title'] == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入产品分类");history.go(-1);</script>';
                    return;
                }
                $dat['fatherid']=0;
                $dat['time']=time();
                if(M('produtype')->add($dat)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.U('Admin/Products/typelist/action/add').'";}else{location.href="'.U('Admin/Products/typelist/action/list').'";}</script>';
                    return;
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("写入失败！请重新提交！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'eite':
                $id=I('get.id');
                if($id){
                    $list=M('produtype')->where(array('id'=>$id))->find();
                    $this->list=$list;
                    $this->id=$id;
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                $this->action='eitecl';
                $this->display('typecont');
                break;
            case 'eitecl':
                $id=I('get.id');
                if($id){
                    $dat['title']=I('post.title');
                    $dat['setval']=I('post.setval')?1:0;
                    $dat['xuhao']=I('post.xuhao')?I('post.xuhao'):50;
                    if($dat['title'] == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请输入产品分类");history.go(-1);</script>';
                        return;
                    }
                    $dat['fatherid']=0;
                    if(M('produtype')->where(array('id'=>$id))->save($dat)){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！");location.href="'.U('Admin/Products/typelist/action/list').'"</script>';
                    }else{
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！信息没有变动！");location.href="'.U('Admin/Products/typelist/action/list').'"</script>';
                    }
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'del':
                $id=I('get.id');
                if($id){

                    if (M('produtype')->where(array('id'=>$id))->delete()) {
                        $this->success("删除成功", U("typelist"));
                    } else {
                        $this->error("删除失败");
                    }
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }

                break;
            default:
                $M_products = M('produtype');
        
                $count = $M_products->count();  //查出总是
                $Page = new \Think\Page($count, 10);  // 实例化 分页类
                //定制分页类
                $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $Page->setConfig('prev','上一页');
                $Page->setConfig('next','下一页');
                $Page->setConfig('first','首页');
                $Page->setConfig('last','末页');
                $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
                $show = $Page -> show();
                
                $products_list = $M_products->limit($Page->firstRow.','.$Page->listRows)->order(array('xuhao'=>'asc','id'=>'desc'))->select();
                //$kcval=$products_list['totalnum'] - $products_list['salenum'];
                //var_dump(PublicSub::LinearArray($products_list));
                //$this->products_list = PublicSub::LinearArray($products_list,'|-');
                $this->products_list = $products_list;
                $this->page = $show;
                $this->display();
                break;
        }
    }

    /*
    *评论
     */
    public function pinglun(){
        $id=I('get.id');
        $spxx=M('products')->where(array('id'=>$id))->find();
        $pllst=M('pcomments')
                    ->alias('p')
                    ->field('p.id as pid,p.addtime as ptime,p.*,u.id,username,phone,address,provinces,city,county,nickname,o.id,o.ordernum')
                    ->join('__USER__ u ON u.id=p.userid','LEFT')
                    ->join('__ORDERS__ o ON o.id=p.orderid','LEFT')
                    ->where(array('productid'=>$id))
                    ->order(array('p.id'=>'desc'))
                    ->select();
        
        $this->assign('spxx',$spxx);
        $this->assign('pllst',$pllst);
        $this->display();
    }
    /*
    *规格
     */
    public function guige(){
        $action=I('get.action')?I('get.action'):'list';
        switch ($action) {
            case 'add':
                $this->action='addcl';
                $this->display('guigecont');
                break;
            case 'addcl':
                $dat['gg_title']=I('post.title');
                $dat['gg_set']=I('post.setval')?1:0;
                if($dat['gg_title'] == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入规格名称");history.go(-1);</script>';
                    return;
                }
                $dat['gg_time']=time();
                if(M('proguige')->add($dat)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.U('Admin/Products/guige/action/add').'";}else{location.href="'.U('Admin/Products/guige/action/list').'";}</script>';
                    return;
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("写入失败！请重新提交！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'eite':
                $id=I('get.id');
                if($id){
                    $list=M('proguige')->where(array('gg_id'=>$id))->find();
                    $this->list=$list;
                    $this->id=$id;
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                $this->action='eitecl';
                $this->display('guigecont');
                break;
            case 'eitecl':
                $id=I('get.id');
                if($id){
                    $dat['gg_title']=I('post.title');
                    $dat['gg_set']=I('post.setval')?1:0;
                    if($dat['gg_title'] == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请输入规格名称");history.go(-1);</script>';
                        return;
                    }
                    if(M('proguige')->where(array('gg_id'=>$id))->save($dat)){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！");location.href="'.U('Admin/Products/guige/action/list').'"</script>';
                    }else{
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！信息没有变动！");location.href="'.U('Admin/Products/guige/action/list').'"</script>';
                    }
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'del':
                $id=I('get.id');
                if($id){

                    if (M('proguige')->where(array('gg_id'=>$id))->delete()) {
                        $this->success("删除成功", U("guige"));
                    } else {
                        $this->error("删除失败");
                    }
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }

                break;
            default:
                $M_products = M('proguige');
        
                $count = $M_products->count();  //查出总是
                $Page = new \Think\Page($count, 10);  // 实例化 分页类
                //定制分页类
                $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $Page->setConfig('prev','上一页');
                $Page->setConfig('next','下一页');
                $Page->setConfig('first','首页');
                $Page->setConfig('last','末页');
                $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
                $show = $Page -> show();
                
                $products_list = $M_products->limit($Page->firstRow.','.$Page->listRows)->order('gg_id DESC')->select();

                $this->products_list = $products_list;
                $this->page = $show;
                $this->display();
                break;
        }
    }


    /**
     * 删除产品信息
     */
    public function delall() {
        $p_opid = I('post.opid');
        
        $M_user = M('products');
        
        foreach ($p_opid as $k=>$v) {
            $deled[] = $M_user->delete($v);
            
        }
        
        if($deled) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    
}