<?php
namespace Qwadmin\Controller;

class IntegralController extends ComController
{
    public function index()
    {

        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';

        $where = '';

        session('integraledit',null);//清除用于在update中区分是编辑还是添加的标志session

        $prefix = C('DB_PREFIX');

        if ($keyword <> '') {
            if ($field == 'integral_rule_type') {
                $where = "{$prefix}integral_rule.integral_rule_type LIKE '%$keyword%'";
            }
            if ($field == 'rule_description') {
                $where = "{$prefix}integral_rule.rule_description LIKE '%$keyword%'";
            }

            if ($field == 'integral_value') {
                $where = "{$prefix}integral_rule.integral_value LIKE '%$keyword%'";
            }
        }

       $user = M('integral_rule');
        $pagesize = 10;#每页数量
        $offset = $pagesize * ($p - 1);//计算记录偏移量
        $count = $user  
            ->count();
        $list = $user          
            ->limit($offset . ',' . $pagesize)
            ->select();

        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);

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
                if ($v == 0) {//uid为1的禁止删除
                    unset($uids[$k]);
                }
                $uids[$k] = intval($v);
            }
            if (!$uids) {
                $this->error('参数错误！');
                $uids = implode(',', $uids);
            }
            $map['uid'] = array('in', $uids);
            $mapaccess['uid'] = array('in', $uids);

        }
        else {
            $map['uid'] = array('eq', $uids);
            $mapaccess['uid'] = array('eq', $uids);
        }

        if (M('integral_rule')->where($map)->delete()) {
           
            addlog('删除积分规则UID：' . $uids);
            $this->success('积分规则删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function edit()
    {
        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
     //   printf($uid);
        if ($uid) {
            session('integraledit','1');
            $user = M('integral_rule');
            $integral = $user->field("integral_rule.*")->where("integral_rule.uid=$uid")->find();
        } else {
            $this->error('参数错误！');
        }


        $this->assign('uid',$uid);

        $this->assign('integralinfo', $integral);
        $this->display('form');
    }

    public function update($ajax = '')
    {

        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;   

        $data['integral_rule_type'] = isset($_POST['integral_rule_type']) ? htmlspecialchars($_POST['integral_rule_type'], ENT_QUOTES) :  '';

        $data['rule_description'] = isset($_POST['rule_description']) ? trim($_POST['rule_description']) :  '';
        $data['integral_value'] = isset($_POST['integral_value']) ? trim($_POST['integral_value']) :  '';


       if(!session('integraledit')) {//用于在update中区分是编辑还是添加的标志session,如果是编辑的话就不用考虑名称重复的事情
            $count = M('integral_rule')->where("rule_description = '{$data['rule_description']}'")->count();
            if ($count != 0) {
                $this->error('积分规则重复！');
            }

        }else{
           $count1 = M('integral_rule')->where("rule_description = '{$data['rule_description']}' and uid<>$uid")->count();
           if ($count1 != 0) {
               $this->error('积分规则重复！');
           }
       }
        session('integraledit',null);//清除用于在update中区分是编辑还是添加的标志session


        if (!$uid) {

            $uid = M('integral_rule')->data($data)->add();
            
            addlog('新增积分规则，会员UID：' . $uid);
        } else {
           
            addlog('编辑积分规则，会员UID：' . $uid);
            M('integral_rule')->data($data)->where("uid=$uid")->save();

        }

        $this->redirect('index');
    }

  public function add()
    {
        $this->display('form');
    }
   
}
