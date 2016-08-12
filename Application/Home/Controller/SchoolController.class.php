<?php
namespace Home\Controller;

use Think\Controller;

header("content-type:text/html;charset=utf-8");

class SchoolController extends CommonController
{

//    public function test()
//    {
//        $school = D('OrganizationInfo');
//        $data = $school->queryCitySchool('5', 'org_name', '1', 'id', '1,3');
//        dump($data);
//    }
//
//    public function tool()
//    {
//        $this->display('schooltool');
//    }

    //学校介绍首页
    public function index()
    {
        $productLine = $_GET['productLine'];
        if (empty($productLine)) {
            $productLine = "1,2";
            $this->assign('productLine', null);
        } else {
            $this->assign('productLine', $productLine);
        }
        $cityId = $_SESSION['cityInfo']['cityId'];
        session('curUrl', __ACTION__);
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->assign('title', '快乐学习官网|校区地址');
        $school = D('OrganizationInfo');
        $count = $school->queryCitySchoolCount($cityId, $productLine);
        $Page = getSimplePage($count, 6);// 实例化分页类 传入总记录数和每页显示的记录数(6)
        $show = $Page->show();// 分页显示输出
        $field = array('address,id,display_name,org_name,path_picture,telephone,position_x,position_y');
        $limit = $Page->firstRow . ',' . $Page->listRows;
        $order = 'product_line,sort_number';
        $schoolInfo = $school->queryCitySchool($cityId, $field, $productLine, $order, $limit);
        $this->assign('schoolList', $schoolInfo);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display();
    }

    public function querySchoolById()
    {
        $schoolId = I('post.schoolId', '', intval());
        $schoolInfo = F($schoolId . '_school');
        if ($schoolInfo == null) {
            $school = M('organizationInfo')->alias('a');
            $where = array(
                'ID' => $schoolId,
            );
            $schoolInfo = $school->field('a.address,a.id,a.display_name,a.org_name,a.path_picture,a.telephone,a.position_x,a.position_y')->where($where)->find();
            F($schoolId . '_school', $schoolInfo);
        }
        $this->ajaxReturn($schoolInfo, 'JSON');
    }

    public
    function schoolDetail()
    {
        $schoolId = I('get.schoolId');
        $school = M('organizationInfo');
        $where = array(
            'ID' => $schoolId,
        );
        $schoolInfo = $school->where($where)->find();
        $this->assign('schoolInfo', $schoolInfo);
        //校区交通图
        $picture = M('pictures');
        $where2 = array(
            'branch_id' => $schoolId,
            'picture_type' => '3',
            'status' => '1',
        );
        //查询校区交通图
        $trafficPictureInfo = $picture->field('path_picture')->where($where2)->find();
        $this->assign('trafficPicture', $trafficPictureInfo);
        //校区环境图
        $picture = M('pictures');
        $where3 = array(
            'branch_id' => $schoolId,
            'picture_type' => '4',
            'status' => '1',
        );
        //查询校区的环境图片
        $envPictureInfo = $picture->field('path_picture')->where($where3)->select();
        $this->assign('envPictureInfo', $envPictureInfo);
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->assign('title', '快乐学习官网|校区查询');
        $this->display();
    }

}