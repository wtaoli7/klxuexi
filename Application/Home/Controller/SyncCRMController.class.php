<?php
namespace Home\Controller;

use Think\Controller;


header("content-type:text/html;charset=utf-8");

class SyncCRMController extends Controller
{


    public function syncToCrm($key='')
    {
        if (IS_POST==false)
            {
                $this->ajaxReturn('{"code":"400","msg":"非法请求"}', 'JSON');
            }
         if($key!=getSecretStr())
         {
              $this->ajaxReturn('{"code":"401","msg":"秘钥不对"}', 'JSON');
         }
        try {
            $apt = M('appointment')->alias('a');
            $field = array('a.tel_number as phone',
                'a.call_name as callName',
                'a.subject_name as subjectName',
                'a.grade_level as gradeName',
                'a.remark as note',
                'a.create_time as createTime',
                'a.bu_id as buId',
            );
            $lastUpdtedId=$this->getLastUpdatedId();
            $where['a.id'] = array('GT',$lastUpdtedId );
            $where['a.type'] = array('EQ', 1);
            $apt->where($where);
            $apt->field($field);
            $data = $apt->select();
            $data['maxId']=$this->getMaxId();
            $this->ajaxReturn($data, 'JSON');

        }
        catch (Exception $e) {
            return json_decode('{"code":"500","msg":"响应发生异常"}');
        }
    }

    //同步之后执行更新预约信息id号
    public function syncAfterPost($lastUpdatedId='',$key='')
    {
        if (IS_POST==false)
        {
            $this->ajaxReturn('{"code":"400","msg":"非法请求"}', 'JSON');
        }
        if($key!=getSecretStr())
        {
            $this->ajaxReturn('{"code":"401","msg":"秘钥不对"}', 'JSON');
        }
        $sync = M('syn')->alias('s');
        try {
            $sync = M("syn");
            $data['last_update_id'] = $lastUpdatedId;
            $data['is_syn'] = 1;
            $where['s.code'] = array('EQ',"appoint" );
            $sync->where($where);
            $sync->where($where)->save($data);
     }
          catch (Exception $e){
              $this->ajaxReturn('{"code":"403","msg":"服务器执行错误"}', 'JSON');

          }
    }

    private function getMaxId()
     {
        $apt = M('appointment')->alias('a');
        $field = array('max(a.id) as maxId');
        $where['a.type'] = array('EQ', 1);
        $apt->where($where);
        $apt->field($field);
        return $apt->select();

    }

    //获取同步的最大编号
    private function getLastUpdatedId()
    {
        $sync = M('syn')->alias('s');
        $where['s.code'] = array('EQ',"appoint" );
        $where['s.is_syn'] = array('EQ',true);
        $sync->where($where);
        $data = $sync->getField('last_update_id', true);
        return empty($data[0])?0:$data[0];
    }
}