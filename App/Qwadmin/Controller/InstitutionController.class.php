<?php
/**
 *
 * 版权所有：恰维网络<qwadmin.qiawei.com>
 * 作    者：寒川<hanchuan@qiawei.com>
 * 日    期：2016-01-20
 * 版    本：1.0.0
 * 功能说明：用户控制器。
 *
 **/

namespace Qwadmin\Controller;

class InstitutionController extends ComController
{
    public function index()
    {

        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
//        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '';

        session('institutionedit',null);//清除用于在update中区分是编辑还是添加的标志session

        $prefix = C('DB_PREFIX');

        if ($keyword <> '') {
            if ($field == 'trainins_name') {
                $where = "{$prefix}trainins_info.trainins_name LIKE '%$keyword%'";
            }
            if ($field == 'trainins_fixed_phone') {
                $where = "{$prefix}trainins_info.trainins_fixed_phone LIKE '%$keyword%'";
            }

            if ($field == 'trainins_email') {
                $where = "{$prefix}trainins_info.trainins_email LIKE '%$keyword%'";
            }
        }

        $user = M('trainins_info');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
//        if($where=='') $where="auth_group_access.group_id=8";
//        else $where.="and auth_group_access.group_id=8";

        $count = $user->field("{$prefix}trainins_info.*")

//            ->join("{$prefix}auth_group_access ON {$prefix}trainins_info.uid = {$prefix}auth_group_access.uid")
//            ->join("{$prefix}auth_group ON {$prefix}auth_group.id = {$prefix}auth_group_access.group_id")
            ->where($where)
            ->count();

        $list = $user->field("{$prefix}trainins_info.*")

//            ->join("{$prefix}auth_group_access ON {$prefix}trainins_info.uid = {$prefix}auth_group_access.uid")
//            ->join("{$prefix}auth_group ON {$prefix}auth_group.id = {$prefix}auth_group_access.group_id")
            ->where($where)
            ->limit($offset . ',' . $pagesize)
            ->select();

        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
//        $group = M('auth_group')->field('id,title')->select();
//        $this->assign('group', $group);
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
//                if ($v == 0) {//uid为1的禁止删除
//                    unset($uids[$k]);
//                }
                $uids[$k] = intval($v);
            }
            if (!$uids) {
                $this->error('参数错误！');
                $uids = implode(',', $uids);
            }
            $map['uid'] = array('in', $uids);
            $mapaccess['uid'] = array('in', $uids);
//            $mapaccess['group_id'] = array('eq', 8);//这里只能删除角色为培训人员
        }
        else {
            $map['uid'] = array('eq', $uids);
            $mapaccess['uid'] = array('eq', $uids);
        }

        if (M('trainins_info')->where($map)->delete()) {
//            M('auth_group_access')->where($mapaccess)->delete();
            addlog('删除培训机构UID：' . $uids);
            $this->success('培训机构删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function edit()
    {
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;

        if ($uid) {

            session('institutionedit','1');


            $prefix = C('DB_PREFIX');
            $user = M('trainins_info');
            $institution = $user->field("{$prefix}trainins_info.*")->where("{$prefix}trainins_info.uid=$uid")->find();

        } else {
            $this->error('参数错误！');
        }



        $this->assign('uid',$uid);
//        $this->assign('usergroup', $usergroup);
//        $this->assign('coursetypestr', $coursetypestr);
        $this->assign('institutioninfo', $institution);
        $this->display('form');
    }

    public function update($ajax = '')
    {

        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;


        $data['trainins_name'] = isset($_POST['trainins_name']) ? htmlspecialchars($_POST['trainins_name'], ENT_QUOTES) :  '';

        $data['trainins_contact'] = isset($_POST['trainins_contact']) ? trim($_POST['trainins_contact']) :  '';
        $data['trainins_address'] = isset($_POST['trainins_address']) ? trim($_POST['trainins_address']) :  '';

        $data['trainins_fixed_phone'] = isset($_POST['trainins_fixed_phone']) ? trim($_POST['trainins_fixed_phone']) :  '';
        $data['trainins_mobile_phone'] = isset($_POST['trainins_mobile_phone']) ? trim($_POST['trainins_mobile_phone']) :  '';

        $data['trainins_fax'] = isset($_POST['trainins_fax']) ? trim($_POST['trainins_fax']) :  '';
        $data['trainins_email'] = isset($_POST['trainins_email']) ? trim($_POST['trainins_email']) :  '';


       if(!session('institutionedit')) {//用于在update中区分是编辑还是添加的标志session,如果是编辑的话就不用考虑名称重复的事情
            $count = M('trainins_info')->where("trainins_name = '{$data['trainins_name']}'")->count();
            if ($count != 0) {
                $this->error('培训机构名称重复！');
            }

        }else{
           $count1 = M('trainins_info')->where("trainins_name = '{$data['trainins_name']}' and uid<>$uid")->count();
           if ($count1 != 0) {
               $this->error('培训机构名称重复！');
           }
       }
        session('institutionedit',null);//清除用于在update中区分是编辑还是添加的标志session


        if (!$uid) {

            $uid = M('trainins_info')->data($data)->add();
//            M('auth_group_access')->data(array('group_id' => 8, 'uid' => $uid))->add();
            addlog('新增培训机构，会员UID：' . $uid);
        } else {

            addlog('编辑培训机构信息，会员UID：' . $uid);
            M('trainins_info')->data($data)->where("uid=$uid")->save();

        }

        $this->redirect('index');
    }


    public function add()
    {

        $coursetype=M('train_type')->field('train_type,id')->select();
        $cousertypelen=sizeof($coursetype);//培训种类的个数
        $coursetypestr="";
        for($i=0;$i<$cousertypelen;$i++){
            $coursetypestr=$coursetypestr."<input type=\"checkbox\" name=\"coursetype[]\" id=\"coursetype".$coursetype[$i]["id"]."\" value=\"".$coursetype[$i]["train_type"]."\">".$coursetype[$i]["train_type"];
        }

        $this->assign('coursetypestr',$coursetypestr);

        $this->display('form');
    }
}
