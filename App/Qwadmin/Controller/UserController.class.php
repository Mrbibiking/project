<?php
namespace Qwadmin\Controller;

class UserController extends ComController
{
    private $action='0';//这里声明

    public function adminindex()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        //  $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '';
        /*if ($order == 'asc') {
            $order = "{$prefix}member.t asc";
         } elseif (($order == 'desc')) {
             $order = "{$prefix}member.t desc";
         } else {
             $order = "{$prefix}member.uid asc";
         }*/
        if ($keyword <> '') {//查询功能
            if ($field == 'name') {
                $where = "personnel.name LIKE '%$keyword%'";
            }
            if ($field == 'fullname') {
                $where = "personnel.fullname LIKE '%$keyword%'";
            }
            if ($field == 'mobile') {
                $where = "personnelcontact.mobile LIKE '%$keyword%'";
            }
            if ($field == 'idnumber') {
                $where = "personnel.idnumber LIKE '%$keyword%'";
            }

            if($field == 'classno'){
                $where = 'examinationandcertificate.classno ='.$keyword;
            }

            if($field == 'institution'){
                $where = "examinationandcertificate.traininginstitution LIKE '%$keyword%'";
            }
        }
        $loginuser=M('member');
        $wherelogin="member.uid=".session('uid');
        $user = M('personnel');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量

        $count = $user->join('INNER JOIN personnelcontact ON personnel.id = personnelcontact.personnelid')
            ->join('INNER JOIN examinationandcertificate ON personnel.examinationandcertificateid = examinationandcertificate.id')
            ->where($where)
            ->count();

        $list = $user->join('INNER JOIN personnelcontact ON personnel.id = personnelcontact.personnelid')
            ->join('INNER JOIN examinationandcertificate ON personnel.examinationandcertificateid = examinationandcertificate.id')
            ->where($where)
            ->limit($offset . ',' . $pagesize)
            ->select();

        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        // $group = M('auth_group')->field('id,title')->select();
        //  $this->assign('group', $group);
        $this->display();
    }

    public function manage()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        //  $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
//        $institutionid = session('insitutionid');
//        if ($institutionid == 1)
//            $where = '';
//        else
//            $where = 'examinationandcertificate.institutionid='.$institutionid;
//        session('institutionid', null); //清空session
        if($keyword <> '')
            {
            if ($field == 'name') {
                $where = "basic_info.name LIKE '%$keyword%' and $where";
            }
            if ($field == 'fullname') {
                $where = "basic_info.fullname LIKE '%$keyword%' and $where";
            }
            if ($field == 'mobile') {
                $where = "personnelcontact.mobile LIKE '%$keyword%' and $where";
            }
            if ($field == 'email') {
                $where = "personnelcontact.email LIKE '%$keyword%' and $where";
            }
            if($field == 'classno'){
                $where = "examinationandcertificate.classno=$keyword and $where";
            }
            if($field == 'institution'){
                $where = "examinationandcertificate.traininginstitution LIKE '%$keyword%' and $where";
            }
        }

        $user = M('basic_info');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量

        $count = $user->join('INNER JOIN contact_info ON basic_info.id = contact_info.person_number')
            ->join('INNER JOIN examinationandcertificate ON basic_info.examinationandcertificateid = examinationandcertificate.id')
            ->where($where)
            ->count();

        $list = $user->join('INNER JOIN contact_info ON basic_info.id = contact_info.person_number')
            ->join('INNER JOIN examinationandcertificate ON basic_info.examinationandcertificateid = examinationandcertificate.id')
            ->where($where)
            ->limit($offset . ',' . $pagesize)
            ->select();

        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
