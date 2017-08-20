<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends Controller {
    /**
     * 会员列表
     */
    public function lists() {
        $M_user = M('user');

        $p_user = I('get.user');
        $p_op = I('get.op', '1', 'intval');
        
        //echo $p_user;

        $user_where['cateid'] = array('eq', $p_op);
        if($p_user) {
            $user_where['phone'] = array('like', "%$p_user%");
        }
        
        $count = $M_user->where($user_where)->count();
        
        $Page = new \Think\Page($count, 15);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        
        $users = $M_user->where($user_where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        
        
        $this->lists = $users;
        $this->op = $p_op;
        $this->page = $show;
        
        $this->display('lists');
    }
    
    /**
     * 会员详情
     */
    public function detail($id=0) {
        $M_user = M('user');
        $user = $M_user->find($id);
        
        $M_corn = M('corn');
        $user['corn'] = $M_corn->where('cateid=1 AND uid='.$id)->getField('corn');
        
        $this->user = $user;
        $this->display();
    }
    
    /**
     * 添加会员
     */
    public function add() {
        if(IS_POST) {
            $p_nick = I('post.nickname');
            $p_user = I('post.username');
            $p_pass = I('post.password');
            $p_email = I('post.email');
            $p_domain_arr = I('post.domain');
            
            $M_user = M('user');
            $M_domain = M('domain');
            
            $user_data['nickname'] = $p_nick;
            $user_data['username'] = $p_user;
            $user_data['password'] = jiamimd5($p_pass);
            $user_data['email'] = $p_email;
            
            $userid = $M_user->add($user_data);
            if(!$userid) {
                $this->error("添加失败");
            }
            
            $domain_data = array();
            foreach ($p_domain_arr as $p_domain_item) {
                if($p_domain_item){
                    $domain_data[] = array('userid'=> $userid,'domain'=>$p_domain_item, 'addtime'=>date('Y-m-d H:i:s'));
                }
            }
            $domain_add = $M_domain->addAll($domain_data);
            if(!$domain_add) {
                $this->error("域添加失败");
            }
            $this->success("添加成功" , U('lists'));
        } else {
            $this->display();
        }
    }
    
    /**
     * 修改会员
     */
    public function mod() {

        if(IS_POST) {
            $p_password = I('post.password');
            $p_id = I('post.id');
            
            if (!$p_password OR !$p_id) $this->error('数据提交异常！');
            
            $M_user = M('user');
            $saved = $M_user->where(array('id'=>$p_id))->save(array('password'=>jiamimd5($p_password)));
            
            if(!$saved) {
                $this->error("修改失败！");
            } else {
                $this->success('修改成功!');
            }
            
        } else {
            $userid = I('get.id');
            $user = $this->getUser($userid);
            if(!$user) {
                $this->error('数据异常或用户不存在!');
            }
            
            $this->user = $user;
            $this->display();
        }
    }
    
    /**
     * 删除会员
     */
    public function del($id=0) {
        $user = $this->getUser($id);
        if(!$user){
            $this->error('数据异常或用户不存在!');
        }
        
        $M_user = M('user');
        $deled = $M_user->delete($id);
        if($deled) {
            // 删除推荐记录
            $M_recommend = M('recommend');
            $M_recommend->where(array('uid'=>$id))->delete();
            
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    
    /**
     * 删除会员
     */
    public function delall() {
        $p_opid = I('post.opid');
        
        $M_user = M('user');
        $M_recommend = M('recommend');
        
        foreach ($p_opid as $k=>$v) {
            $deled[] = $M_user->delete($v);
            
            // 删除推荐记录
            $M_recommend->where(array('uid'=>$v))->delete();
        }
        
        if($deled) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    
    /**
     * 获取会员信息
     */
    private function getUser($userid=0) {
        $M_user = M('user');
        
        if(!$userid) {
            return false;
        }
        
        $user = $M_user->find($userid);
        if(!$user) {
            return false;
        }
        
        return $user;
    }
    
    /**
     * 渠道列表
     */
    public function doclists() {
        $M_doctor = M('doctor');

        $where['phone'] = array('LIKE','%%');
        $p_user = I('get.user');

        if($p_user) {
            $user_where['phone'] = array('like', "%$p_user%");
        }
        
        $count = $M_doctor->where($user_where)->count();
        
        $Page = new \Think\Page($count, 15);
        //定制分页类
        $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','末页');
        $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
        $show = $Page -> show();
        
        
        $doctors = $M_doctor->where($user_where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        $this->lists = $doctors;
        $this->page = $show;
        
        $this->display();
    }
    
    /**
     * 导出信息
     */
    public function export($typeid=1) {
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        
        require_once LIB_PATH. 'Org/Util/PHPExcel.class.php';
        
        $objPHPExcel = new \PHPExcel();
        
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("PHPExcel Test Document")
        ->setSubject("PHPExcel Test Document")
        ->setDescription("Test document for PHPExcel, generated using PHP classes.")
        ->setKeywords("office PHPExcel php")
        ->setCategory("Test result file");
        
        // 设置标题
        
        if ($typeid==1) {
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '帐号')
            ->setCellValue('B1', '手机号')
            ->setCellValue('C1', '名称')
            ->setCellValue('D1', '性别')
            ->setCellValue('E1', '邮箱')
            ->setCellValue('F1', '固定电话')
            ->setCellValue('G1', '生日')
            ->setCellValue('H1', '地址')
            ->setCellValue('I1', '注册时间')
            ->setCellValue('J1', '是否微信授权');
        } else {
            // 渠道
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '帐号')
            ->setCellValue('B1', '手机号')
            ->setCellValue('C1', '联系人')
            ->setCellValue('D1', '邮箱')
            ->setCellValue('E1', '固定电话')
            ->setCellValue('F1', '性别')
            ->setCellValue('G1', '生日')
            ->setCellValue('H1', '公司名称')
            ->setCellValue('I1', '公司地址')
            ->setCellValue('J1', '销售区域')
            ->setCellValue('K1', '备注')
            ->setCellValue('L1', '是否微信授权');
        }
        
        
        // 筛选用户信息
        $M_user = M('user');
        $user_rows = $M_user->where(array('cateid'=>$typeid))->order('addtime DESC')->select();
        
        $sex = array('', '男', '女');
        
        $i = 1;
        $wx = '否';
        foreach ($user_rows as $user_k=>$user_v) {
            $i++;
            
            if ($user_v['openid']) {
                $wx = '是';
            }
            
            if ($typeid==1) {
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $user_v['username'])
                ->setCellValueExplicit('B'.$i, $user_v['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('C'.$i, $user_v['nickname'])
                ->setCellValue('D'.$i, $sex[$user_v['sex']])
                ->setCellValue('E'.$i, $user_v['email'])
                ->setCellValue('F'.$i, $user_v['telval'])
                ->setCellValue('G'.$i, date('Y-m-d H:i:s',$user_v['shengri']))
                ->setCellValue('H'.$i, szcitycx($user_v['city'],$user_v['county'],$user_v['address'],$user_v['provinces']))
                ->setCellValue('I'.$i, date('Y-m-d H:i:s',$user_v['addtime']))
                ->setCellValue('J'.$i, $wx);
            } else {
                // 医生
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $user_v['username'])
                ->setCellValueExplicit('B'.$i, $user_v['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('C'.$i, $user_v['nickname'])
                ->setCellValue('D'.$i, $user_v['email'])
                ->setCellValue('E'.$i, $user_v['telval'])
                ->setCellValue('F'.$i, $sex[$user_v['sex']])
                ->setCellValue('G'.$i, date('Y-m-d H:i:s',$user_v['shengri']))

                ->setCellValue('H'.$i, $user_v['poratename'])
                ->setCellValue('I'.$i, $user_v['porateaddress'])
                ->setCellValue('J'.$i, szcitycx($user_v['city'],$user_v['county'],$user_v['address'],$user_v['provinces']))
                ->setCellValue('K'.$i, $user_v['count'])

                ->setCellValue('L'.$i, $wx);
            }
        }
        
        //$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
        //$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
        //$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
        
        // Rename worksheet
        if ($typeid==1) {
            $objPHPExcel->getActiveSheet()->setTitle('用户信息导出');
        } else {
            $objPHPExcel->getActiveSheet()->setTitle('经销商信息导出');
        }
        
        
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        /*$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));*/
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.date('Y-m-d_').rand(1000, 9999).'.xlsx"');
        header('Cache-Control: max-age=0');
        
        $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save( 'php://output');
    }

    /*
    *添加会员
     */
    public function usercontcl(){
        $action=I('get.action');
        if($action == ''){
            $action=I('post.action');
        }
        $typeid=I('get.typeid');
        if($typeid == ''){
            $typeid=I('post.typeid')?I('post.typeid'):1;
        }

        switch ($action) {
            case 'add':
                $prolist=M('region')->where(array('PARENT_ID'=>1))->order(array('REGION_ID'=>'asc'))->select();

                $this->assign('prolist',$prolist);
                $this->typeid=$typeid;
                $this->action='addcl';
                $this->display('usercont');
                break;
            case 'addcl':
                $data['cateid']=$typeid;
                $data['nickname']=I('post.nickname');
                $password=I('post.password');
                $dpassword2=I('post.password2');
                $data['email']=I('post.email');
                $data['phone']=I('post.phone');
                $data['headpic']=I('post.headpic');
                


                if($data['phone'] == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入用户名");history.go(-1);</script>';
                    return;
                }
                if(M('user')->field('phone')->where(array('phone'=>$data['phone']))->find()){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("该用户已经存在！请更换");history.go(-1);</script>';
                    return;
                }
                if($data['nickname'] == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入姓名");history.go(-1);</script>';
                    return;
                }
                if($password == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入密码");history.go(-1);</script>';
                    return;
                }
                if($password != $dpassword2){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("你两次输入的密码不一致！");history.go(-1);</script>';
                    return;
                }


                
                    $data['provinces']=I('post.provinces');
                    $data['city']=I('post.city');
                    $data['county']=I('post.county');
                    $data['address']=I('post.address');
                if($typeid == 2){
                    $data['code']=foo();
                    if($data['provinces'] == -1){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请选择经销商所在城市");history.go(-1);</script>';
                        return;
                    }
                    if($data['city'] == -1){
                            $data['city']=0;
                        }
                        if($data['county'] == -1){
                            $data['county']=0;
                        }
                }else{
                    if($data['provinces'] == -1){
                        $data['provinces']=0;
                    }
                    if($data['city'] == -1){
                            $data['city']=0;
                        }
                        if($data['county'] == -1){
                            $data['county']=0;
                        }
                }

                $data['password']=jiamimd5($dpassword2);
                $data['addtime']=time();

                if(M('user')->add($data)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.U('Admin/User/usercontcl/action/add/typeid/'.$typeid).'";}else{location.href="'.U('Admin/user/lists/op/'.$typeid).'";}</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("写入失败！请重新提交！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'jumpus':
                $dtval['phone']=I('post.username');
                if(M('user')->where($dtval)->find()){
                    echo json_encode(array('code'=>0,'msg'=>'该手机号已经被使用！'));
                }else{
                    echo json_encode(array('code'=>1,'msg'=>'可以注册'));
                }
                break;
            case 'eite':
                $prolist=M('region')->where(array('PARENT_ID'=>1))->order(array('REGION_ID'=>'asc'))->select();
                $this->assign('prolist',$prolist);
                $id=I('get.id');
                if($id == ''){
                    $typeid=I('post.id');
                }
                if($id){
                    $list=M('user')->where(array('id'=>$id))->find();
                    $this->typeid=$typeid;
                    $this->action='eitecl';
                    $this->id=$id;

                    if($list['provinces'] != 0){
                        $this->assign('citylt',addreslook($list['provinces']));
                    }
                    if($list['city'] != 0){
                        $this->assign('countylst',addreslook($list['city']));
                    }
                    $this->assign('list',$list);

                    $this->display('eitecont');
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'eitecl':
                $id=I('get.id');
                if($id == ''){
                    $typeid=I('post.id');
                }
                if($id){
                    $data['nickname']=I('post.nickname');
                    $data['email']=I('post.email');
                    $data['headpic']=I('post.headpic');
                    if($data['nickname'] == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请输入姓名");history.go(-1);</script>';
                        return;
                    }
                        $data['provinces']=I('post.provinces');
                        $data['city']=I('post.city');
                        $data['county']=I('post.county');
                        $data['address']=I('post.address');
                    if($typeid == 2){
                        if($data['provinces'] == -1){
                            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                            echo '<script>alert("请选择经销商所在城市");history.go(-1);</script>';
                            return;
                        }
                        if($data['city'] == -1){
                            $data['city']=0;
                        }
                        if($data['county'] == -1){
                            $data['county']=0;
                        }
                    }else{
                        if($data['provinces'] == -1){
                            $data['provinces']=0;
                        }
                        if($data['city'] == -1){
                            $data['city']=0;
                        }
                        if($data['county'] == -1){
                            $data['county']=0;
                        }
                    }
                    if(M('user')->where(array('id'=>$id))->save($data)){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！");location.href="'.U('Admin/user/lists/op/'.$typeid).'"</script>';
                    }else{
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！信息没有变动！");location.href="'.U('Admin/user/lists/op/'.$typeid).'"</script>';
                    }
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                break;
            default:
                $this->typeid=$typeid;
                $this->action='list';
                self::lists();
                break;
        }
    }
    /*
    *查询地址
     */
    public function lookaddres(){
        $proval=I('get.proval');
        if($proval == ''){
            $proval=I('post.proval')?I('post.proval'):-1;
        }
        if($proval != -1){
            $adrlist=addreslook($proval);
            if($adrlist){
                echo json_encode(array('code'=>1,'msg'=>'查询成功','infor'=>array('sum'=>count($adrlist),'cont'=>$adrlist)));
            }else{
                echo json_encode(array('code'=>0,'msg'=>'没有下级地区'));
            }
        }else{
            echo json_encode(array('code'=>0,'msg'=>'参数错误！'));
        }
    }

    /*
    *添加会员
     */
    public function addhuiyuan(){
        $action=I('get.action');
        if($action == ''){
            $action=I('post.action');
        }
        $typeid=I('get.typeid');
        if($typeid == ''){
            $typeid=I('post.typeid')?I('post.typeid'):1;
        }
        switch ($action) {
            case 'add':

                $prolist=M('region')->where(array('PARENT_ID'=>1))->order(array('REGION_ID'=>'asc'))->select();
                $this->assign('prolist',$prolist);
                $this->typeid=$typeid;
                $this->action='addcl';
                $this->display('huiyuanadd');
                break;
            case 'lookset':                     //检测是否已经被注册
                $mobset=I('post.mobset');
                $usval=I('post.usval');
                if($usval == ''){
                    echo json_encode(array('code'=>0,'msg'=>'请输入您的用户名！'));
                    exit;
                }
                $M_user = M('user');
                if($mobset == 1){
                    $wheval['username']=$usval;
                    $wheval['phone']=$usval;
                    $wheval['_logic']='OR';
                    $list=$M_user->field('username,phone')->where($wheval)->find();
                    if($list){
                        echo json_encode(array('code'=>0,'msg'=>'该手机号码已经被使用！请更换！'));
                    }else{
                        echo json_encode(array('code'=>1,'msg'=>'该帐号可以使用！'));
                    }
                }else{
                    $list=$M_user->field('username')->where(array('username'=>$usval))->find();
                    if($list){
                        echo json_encode(array('code'=>0,'msg'=>'该帐号已经被使用！请更换！'));
                    }else{
                        echo json_encode(array('code'=>1,'msg'=>'该帐号可以使用！'));
                    }
                }
                break;
            case 'looksetpho':
                $phoneval=I('post.qrmm');
                if($phoneval == ''){
                    echo json_encode(array('code'=>0,'msg'=>'请输入您的手机号码！'));
                    exit;
                }
                $M_user = M('user');
                $list=$M_user->field('phone')->where(array('phone'=>$phoneval))->find();
                if($list){
                    echo json_encode(array('code'=>0,'msg'=>'该手机号码已经被使用！请更换！'));
                }else{
                    echo json_encode(array('code'=>1,'msg'=>'该手机号码可以使用！'));
                }
                break;
            case 'addcl':
                $usernamers=I('post.usernamers');           //用户名

                $password=I('post.password');               //密码
                $password2=I('post.password2');             //确认密码
                $nickname=I('post.nickname');               //昵称
                $email=I('post.email');                     //邮箱
                $phone=I('post.phone');                     //手机
                $tel=I('post.tel')?I('post.tel'):0;                         //固定电话
                $sex=I('post.sex')?1:0;                     //性别
                $starttime=I('post.starttime')?strtotime(I('post.starttime')):0;             //生日
                $address=I('post.address');                 //补充地址
                $headpic=I('post.headpic');                 //头像

                $provinces=I('post.provinces');             //省
                $city=I('post.city');                       //市
                $county=I('post.county');                   //县

                if($usernamers == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入用户名");history.go(-1);</script>';
                    return;
                }
                if($password == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入密码");history.go(-1);</script>';
                    return;
                }
                if($password != $password2){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("你两次输入的密码不一致！");history.go(-1);</script>';
                    return;
                }
                if($email == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入E-maile");history.go(-1);</script>';
                    return;
                }
                if($phone == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入您的手机号码！");history.go(-1);</script>';
                    return;
                }
                if($provinces == -1){
                    $provinces=0;
                }
                if($city == -1){
                    $city=0;
                }
                if($county == -1){
                    $county=0;
                }
                $uscont=array(
                    'cateid'                    =>                  1,                              //身份(1.普通会员，2.经销商)
                    'code'                      =>                  0,  //foo(),
                    'username'                  =>                  $usernamers,
                    'phone'                     =>                  $phone,
                    'telval'                    =>                  $tel,
                    'nickname'                  =>                  $nickname,
                    'headpic'                   =>                  $headpic,
                    'password'                  =>                  jiamimd5($password2),
                    'sex'                       =>                  $sex,
                    'address'                   =>                  $address,
                    'detailed'                  =>                  1,
                    'addtime'                   =>                  time(),
                    'authority'                 =>                  1,
                    'provinces'                 =>                  $provinces,
                    'city'                      =>                  $city,
                    'county'                    =>                  $county,
                    'email'                     =>                  $email,
                    'shengri'                   =>                  $starttime
                );  
                if(M('user')->add($uscont)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.U('Admin/User/addhuiyuan/action/add/typeid/'.$typeid).'";}else{location.href="'.U('Admin/user/lists/op/'.$typeid).'";}</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("写入失败！请重新提交！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'eite':
                $prolist=M('region')->where(array('PARENT_ID'=>1))->order(array('REGION_ID'=>'asc'))->select();
                $this->assign('prolist',$prolist);
                $id=I('get.id');
                if($id == ''){
                    $typeid=I('post.id');
                }
                if($id){
                    $list=M('user')->where(array('id'=>$id))->find();
                    $this->typeid=$typeid;
                    $this->action='eitecl';
                    $this->id=$id;

                    if($list['provinces'] != 0){
                        $this->assign('citylt',addreslook($list['provinces']));
                    }
                    if($list['city'] != 0){
                        $this->assign('countylst',addreslook($list['city']));
                    }

                    if($list['phone'] == 0){
                        $list['phone']='';
                    }
                    if($list['telval'] == 0){
                        $list['telval']='';
                    }
                    if($list['shengri'] == 0){
                        $list['shengri']='';
                    }


                    $this->assign('list',$list);

                    $this->display('eiteuscont');
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'eitecl':
                $id=I('get.id');
                if($id == ''){
                    $id=I('post.id');
                }
                if($id){
                    $nickname=I('post.nickname');               //昵称
                    $email=I('post.email');                     //邮箱
                    $phone=I('post.phone');                     //手机
                    $tel=I('post.tel')?I('post.tel'):0;                         //固定电话
                    $sex=I('post.sex')?1:0;                     //性别
                    $starttime=I('post.starttime')?strtotime(I('post.starttime')):0;             //生日
                    $address=I('post.address');                 //补充地址
                    $headpic=I('post.headpic');                 //头像

                    $provinces=I('post.provinces');             //省
                    $city=I('post.city');                       //市
                    $county=I('post.county');                   //县

                    if($email == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请输入E-maile");history.go(-1);</script>';
                        return;
                    }
                    if($phone == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请输入您的手机号码！");history.go(-1);</script>';
                        return;
                    }
                    if($provinces == -1){
                        $provinces=0;
                    }
                    if($city == -1){
                        $city=0;
                    }
                    if($county == -1){
                        $county=0;
                    }
                    $uscont=array(
                        
                        'phone'                     =>                  $phone,
                        'telval'                    =>                  $tel,
                        'nickname'                  =>                  $nickname,
                        'headpic'                   =>                  $headpic,
                        'sex'                       =>                  $sex,
                        'address'                   =>                  $address,
                        'provinces'                 =>                  $provinces,
                        'city'                      =>                  $city,
                        'county'                    =>                  $county,
                        'email'                     =>                  $email,
                        'shengri'                   =>                  $starttime
                    ); 
                    if(M('user')->where(array('id'=>$id))->save($uscont)){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！");location.href="'.U('Admin/user/lists/op/'.$typeid).'"</script>';
                    }else{
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！信息没有变动！");location.href="'.U('Admin/user/lists/op/'.$typeid).'"</script>';
                    }
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                break;
            default:
                $M_user = M('user');
                $p_op = 1;
                $user_where['cateid'] = array('eq', $p_op);
                
                $count = $M_user->where($user_where)->count();
                $Page = new \Think\Page($count, 15);
                //定制分页类
                $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $Page->setConfig('prev','上一页');
                $Page->setConfig('next','下一页');
                $Page->setConfig('first','首页');
                $Page->setConfig('last','末页');
                $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
                $show = $Page -> show();
                $users = $M_user->where($user_where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
                $this->lists = $users;
                $this->op = $p_op;
                $this->page = $show;
                $this->display('lists');
                break;
        }
    }
    /*
    *添加经销商
     */
    public function addjxs(){
        $action=I('get.action');
        if($action == ''){
            $action=I('post.action');
        }
        $typeid=I('get.typeid');
        if($typeid == ''){
            $typeid=I('post.typeid')?I('post.typeid'):1;
        }
        switch ($action) {
            case 'add':
                $prolist=M('region')->where(array('PARENT_ID'=>1))->order(array('REGION_ID'=>'asc'))->select();
                $this->assign('prolist',$prolist);
                $this->typeid=$typeid;
                $this->action='addcl';
                $this->codeval=foo();
                $this->display('jingxsadd');
                break;
            case 'addcl':
                $usernamers=I('post.usernamers');           //用户名

                $password=I('post.password');               //密码
                $password2=I('post.password2');             //确认密码
                $nickname=I('post.nickname');               //昵称
                $email=I('post.email');                     //邮箱
                $phone=I('post.phone');                     //手机
                $tel=I('post.tel')?I('post.tel'):0;                         //固定电话
                $sex=I('post.sex')?1:0;                     //性别
                $starttime=I('post.starttime')?strtotime(I('post.starttime')):0;             //生日
                $address=I('post.address');                 //补充地址
                $headpic=I('post.headpic');                 //头像

                $provinces=I('post.provinces');             //省
                $city=I('post.city');                       //市
                $county=I('post.county');                   //县


                $poratename=I('post.poratename');                   //公司名称
                $porateaddress=I('post.porateaddress');                   //公司地址
                $titlepicyyzz=I('post.titlepicyyzz');                   //营业执照
                $count=I('post.count');                   //备注
                $code=I('post.code');                   //渠道码(唯一不可重复)


                if($usernamers == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入用户名");history.go(-1);</script>';
                    return;
                }
                if($password == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入密码");history.go(-1);</script>';
                    return;
                }
                if($password != $password2){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("你两次输入的密码不一致！");history.go(-1);</script>';
                    return;
                }
                if($email == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入E-maile");history.go(-1);</script>';
                    return;
                }
                if($phone == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入您的手机号码！");history.go(-1);</script>';
                    return;
                }

                if($poratename == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入公司名称！");history.go(-1);</script>';
                    return;
                }
                if($porateaddress == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入公司地址！");history.go(-1);</script>';
                    return;
                }
                /*if($titlepicyyzz == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请上传营业执照！");history.go(-1);</script>';
                    return;
                }*/
                if($code == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请二维码参数错误！");history.go(-1);</script>';
                    return;
                }

                if($provinces == -1){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请选择经销商销售区域");history.go(-1);</script>';
                    return;
                }
                if($city == -1){
                    $city=0;
                }
                if($county == -1){
                    $county=0;
                }

                $uscont=array(
                    'cateid'                    =>                  2,                              //身份(1.普通会员，2.经销商)
                    'code'                      =>                  $code,  //foo(),
                    'username'                  =>                  $usernamers,
                    'phone'                     =>                  $phone,
                    'telval'                    =>                  $tel,
                    'nickname'                  =>                  $nickname,
                    'headpic'                   =>                  $headpic,
                    'password'                  =>                  jiamimd5($password2),
                    'sex'                       =>                  $sex,
                    'address'                   =>                  $address,
                    'detailed'                  =>                  1,
                    'addtime'                   =>                  time(),
                    'authority'                 =>                  2,
                    'provinces'                 =>                  $provinces,
                    'city'                      =>                  $city,
                    'county'                    =>                  $county,
                    'email'                     =>                  $email,
                    'shengri'                   =>                  $starttime,

                    'poratename'                =>                  $poratename,
                    'porateaddress'             =>                  $porateaddress,
                    'porateipc'                 =>                  $titlepicyyzz,
                    'count'                     =>                  $count
                );  
                if(M('user')->add($uscont)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.U('Admin/User/addjxs/action/add/typeid/'.$typeid).'";}else{location.href="'.U('Admin/user/lists/op/'.$typeid).'";}</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("写入失败！请重新提交！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'eite':
                $prolist=M('region')->where(array('PARENT_ID'=>1))->order(array('REGION_ID'=>'asc'))->select();
                $this->assign('prolist',$prolist);
                $id=I('get.id');
                if($id == ''){
                    $typeid=I('post.id');
                }
                if($id){
                    $list=M('user')->where(array('id'=>$id))->find();
                    $this->typeid=$typeid;
                    $this->action='eitecl';
                    $this->id=$id;

                    if($list['provinces'] != 0){
                        $this->assign('citylt',addreslook($list['provinces']));
                    }
                    if($list['city'] != 0){
                        $this->assign('countylst',addreslook($list['city']));
                    }

                    if($list['phone'] == 0){
                        $list['phone']='';
                    }
                    if($list['telval'] == 0){
                        $list['telval']='';
                    }
                    if($list['shengri'] == 0){
                        $list['shengri']='';
                    }
                    if($list['poratename'] == '0'){
                        echo 1;
                        $list['poratename']='';
                    }
                    if($list['porateaddress'] == '0'){
                        $list['porateaddress']='';
                    }
                    if($list['porateipc'] == '0'){
                        $list['porateipc']='';
                    }

                    if($list['code'] == '0'){
                        $list['code']=foo();
                    }


                    $this->assign('list',$list);

                    $this->display('eiusjxscont');
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'eitecl':
                $id=I('get.id');
                if($id == ''){
                    $id=I('post.id');
                }
                if($id){
                    $nickname=I('post.nickname');               //昵称
                    $email=I('post.email');                     //邮箱
                    $phone=I('post.phone');                     //手机
                    $tel=I('post.tel')?I('post.tel'):0;                         //固定电话
                    $sex=I('post.sex')?1:0;                     //性别
                    $starttime=I('post.starttime')?strtotime(I('post.starttime')):0;             //生日
                    $address=I('post.address');                 //补充地址
                    $headpic=I('post.headpic');                 //头像

                    $provinces=I('post.provinces');             //省
                    $city=I('post.city');                       //市
                    $county=I('post.county');                   //县


                    $poratename=I('post.poratename');                   //公司名称
                    $porateaddress=I('post.porateaddress');                   //公司地址
                    $titlepicyyzz=I('post.titlepicyyzz');                   //营业执照
                    $count=I('post.count');                   //备注
                    $code=I('post.code');                   //渠道码(唯一不可重复)


                    
                    if($email == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请输入E-maile");history.go(-1);</script>';
                        return;
                    }
                    if($phone == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请输入您的手机号码！");history.go(-1);</script>';
                        return;
                    }

                    if($poratename == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请输入公司名称！");history.go(-1);</script>';
                        return;
                    }
                    if($porateaddress == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请输入公司地址！");history.go(-1);</script>';
                        return;
                    }
                    if($titlepicyyzz == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请上传营业执照！");history.go(-1);</script>';
                        return;
                    }
                    if($code == ''){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请二维码参数错误！");history.go(-1);</script>';
                        return;
                    }

                    if($provinces == -1){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("请选择经销商销售区域");history.go(-1);</script>';
                        return;
                    }
                    if($city == -1){
                        $city=0;
                    }
                    if($county == -1){
                        $county=0;
                    }

                    $uscont=array(
                        'code'                      =>                  $code,  //foo(),
                        'phone'                     =>                  $phone,
                        'telval'                    =>                  $tel,
                        'nickname'                  =>                  $nickname,
                        'headpic'                   =>                  $headpic,
                        'sex'                       =>                  $sex,
                        'address'                   =>                  $address,
                        'detailed'                  =>                  1,
                        'provinces'                 =>                  $provinces,
                        'city'                      =>                  $city,
                        'county'                    =>                  $county,
                        'email'                     =>                  $email,
                        'shengri'                   =>                  $starttime,

                        'poratename'                =>                  $poratename,
                        'porateaddress'             =>                  $porateaddress,
                        'porateipc'                 =>                  $titlepicyyzz,
                        'count'                     =>                  $count
                    );
                    if(M('user')->where(array('id'=>$id))->save($uscont)){
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！");location.href="'.U('Admin/user/lists/op/'.$typeid).'"</script>';
                    }else{
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                        echo '<script>alert("操作完成！信息没有变动！");location.href="'.U('Admin/user/lists/op/'.$typeid).'"</script>';
                    }
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                break;
            default:
                $M_user = M('user');
                $p_op = 2;
                $user_where['cateid'] = array('eq', $p_op);
                
                $count = $M_user->where($user_where)->count();
                $Page = new \Think\Page($count, 15);
                //定制分页类
                $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $Page->setConfig('prev','上一页');
                $Page->setConfig('next','下一页');
                $Page->setConfig('first','首页');
                $Page->setConfig('last','末页');
                $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
                $show = $Page -> show();
                $users = $M_user->where($user_where)->order('id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
                $this->lists = $users;
                $this->op = $p_op;
                $this->page = $show;
                $this->display('lists');
                break;
        }
    }


    /*
    *经销商消息
     */
    public function usnotice(){
        $action=I('get.action')?I('get.action'):'list';
        switch ($action) {
            case 'addfbxx':
                $fbid=I('get.fbid')?I('get.fbid'):0;
                $this->assign('fbid',$fbid);
                $this->assign('action','addcl');
                $this->display('xiaoxi');
                break;
            case 'addcl':
                $jsrid=I('post.jsrid')?I('post.jsrid'):0;
                $uszhanh=I('post.uszhanh');
                $title=I('post.title');
                $smalltext=I('post.smalltext');
                if($title == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入消息标题");history.go(-1);</script>';
                    return;
                }
                if($smalltext == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入消息内容");history.go(-1);</script>';
                    return;
                }
                if($jsrid == 0){
                    if($uszhanh != ''){
                        $usidv=M('user')->field('id,username')->where(array('username'=>$uszhanh))->find();
                        if($usidv){
                            $jsrid=$usidv['id'];
                        }else{
                            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                            echo '<script>alert("您输入的帐号不存在！");history.go(-1);</script>';
                            return;
                        }
                    }
                }
                $addx=array(
                    'uid'           =>                  $jsrid,
                    'title'         =>                  $title,
                    'contval'       =>                  $smalltext,
                    'time'          =>                  time(),
                    'setval'        =>                  0,
                    'fsid'          =>                  $_SESSION['r_id']?$_SESSION['r_id']:0
                );
                if(M('usxiaoxi')->add($addx)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>if(confirm("添加成功！是否继续添加？")){location.href="'.U('Admin/User/usnotice/action/addfbxx/fbid/'.$jsrid).'";}else{location.href="'.U('Admin/User/usnotice/action/list/id/'.$jsrid).'";}</script>';
                    return;
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("写入失败！请重新提交！");history.go(-1);</script>';
                    return;
                }
                break;
            case 'eite':
                $id=I('get.id')?I('get.id'):0;
                if($id == 0){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("参数错误！");history.go(-1);</script>';
                    return;
                }
                $xxcon=M('usxiaoxi')->where(array('id'=>$id))->find();
                $this->assign('id',$id);
                $this->assign('fbid',$xxcon['uid']);
                $this->assign('action','eitecl');
                $this->assign('list',$xxcon);
                $this->display('xiaoxi');
                break;
            case 'eitecl':
                $id=I('get.id')?I('get.id'):0;
                $title=I('post.title');
                $smalltext=I('post.smalltext');
                $jsrid=I('post.jsrid')?I('post.jsrid'):0;
                if($title == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入消息标题");history.go(-1);</script>';
                    return;
                }
                if($smalltext == ''){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("请输入消息内容");history.go(-1);</script>';
                    return;
                }
                
                $addx=array(
                    'title'         =>                  $title,
                    'contval'       =>                  $smalltext,
                    'time'          =>                  time(),
                    'setval'        =>                  0,
                    'fsid'          =>                  $_SESSION['r_id']
                );
                if(M('usxiaoxi')->where(array('id'=>$id))->save($addx)){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("操作完成！");location.href="'.U('Admin/User/usnotice/action/list/id/'.$jsrid).'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("操作完成！信息没有变动！");location.href="'.U('Admin/User/usnotice/action/list/id/'.$jsrid).'"</script>';
                }
                break;
            case 'delt':
                $id=I('get.id')?I('get.id'):0;
                $xxcon=M('usxiaoxi')->field('id,uid')->where(array('id'=>$id))->find();
                if(M('usxiaoxi')->where(array('id'=>$id))->delete()){
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("操作完成！");location.href="'.U('Admin/User/usnotice/action/list/id/'.$xxcon['uid']).'"</script>';
                }else{
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo '<script>alert("删除失败");location.href="'.U('Admin/User/usnotice/action/list/id/'.$xxcon['uid']).'"</script>';
                }
                break;
            default:
                $M_xiaoxi = M('usxiaoxi');
                $id=I('get.id');
                if($id){
                    $tj['x.uid']=$id;
                }
                $count = $M_xiaoxi
                            ->alias('x')
                            ->field('x.id as myid,x.*,u.*')
                            ->join('__USER__ u ON u.id=x.uid','LEFT')
                            ->where($tj)
                            ->count();
                $Page = new \Think\Page($count, 10);
                //定制分页类
                $Page->setConfig('header','<span class="sabrosus">共 %TOTAL_ROW% 条记录</span>'); //显示内容
                $Page->setConfig('prev','上一页');
                $Page->setConfig('next','下一页');
                $Page->setConfig('first','首页');
                $Page->setConfig('last','末页');
                $Page->setConfig('theme'," %HEADER% %FIRST% %UP_PAGE%  %LINK_PAGE% %DOWN_PAGE% %END%");
                $show = $Page -> show();
                
                $orders_list = $M_xiaoxi
                                    ->alias('x')
                                    ->field('x.id as myid,x.*,u.*')
                                    ->join('__USER__ u ON u.id=x.uid','LEFT')
                                    ->where($tj)
                                    ->limit($Page->firstRow.','.$Page->listRows)
                                    ->order('x.id DESC')
                                    ->select();

                $this->orders_list = $orders_list;
                $this->page = $show;
                if($id){
                    $this->fbid=$id;
                }else{
                    $this->fbid=0;
                }
                
                $this->display();
                break;
        }
    }




}