//        $this->assign('role', $role['type']);
        $this->assign('list', $list);
        $this->assign('page', $page);
        // $group = M('auth_group')->field('id,title')->select();
        //  $this->assign('group', $group);
        $this->display();
    }

    public function index()
    {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
     //  $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '';
       /*if ($order == 'asc') {
           $order = "{$prefix}member.t asc";
        } elseif (($order == 'desc')) {
            $order = "{$prefix}member.t desc";
        } else {
            $order = "{$prefix}member.uid asc";
        }*/
        if ($keyword <> '') {
            if ($field == 'name') {
                $where = "personnel.name LIKE '%$keyword%'";
            }
            if ($field == 'fullname') {
                $where = "personnel.fullname LIKE '%$keyword%'";
            }
            if ($field == 'mobile') {
                $where = "personnel.mobile LIKE '%$keyword%'";
            }
            if ($field == 'email') {
                $where = "personnel.email LIKE '%$keyword%'";
            }
        }
        $loginuser=M('member');
        $wherelogin="member.uid=".session('uid');
        $role=$loginuser->field("auth_group.type")
            ->join("auth_group_access ON member.uid=auth_group_access.uid")
            ->join("auth_group ON auth_group_access.group_id=auth_group.id")
            ->where($wherelogin)
            ->find();
        $user = M('personnel');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $count = $user->field("personnel.id as personnelid,personnelcontact.id as personnelcontactid,personnel.*,personnelcontact.mobile,personnelcontact.email")
            ->join("personnelcontact ON personnel.id = personnelcontact.personnelid")
            ->where($where)
            ->count();

        $list = $user->field("personnel.id as personnelid,personnelcontact.id as personnelcontactid,personnel.*,personnelcontact.*")
            ->join("personnelcontact ON personnel.id = personnelcontact.personnelid")
            ->where($where)
            ->limit($offset . ',' . $pagesize)
            ->select();

        //$user->getLastSql();

        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('role', $role['type']);
        $this->assign('list', $list);
        $this->assign('page', $page);
       // $group = M('auth_group')->field('id,title')->select();
      //  $this->assign('group', $group);
        $this->display();
    }

    public function del()
    {
        $uids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        if (!$uids) {
            $this->error('参数错误！');
        }
        if (is_array($uids)) {
            foreach ($uids as $k => $v) {
                $uids[$k] = intval($v);
            }
            if (!$uids) {
                $this->error('参数错误！');
                $uids = implode(',', $uids);
            }
            $map['id'] = array('in', $uids);
        }
        else
        $map['id'] = array('eq', $uids);
        if (M('basic_info')->where($map)->delete()) {
           // M('auth_group_access')->where($map)->delete();
            addlog('删除会员UID：' . $uids);
            $this->success('恭喜，用户删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function update_base_info($ajax = ''){
        //$institutionid = session('insitutionid');
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        $basic_info['realname']= isset($_POST['realname']) ? trim($_POST['realname']) : '';
        $basic_info['name_spelling']= isset($_POST['name_spelling']) ? trim($_POST['name_spelling']) : '';
        $basic_info['gender'] = isset($_POST['gender']) ? pack('C',$_POST['gender']) : 1;
        $basic_info['id_type_number'] = isset($_POST['id_type_number']) ? intval($_POST['id_type_number']) : 1;
        $basic_info['id_number']= isset($_POST['id_number']) ? trim($_POST['id_number']) : '';
        $basic_info['birthday'] = isset($_POST['birthday']) ? strtotime($_POST['birthday']) : 0;
        $basic_info['place_of_origin']= isset($_POST['place_of_origin']) ? trim($_POST['place_of_origin']) : '';
        $basic_info['country_or_region']= isset($_POST['country_or_region']) ? trim($_POST['country_or_region']) : '';
        $basic_info['nation']= isset($_POST['nation']) ? trim($_POST['nation']) : '';
        $basic_info['political_status']= isset($_POST['political_status']) ? trim($_POST['political_status']) : '';
        $basic_info['marital_status']= isset($_POST['marital_status']) ? pack('C',$_POST['marital_status']) : '0';
        $basic_info['highest_education']= isset($_POST['highest_education']) ? trim($_POST['highest_education']) : '';
        $basic_info['professional']= isset($_POST['professional']) ? trim($_POST['professional']) : '';
        $basic_info['present_work_unit']= isset($_POST['present_work_unit']) ? trim($_POST['present_work_unit']) : '';
        $basic_info['lasteditdate']=date('Y-m-d') ;

//        $basic_info['age'] = isset($_POST['age']) ? intval($_POST['age']) : 0;//这一项通过计算得到，其实不用填
//        $basic_info['industry']= isset($_POST['industry']) ? trim($_POST['industry']) : '';
//        $basic_info['photo']= isset($_POST['photo']) ? trim($_POST['photo']) : '';


//        $usercontact['unitaddress']= isset($_POST['unitaddress']) ? trim($_POST['unitaddress']) : '';
//        $usercontact['unitzipcode']= isset($_POST['unitzipcode']) ? trim($_POST['unitzipcode']) : '';
//        $usercontact['unitname']= isset($_POST['unitname']) ? trim($_POST['unitname']) : '';
//        $usercontact['unitfixedphone']= isset($_POST['unitfixedphone']) ? trim($_POST['unitfixedphone']) : '';
//        $usercontact['unitemail']= isset($_POST['unitemail']) ? trim($_POST['unitemail']) : '';
//        $usercontact['unitfax']= isset($_POST['unitfax']) ? trim($_POST['unitfax']) : '';
//        $usercontact['unitwebsite']= isset($_POST['unitwebsite']) ? trim($_POST['unitwebsite']) : '';

        $contact_info['fixed_phone']= isset($_POST['fixed_phone']) ? trim($_POST['fixed_phone']) : '';
        $contact_info['mobile_phone']= isset($_POST['mobile_phone']) ? trim($_POST['mobile_phone']) : '';
        $contact_info['email']= isset($_POST['email']) ? trim($_POST['email']) : '';
        $contact_info['fax']= isset($_POST['fax']) ? trim($_POST['fax']) : '';
        $contact_info['contact_address']= isset($_POST['contact_address']) ? trim($_POST['contact_address']) : '';
        $contact_info['zip_code']= isset($_POST['zip_code']) ? trim($_POST['zip_code']) : '';
        $contact_info['account_type_number']= isset($_POST['account_type_number']) ? trim($_POST['account_type_number']) : '';
        $contact_info['account']= isset($_POST['account']) ? trim($_POST['account']) : '';

        $contact_info['lasteditdate']=date('Y-m-d') ;

        if (!$uid) {
            $basic_info['createdate']= date('Y-m-d') ;
            M('basic_info')->data($basic_info)->add();
            $uid = M('basic_info')->getLastInsID();
            addlog('创建会员，会员UID：' . $uid);
        }
        else {
            addlog('编辑会员信息，会员UID：' . $uid);
//            dump($user);
//            dump($_POST);
//            die;
            M('basic_info')->data($basic_info)->where("id=$uid")->save();
        }

        if (!$uid) {

        }
        else {
            $count=M('contact_info')->field("contact_info.*")
                ->where('person_number ='.$uid)
                ->count();
            if($count==0)
            {
                $contact_info['person_number']=$uid;
                $contact_info['createdate']=date('Y-m-d') ;
                M('contact_info')->data($contact_info)->add();
            }
            else
                M('contact_info')->data($contact_info)->where("person_number =".$uid)->save();

            $examinationandcertificateid = M('basic_info')
                                            ->where('id='.$uid)
                                            ->getField('examinationandcertificateid');
            if($examinationandcertificateid == null){   //根据当前登录的机构给新创建的参培人员添加培训机构的信息,添加institutionid、traininginstitution
//                $examinationandcertificate['institutionid'] = $institutionid;
                $examinationandcertificate['traininginstitution'] = M('member')
                                                                    //->where('uid='.$institutionid)
                                                                    ->getField('trainins');
                M('examinationandcertificate')->data($examinationandcertificate)->add();
                $examinationandcertificateid = M('examinationandcertificate')->getLastInsID();
                M('basic_info')->where('id='.$uid)->setField('examinationandcertificateid',$examinationandcertificateid);
            }
        }
        $this->redirect('edit', array('uid'=>$uid,'flag'=>1));
    }

    public function update_work_exp($ajax = ''){
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        $form=I('post.');
        $count=$form['countwork'];
        $personnelwork=M('work_exp');
        $countwork=$personnelwork->field("work_exp.*")
            ->where("person_number=".$uid)
            ->count();
        if($countwork!=0) {
            $personnelwork->field("work_exp.*")
                ->where("person_number=" . $uid)
                ->delete();
        }
        for($i=0;$i<$count;$i++){
            $data['person_number']=$uid;
            $data["work_unit"]=$form["work_unit".$i];
            $data["unit_address"]=$form["unit_address".$i];
            $data["post"]=$form["post".$i];
            $data["duty"]=$form["duty".$i];
            $data['work_start_time']=$form["work_start_time".$i];
            $data["work_end_time"]=$form["work_end_time".$i];
            $data["witness"]=$form["witness".$i];
            $data["security_related"] = pack('C',$form["security_related".$i]);
            $data["createdate"]=date('Y-m-d');
            $data["lasteditdate"]=date('Y-m-d');

            if (!$uid) {
            }
            else {
                M('work_exp')->data($data)->add();
            }
        }
        $user['workingyears']=$form['workingyears'];
        $user['securityworkingyears']=$form['securityworkingyears'];
        M('basic_info')->data($user)->where("id = $uid")->save();

        $this->redirect('edit', array('uid'=>$uid,'flag'=>2));
    }

    public function update_education_exp($ajax = ''){
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        $form=I('post.');
        $count=$form['count'];
        $personneleducation=M('education_exp');
        $counteducation=$personneleducation->field("education_exp.*")
            ->where("person_number =".$uid)
            ->count();
        if($counteducation!=0) {
            $personneleducation->field("education_exp.*")
                ->where("person_number =" . $uid)
                ->delete();
        }
//        dump($form);
//        dump($count);
//        die;
        for($i=0;$i<$count;$i++){
            $data['person_number']=$uid;
            $data['degree']=$form["degree".$i];
            $data["graduation_school"]=$form["graduation_school".$i];
            $data["major"]=$form["major".$i];
            $data["edu_start_time"]=$form["edu_start_time".$i];
            $data["edu_end_time"]=$form["edu_end_time".$i];
            $data["createdate"]=date('Y-m-d');
            $data["lasteditdate"]=date('Y-m-d');
            if (!$uid) {
            }
            else {
                M('education_exp')->data($data)->add();
            }
        }
      //  $this->display('form/#form3');
        $this->redirect('edit', array('uid'=>$uid,'flag'=>3));
    }

    public function updatecertificate($ajax = ''){
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
//        $usercertificate['institutionid']= isset($_POST['institutionid']) ? intval($_POST['institutionid']) : '';
        $usercertificate['classid']= isset($_POST['classid']) ? intval($_POST['classid']) : '';
        $usercertificate['traininginstitution']= isset($_POST['institutionname']) ? trim($_POST['institutionname']) : '';
        $usercertificate['classno']= isset($_POST['classname']) ? trim($_POST['classname']) : '';
        $usercertificate['obtainthecertificate']= isset($_POST['obtainthecertificate']) ? pack('C',$_POST['obtainthecertificate']) : '';
        $usercertificate['certificateno']= isset($_POST['certificateno']) ? trim($_POST['certificateno']) : '';
        $usercertificate['certificatetype']= isset($_POST['certificatetype']) ? trim($_POST['certificatetype']) : '';
        $usercertificate['certificatedate'] = isset($_POST['certificatedate']) ? strtotime($_POST['certificatedate']) : '';
        $usercertificate['certificatevaliditydate']= isset($_POST['certificatevaliditydate']) ? strtotime($_POST['certificatevaliditydate']) : '';
        $usercertificate['maintaindate'] = isset($_POST['maintaindate']) ? strtotime($_POST['maintaindate']) : '';
        $usercertificate['maintainvaliditydate'] = isset($_POST['maintainvaliditydate']) ? strtotime($_POST['maintainvaliditydate']) : '';
        $usercertificate['trainingtimestart']= isset($_POST['trainingtimestart']) ? strtotime($_POST['trainingtimestart']) : '';
        $usercertificate['trainingtimeend']= isset($_POST['trainingtimeend']) ? strtotime($_POST['trainingtimeend']) : '';
        $usercertificate['makeupexam']= isset($_POST['makeupexam']) ? intval($_POST['makeupexam']) : '';
        //$usercertificate['maintainvaliditystartdate']= isset($_POST['maintainvaliditystartdate']) ? ($_POST['maintainvaliditystartdate']) : '';
        $usercertificate['lasteditdate']=date('Y-m-d') ;

        $usercertificate['institutionid'] = M('Member')->where("trainins = '{$usercertificate['traininginstitution']}'")->getField('uid');
        if (!$uid) {

        }
        else {
            $user=M('basic_info')->where("id=".$uid)->find();
            if(intval($user['examinationandcertificateid'])==0){
                $usercertificate['createdate']=date('Y-m-d');
                $certificateid=M('examinationandcertificate')->data($usercertificate)->add();
                M('basic_info')->where("id=".$uid)->setField('examinationandcertificateid',$certificateid);
            }
            else {
                M('examinationandcertificate')->data($usercertificate)->where("id=" . intval($user['examinationandcertificateid']))->save();
            }
        }
        $this->redirect('edit', array('uid'=>$uid,'flag'=>4));
    }

    public function update_cert_info($ajax = ''){
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        $form=I('post.');
        $count=$form['counttrain'];
        $personneltraining=M('cert_info');
        $counttraining=$personneltraining->field("cert_info.*")
            ->where("person_number =".$uid)
            ->count();
        if($counttraining!=0) {
            $personneltraining->field("cert_info.*")
                ->where("person_number =" . $uid)
                ->delete();
        }
        for($i=0;$i<$count;$i++){
            $data['person_number']=$uid;
            $data['cert_type_number']=$form["cert_type_number".$i];
            $data['cert_name']=$form["cert_name".$i];
            $data["cert_au"]=$form["cert_au".$i];
            $data["cert_number"]=$form["cert_number".$i];
            $data["cert_start_time"]=$form["cert_start_time".$i];
            $data["cert_end_time"]=$form["cert_end_time".$i];
            $data["createdate"]=date('Y-m-d');
            $data["lasteditdate"]=date('Y-m-d');
            if (!$uid) {
            }
            else {
                M('cert_info')->data($data)->add();
            }
        }
        $this->redirect('edit', array('uid'=>$uid,'flag'=>5));
    }

    public function updateregistration($ajax = ''){
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        $userregistration['registrationtype']= isset($_POST['registrationtype']) ? trim($_POST['registrationtype']) : '';
        $userregistration['ep_name']= isset($_POST['ep_name']) ? trim($_POST['ep_name']) : '';
        $userregistration['ep_title']= isset($_POST['ep_title']) ? trim($_POST['ep_title']) : '';
        $userregistration['ep_certtype'] = isset($_POST['ep_certtype']) ? trim($_POST['ep_certtype']) : '';
        $userregistration['ep_certnum']= isset($_POST['ep_certnum']) ? trim($_POST['ep_certnum']) : '';
        $userregistration['ep_unit'] = isset($_POST['ep_unit']) ? trim($_POST['ep_unit']) : 1;
        $userregistration['ep_address'] = isset($_POST['ep_address']) ? trim($_POST['ep_address']) : 0;
        $userregistration['ep_zipcode']= isset($_POST['ep_zipcode']) ? trim($_POST['ep_zipcode']) : '';
        $userregistration['ep_phone']= isset($_POST['ep_phone']) ? trim($_POST['ep_phone']) : '';
        $userregistration['ep_fax']= isset($_POST['ep_fax']) ? trim($_POST['ep_fax']) : '';
        $userregistration['ep_email']= isset($_POST['ep_email']) ? trim($_POST['ep_email']) : '';
        $userregistration['ep_other']= isset($_POST['ep_other']) ? trim($_POST['ep_other']) : '';
        $userregistration['uv_istrue']= isset($_POST['uv_istrue']) ? pack('C',trim($_POST['uv_istrue'])) : '';
        $userregistration['uv_other']= isset($_POST['uv_other']) ? trim($_POST['uv_other']) : '';
        $userregistration['lasteditdate']=date('Y-m-d') ;
        $tongzai = $_POST['tongzai'];
        $ep_bumen = $_POST['ep_bumen'];
        $dirleadorother = $_POST['dirleadorother'];//复选框选直接领导，此值0。复选框选其他，此值为1。
        //直接领导和其他不能同时选择，**其他;||**直接领导;||没有**（**为部门名称）
        if(isset($tongzai))
            $userregistration['ep_bumen'] = $ep_bumen;
        if($dirleadorother == '直接领导'){
            $userregistration['dirleadorother'] = 0;
        }else{
            $userregistration['dirleadorother'] = 1;
        }

//        if(isset($tongzai) and $tongzai == '同在'){
//            $ep_relationship=$ep_bumen.';';
//        }
//        if(isset($dirLead) and $dirLead == '直接领导'){
//            $ep_relationship=$ep_relationship.$dirlead.';';
//        }
//        else
//            $ep_relationship=$ep_relationship.';';
//        if(isset($other)){
//            $ep_relationship=$ep_relationship.$other.';';
//        }
//        else{
//
//        }
        if (!$uid) {

        }
        else {
            $examregistration=M('examregistration');
            $count=$examregistration->field("examregistration.*")
                ->where("personnelid=".$uid)
                ->count();
            if($count==0)
            {
                $userregistration['registrationdate']= date('Y-m-d');
                $userregistration['createdate']=date('Y-m-d');
                $userregistration['personnelid']=$uid;
                M('examregistration')->data($userregistration)->add();
            }
            else
                M('examregistration')->data($userregistration)->where("personnelid=".$uid)->save();
        }
        $this->redirect('edit', array('uid'=>$uid,'flag'=>6));

    }

    public function updatephoto($ajax = ''){
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     100*1024*1024*1024 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg', 'bmp');// 设置附件上传类型
        $upload->rootPath  =     'Public/qwadmin/Uploads/Photo/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        $_FILES['fileuserphoto']['filetype']=2;
        $_FILES['fileidphotofile']['filetype']=3;
        $_FILES['fileprofessionfile']['filetype']=4;
        $_FILES['filecispphoto']['filetype']=5;

        $_FILES['fileuserphoto']['filename']=$_POST['userphoto'];
        $_FILES['fileidphotofile']['filename']=$_POST['idphoto'];
        $_FILES['fileprofessionfile']['filename']=$_POST['professionphoto'];
        $_FILES['filecispphoto']['filename']=$_POST['cispphoto'];

        $_FILES['fileuserphoto']['filedescription']=$_POST['userphotodepict'];
        $_FILES['fileidphotofile']['filedescription']=$_POST['idphotodepict'];
        $_FILES['fileprofessionfile']['filedescription']=$_POST['professiondepict'];
        $_FILES['filecispphoto']['filedescription']=$_POST['cispphotodepict'];

        // 上传文件
        $info   =   $upload->upload();
        if(!$info&&$_POST['filesavename']=='') {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else {// 上传成功
            //  var_dump($info);
            if ($this->AddFile($info, $uid))//写入数据库
            {
                $this->redirect('edit', array('uid' => $uid, 'flag' => 7));
            } else {
                $this->error('写入数据库失败');
            }
        }
    }

    private function AddFile($fileinfo,$uid){

        foreach ($fileinfo as $info) {
            $file = M('file');
            $data['lasteditdate'] = date("Y-m-d H:m:s");
            $data['filename'] = $info['filename'];
            $data['filedescription'] = $info['filedescription'];
            $data['filepath'] = $info['savepath'];
            $data['filesavename'] = $info['savename'];
            $data['filetype'] = $info['filetype'];
            $data['personnelid'] = $uid;
            $count = $file->where('personnelid='.$data['personnelid'].' and filetype='.$data['filetype'])
                ->count();
            if ($count == 0) {
                $data['createdate'] = date('Y-m-d');
                if(M('file')->data($data)->add());
                else return false;
            }
            else {
                $oldfilesavename = $file->where('personnelid='.$data['personnelid'].' and filetype='.$data['filetype'])->getField('filesavename');
                $oldfilepath = $file->where('personnelid='.$data['personnelid'].' and filetype='.$data['filetype'])->getField('filepath');
                $fileurl = './Public/qwadmin/Uploads/Photo/'.$oldfilepath.$oldfilesavename;
                unlink($fileurl);//删除之前上传的旧文件
                $folder = './Public/qwadmin/Uploads/Photo/'.$oldfilepath;
                if(count(scandir($folder))==2){//目录为空,=2是因为.和..存在
                    rmdir($folder);//如果删除旧文件之后目录为空则删除目录
                }
                M('file')->data($data)->where('personnelid='.$data['personnelid'].' and filetype='.$data['filetype'])->save();
            }
        }
        return true;
    }

    public function add()
    {
       // $usergroup = M('auth_group')->field('id,title')->select();
      //  $this->assign('usergroup', $usergroup);

        $this->institutionselect();
        $this->classselect();
        $this->display('form');
    }

    public function edit()
    {
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        $flag = isset($_GET['flag']) ? intval($_GET['flag']) : false;
        if ($uid) {

            // 把查询条件传入查询方法
            $basic_info = M('basic_info')->field("basic_info.*")
                ->where("basic_info.id = $uid")
                ->find();
            $id_type = M('id_type')->getField('id,id_type');

            $contact_info = M('contact_info')->field("contact_info.*")
                ->where("person_number = $uid")
                ->find();
            $account_type = M('account_type')->getField('id, account_type');

            $work_exp_tmp = M('work_exp')->field("work_exp.*")
                ->where("person_number = $uid")
                ->order("id asc")
                ->select();

            $usereducationinfo=M('education_exp')->field("education_exp.*")
                ->where("person_number=$uid")
                ->order("id asc")
                ->select();
            $usercertificateinfo=M('examinationandcertificate')
                ->where("id=".intval($basic_info['examinationandcertificateid']))
                ->find();

            $cert_info_tmp =M('cert_info')->field("cert_info.*")
                ->where("person_number = $uid")
                ->order("id asc")
                ->select();
//            $cert_type = M('cert_type')->getField('id, cert_type');

            $userregistrationinfo=M('examregistration')->field("examregistration.*")
                ->where("personnelid=$uid")
                ->find();
            $userphoto=M('file')->field("file.*")
                ->where("personnelid=$uid and filetype='2'")
                ->find();
            $idphoto=M('file')->field("file.*")
                ->where("personnelid=$uid and filetype='3'")
                ->find();
            $professionphoto=M('file')->field("file.*")
                ->where("personnelid=$uid and filetype='4'")
                ->find();
            $cispphoto=M('file')->field("file.*")
                ->where("personnelid=$uid and filetype='5'")
                ->find();

            $educationlen=sizeof($usereducationinfo);
            $educationstr="";
            for($i=0;$i<$educationlen;$i++){
                $educationstr = $educationstr."<tr style=\"width:800px\">";
                $str="" ;
                $str=$str."<input type=\"hidden\" name=\"no\" value=\"1\"/><td style=\"width: 10%\"><input type=\"text\" class=\"form-control\" name=\"degree".$i."\" value=\"".($usereducationinfo[$i]['degree'])."\"/></td>";
                $str=$str."<td style=\"width: 20%\"><input type=\"text\" class=\"form-control\" name=\"graduation_school".$i."\" value=\"".($usereducationinfo[$i]['graduation_school'])."\"/></td>";
                $str=$str."<td style=\"width: 20%\"><input type=\"text\" class=\"form-control\" name=\"major".$i."\" value=\"".($usereducationinfo[$i]['major'])."\"/></td>";
                $str=$str."<td style=\"width: 10%\"><input  class=\"form-control\"  id=\"edu_start_time".$i."\" name=\"edu_start_time".$i."\" type=\"text\" class=\"iput1\" value=\"".($usereducationinfo[$i]['edu_start_time'])."\" readonly=\"readonly\"  onFocus=\"WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:$('#edu_start_time".$i."').val()})\"/></td>";
                $str=$str."<td style=\"width: 10%\"><input  class=\"form-control\" id=\"edu_end_time".$i."\"  name=\"edu_end_time".$i."\"  type=\"text\" class=\"iput1\" value=\"".($usereducationinfo[$i]['edu_end_time'])."\" readonly=\"readonly\"  onFocus=\"WdatePicker({dateFmt:'yyyy-MM-dd',minDate:$('#edu_end_time".$i."').val()})\"/> </td>";
                $str=$str."<td style=\"width: 10%\"><input type=\"button\" class=\"btn btn-primary btn-sm\" onclick=\"deltr3(this)\" value=\"删行\"></td>";
                $educationstr=$educationstr.$str;
                $educationstr=$educationstr."</tr>";
            }
            if($educationlen!=0)
            $educationstr=$educationstr."<input type=\"hidden\" name=\"count\" id=\"count\" value=\"".$educationlen."\" >";

            $trainginglen=sizeof($cert_info_tmp);
            $cert_info = "";
            for($i=0;$i<$trainginglen;$i++){
                $cert_info = $cert_info."<tr style=\"width:800px\">";
                $str1="" ;
                $str1=$str1."<input type=\"hidden\" name=\"no\" value=\"1\"/><td><input type=\"text\" class=\"form-control\"  readonly=\"readonly\"  onFocus=\"WdatePicker({dateFmt:'yyyy-MM-dd'})\"  name=\"cert_start_time".$i."\" value=\"".($cert_info_tmp[$i]['cert_start_time'])."\"/></td>";
                $str1=$str1."<input type=\"hidden\" name=\"no\" value=\"1\"/><td><input type=\"text\" class=\"form-control\"  readonly=\"readonly\"  onFocus=\"WdatePicker({dateFmt:'yyyy-MM-dd'})\"  name=\"cert_end_time".$i."\" value=\"".($cert_info_tmp[$i]['cert_end_time'])."\"/></td>";
                $str1=$str1."<td><select class=\"txt sel3 valid\" data-required id=\"cert_type_number".$i."\" name=\"cert_type_number".$i."\">";
                if($cert_info_tmp[$i]['cert_type_number'] == 0) {
                    $str1 = $str1 . "<option value= 0 selected >测试证件类型1</option>";
                    $str1 = $str1 . "<option value= 1 >测试证件类型2</option>";
                } else {
                    $str1 = $str1 . "<option value= 0 >测试证件类型1</option>";
                    $str1 = $str1 . "<option value= 1 selected >测试证件类型2</option>";
                }
                $str1=$str1."</select></td>";
                $str1=$str1."<td><input  class=\"form-control\" name=\"cert_name".$i."\" type=\"text\" class=\"iput1\" value=\"".($cert_info_tmp[$i]['cert_name'])."\" /></td>";
                $str1=$str1."<td><input type=\"text\" class=\"form-control\" name=\"cert_number".$i."\" value=\"".($cert_info_tmp[$i]['cert_number'])."\"/></td>";
                $str1=$str1."<td><input type=\"text\" class=\"form-control\" name=\"cert_au".$i."\" value=\"".($cert_info_tmp[$i]['cert_au'])."\"/></td>";
                $str1=$str1."<td><input type=\"button\" class=\"btn btn-primary btn-sm\"  onclick=\"deltr3(this)\" value=\"删行\"></td>";
                $cert_info = $cert_info.$str1;
                $cert_info = $cert_info."</tr>";
            }
            if($trainginglen!=0)
                $cert_info=$cert_info."<input type=\"hidden\" name=\"counttrain\" id=\"counttrain\" value=\"".$trainginglen."\" >";

            $worklen=sizeof($work_exp_tmp);
            $work_exp="";
            for($i=0;$i<$worklen;$i++){
                $work_exp = $work_exp."<tr style=\"width:800px\">";
                $str2="" ;
                $str2=$str2."<td><input type=\"text\" class=\"form-control\" name=\"work_unit".$i."\" value=\"".($work_exp_tmp[$i]['work_unit'])."\"/></td>";
                $str2=$str2."<td><input type=\"text\" class=\"form-control\" name=\"unit_address".$i."\" value=\"".($work_exp_tmp[$i]['unit_address'])."\"/></td>";
                $str2=$str2."<td><input  class=\"form-control\" name=\"post".$i."\" type=\"text\" class=\"iput1\" value=\"".($work_exp_tmp[$i]['post'])."\" /></td>";
                $str2=$str2."<td><input  class=\"form-control\" name=\"duty".$i."\" type=\"text\" class=\"iput1\" value=\"".($work_exp_tmp[$i]['duty'])."\" /></td>";
                $str2=$str2."<input type=\"hidden\" name=\"no\" value=\"1\"/><td><input type=\"text\" class=\"form-control\" id=\"work_start_time".$i."\" readonly=\"readonly\"  onFocus=\"WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:$('#work_start_time".$i."').val()})\"  name=\"work_start_time".$i."\" value=\"".($work_exp_tmp[$i]['work_start_time'])."\"/></td>";
                $str2=$str2."<td><input type=\"text\" class=\"form-control\" id=\"work_end_time".$i."\"  readonly=\"readonly\"  onFocus=\"WdatePicker({dateFmt:'yyyy-MM-dd',minDate:$('#work_end_time".$i."').val()})\"  name=\"work_end_time".$i."\" value=\"".($work_exp_tmp[$i]['work_end_time'])."\"/></td>";
                $str2=$str2."<td><input type=\"text\" class=\"form-control\" name=\"witness".$i."\" value=\"".($work_exp_tmp[$i]['witness'])."\"/></td>";
                if($work_exp_tmp[$i]['security_related']== 1)
                    $str2=$str2."<td style=\"width: 10%;text-align: center\"> <select name=\"security_related".$i."\" require=\"true\"> <option value=1 selected=\"selected\">是</option> <option value=0 >否</option> </select> </td>";
                else
                    $str2=$str2."<td style=\"width: 10%;text-align: center\"> <select name=\"security_related".$i."\" require=\"true\"> <option value=1 >是</option> <option value=0 selected=\"selected\">否</option> </select> </td>";
                $str2=$str2."<td><input type=\"button\" class=\"btn btn-primary btn-sm\"  onclick=\"deltr2(this)\" value=\"删行\"></td>";
                $work_exp=$work_exp.$str2;
                $work_exp=$work_exp."</tr>";
            }
            if($worklen!=0)
                $work_exp=$work_exp."<input type=\"hidden\" name=\"countwork\" id=\"countwork\" value=\"".$worklen."\" >";
            $relationstr=$userregistrationinfo['ep_relationship'];

            //从后向前截取字符串
            $str1 = mb_substr($relationstr,-5,-1,'utf-8');
            $str2 = mb_substr($relationstr,-3,-1,'utf-8');
            if($str1=='直接领导') {
                $userregistrationinfo['dirlead'] = 1;
                $userregistrationinfo['ep_bumen']=mb_substr($relationstr,0,-5,'utf-8');
            }
            if($str2=='其他'){
                $userregistrationinfo['other']=1;
                $userregistrationinfo['ep_bumen']=mb_substr($relationstr,0,-3,'utf-8');
            }

        } else {
            $this->error('参数错误！');
        }
        // $usergroup = M('auth_group')->field('id,title')->select();
        // $this->assign('usergroup', $usergroup);
        $this->institutionselect();
        $this->classselect();
        $this->assign('uid',$uid);
        $this->assign('basic_info',$basic_info);
        $this->assign('id_type',$id_type);
        $this->assign('contact_info',$contact_info);
        $this->assign('account_type',$account_type);
        $this->assign('work_exp',$work_exp);
        $this->assign('educationstr',$educationstr);
        $this->assign('certificateinfo',$usercertificateinfo);
        $this->assign('cert_info',$cert_info);
        $this->assign('userregistrationinfo',$userregistrationinfo);
        $this->assign('userphoto',$userphoto);
        $this->assign('idphoto',$idphoto);
        $this->assign('professionphoto',$professionphoto);
        $this->assign('cispphoto',$cispphoto);
        //$this->assign('personnel', $personnel);
        $this->assign('flag',$flag);
        $this->display('form');
    }

    function download(){
        $id=$_GET['fid'];
        $file=M('file');
        $data=$file->find($id);
        $filepath=$data['filepath'];
        $file_name=$data['filesavename'];
        $file_path = "Public/qwadmin/Uploads/Photo/".$filepath.$file_name;
        // echo $file_path;
        //转码，文件名转为gb2312解决中文乱码
        $file_name = iconv("utf-8","gb2312",$file_name);
        $file_path = iconv("utf-8","gb2312",$file_path);
        $fp = fopen($file_path,"r") or exit("文件不存在");
        //定义变量空着每次下载的大小
        $buffer = 1024;
        //得到文件的大小
        $file_size = filesize($file_path);
        //header("Content-type:text/html;charset=gb2312");
        //会写用到的四条http协议信息
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");//可以忽略
        header("Content-Length: ".$file_size);//原文这里是Accept-Length经查阅http协议无此项
        header("Content-Disposition:attachment;filename=".$file_name);
//字节技术器，纪录当前现在字节数
        $count = 0;
        while(!feof($fp) && $file_size-$count>0){
//从$fp打开的文件流中每次读取$buffer大小的数据
            $file_data = fread($fp,$buffer);
            $count+=$buffer;
//将读取到的数据读取出来
            echo $file_data;
        }
//关闭文件流
        fclose($fp);
    }

    public function classselect(){//类型下拉列表查询
    $institution=M('file');
    $arr=$institution->field("file.id,file.classname")
        ->where("personnelid=".session('uid'))
        ->select();
    $this->assign('classList',$arr);
    }

    public function institutionselect(){//类型下拉列表查询
        $institution=M('member');
        $arr=$institution->field("member.uid,member.trainins")
            ->join("auth_group_access ON member.uid = auth_group_access.uid")
            ->join("auth_group ON auth_group_access.group_id = auth_group.id")
//            ->where("auth_group.type='4'")
            ->select();
        $insval=$institution->field("member.uid,member.trainins")
            ->join("auth_group_access ON member.uid = auth_group_access.uid")
            ->join("auth_group ON auth_group_access.group_id = auth_group.id")
            ->where("member.uid=".session('uid'))
            ->find();
        $this->assign('institutionid',$insval['uid']);
        $this->assign('institutionList',$arr);
    }



}
