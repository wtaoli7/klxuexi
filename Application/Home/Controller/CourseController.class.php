<?php
namespace Home\Controller;

use Think\Controller;

header("content-type:text/html;charset=utf-8");

class CourseController extends CommonController
{

    public function index()
    {
        $cityId = $_SESSION['cityInfo']['cityId'];
        $school = D('OrganizationInfo');
        $field = array('display_name,id');
        $this->assign('schoolInfo', $school->queryCitySchool($cityId, $field, '1', ''));// 赋值数据集
        //先清空查询条件
        unset($_SESSION['selectedSchoolId']);
        unset($_SESSION['selectedGradeId']);
        unset($_SESSION['selectedSubjectId']);
        $this->assign('selectedSchool', 'schoolAll');
        $whereSQL = array(
            'c.is_public' => 1,
            'c.status' => 1,
            'c.city_id' => $cityId,
        );
        if (!empty($grade)) {
            $_SESSION['selectedGradeId'] = $grade;
            $this->assign('selectedGrade', 'grade' . $grade);
            $whereSQL['c.GRADE_ID'] = $grade;
        } else {
            $this->assign('selectedGrade', 'gradeAll');
        }
        if (!empty($subject)) {
            $_SESSION['selectedSubjectId'] = $subject;
            $this->assign('selectedSubject', 'subject' . $subject);
            $whereSQL['c.SUBJECT_ID'] = $subject;
        } else {
            $this->assign('selectedSubject', 'subjectAll');
        }

        $course = M('course')->alias('c');
        $course->join('left join ent_organization_info o on c.branch_id=o.id');
        $count = $course->where($whereSQL)->count();// 查询满足要求的总记录数
        $Page = getpage($count, 6);// 实例化分页类 传入总记录数和每页显示的记录数(6)

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $course = M('course')->alias('c');
        $course->join('left join ent_organization_info o on c.branch_id=o.id');
        $courseList = $course->field('c.summary,c.path_picture,c.id,c.display_name,c.course_time_desc,c.course_place,round(c.UNIT_PRICE * c.COURSE_COUNT) total_price')->where($whereSQL)->order('c.id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $this->assign('courseList', $courseList);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        //记录当前操作url，切换城市时，重定向到该地址
        session('curUrl', __ACTION__);
        $this->assign('title', '快乐学习官网|课程搜索');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->display('index');
    }

    /**
     * 查询课程信息
     */
    public function searchCourse()
    {
        $cityId = $_SESSION['cityInfo']['cityId'];
        $school = D('OrganizationInfo');
        $field = array('display_name,id');
        $this->assign('schoolInfo', $school->queryCitySchool($cityId, $field, '1', ''));// 赋值数据集
        $subject = I('get.subject', '', intval());
        $grade = I('get.grade', '', intval());
        $school = I('get.school', '', intval());
        $courseName = I('get.courseName', intval());
        $whereSQL = array(
            'c.is_public' => 1,
            'c.status' => 1,
            'c.city_id' => $cityId,
        );

        //设置年级查询条件
        if (!empty($grade)) {
            if (strcmp($grade, 'All') !== 0) {
                $this->assign('selectedGrade', 'grade' . $grade);
                $whereSQL['c.GRADE_ID'] = $grade;
            } else {
                $this->assign('selectedGrade', 'gradeAll');
            }
            $_SESSION['selectedGradeId'] = $grade;
        } else {
            if (ISSET($_SESSION['selectedGradeId'])) {
                $this->assign('selectedGrade', 'grade' . $_SESSION['selectedGradeId']);
                if (strcmp($_SESSION['selectedGradeId'], 'All') !== 0) {
                    $whereSQL['c.GRADE_ID'] = $_SESSION['selectedGradeId'];
                }
            } else {
                $this->assign('selectedGrade', 'gradeAll');
            }
        }

        if (!empty($courseName)) {
            $this->assign('courseName', $courseName);
            $whereSQL['c.display_name'] = array('like', '%' . $courseName . '%');
        }

        //设置科目查询条件
        if (!empty($subject)) {
            if (strcmp($subject, 'All') !== 0) {
                $this->assign('selectedSubject', 'subject' . $subject);
                $whereSQL['c.SUBJECT_ID'] = $subject;
            } else {
                $this->assign('selectedSubject', 'subjectAll');
            }
            $_SESSION['selectedSubjectId'] = $subject;
        } else {
            if (ISSET($_SESSION['selectedSubjectId'])) {
                $this->assign('selectedSubject', 'subject' . $_SESSION['selectedSubjectId']);
                if (strcmp($_SESSION['selectedSubjectId'], 'All') !== 0) {
                    $whereSQL['c.SUBJECT_ID'] = $_SESSION['selectedSubjectId'];
                }
            } else {
                $this->assign('selectedSubject', 'subjectAll');
            }
        }
        //设置学校查询条件
        if (!empty($school)) {
            if (strcmp($school, 'All') !== 0) {
                $this->assign('selectedSchool', 'school' . $school);
                $whereSQL['c.BRANCH_ID'] = $school;
            } else {
                $this->assign('selectedSchool', 'schoolAll');
            }
            $_SESSION['selectedSchoolId'] = $school;
        } else {
            if (ISSET($_SESSION['selectedSchoolId'])) {
                $this->assign('selectedSchool', 'school' . $_SESSION['selectedSchoolId']);
                if (strcmp($_SESSION['selectedSchoolId'], 'All') !== 0) {
                    $whereSQL['c.BRANCH_ID'] = $_SESSION['selectedSchoolId'];
                }
            } else {
                $this->assign('selectedSchool', 'schoolAll');
            }
        }

        $course = M('course')->alias('c');
        $course->join('left join ent_organization_info o on c.branch_id=o.id');
        $count = $course->where($whereSQL)->count();// 查询满足要求的总记录数
        $Page = getpage($count, 6);
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $course = M('course')->alias('c');
        $course->join('left join ent_organization_info o on c.branch_id=o.id');
        $courseList = $course->field('c.summary,c.path_picture,c.id,c.display_name,c.course_time_desc,c.course_place,round(c.UNIT_PRICE * c.COURSE_COUNT) total_price')->where($whereSQL)->order('c.id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('courseList', $courseList);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('title', '快乐学习官网|课程搜索');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->display('index');
    }

    /**
     * 查询课程详情
     */
    public function showCourseDetail()
    {
        $courseId = I('get.courseId', '', intval());
        $course = M('course')->alias('c');
        $field = array('c.path_picture,c.display_name', 'c.desc_link',
            'c.id', 'c.course_time_desc', 'c.course_place', 'round(c.UNIT_PRICE * c.COURSE_COUNT) total_price', 'c.course_no', 'c.people_count', 'c.course_count');
        $where = array(
            'c.ID' => $courseId,
        );
        $courseInfo = $course->field($field)->where($where)->find();
        F($courseId, $courseInfo);
        $courseTeacher = M('courseTeacher')->alias('a')->join('left  join ent_teacher_info b on a.teacher_id=b.ID');
        $courseTeacher->where(array('b.is_public' => '1', 'b.status' => '1', 'a.status' => '1', 'a.course_id' => $courseId))->field('distinct b.ID,5 evl_num, CASE
WHEN CHAR_LENGTH(b.DESCRIPTION) > 50 THEN
	CONCAT(
		LEFT (b.DESCRIPTION, 100),
		\'。。。\'
	) 
ELSE
	b.DESCRIPTION
END DESCRIPTION,b.path_picture, b.TEACHER_NAME');
        $teacherInfo = $courseTeacher->select();
        $this->assign('course', $courseInfo);
        $this->assign('teacherList', $teacherInfo);
        //记录当前操作url，切换城市时，重定向到该地址
        $this->assign('title', '快乐学习官网|课程搜索');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->display('coursedetail');
    }

    public function onlineOrderIndex()
    {
        $this->buId = getBuId($_SESSION['cityCode'], 'Dxb');
        $this->assign('schoolInfo', getEnableBranch($this->buId));// 赋值数据集
        $this->assign('subjectInfo', getEnableSubject($this->buId));// 赋值数据集
        $this->assign('gradeInfo', getEnableGrade($this->buId));// 赋值数据集
        //先清空查询条件
        unset($_SESSION['selectedSchoolId']);
        unset($_SESSION['selectedGradeId']);
        unset($_SESSION['selectedSubjectId']);
        $this->assign('selectedSchool', 'schoolAll');
        $this->assign('selectedGrade', 'gradeAll');
        $this->assign('selectedSubject', 'subjectAll');
        $whereSQL = array(
            'c.is_public' => 1,
            'c.status' => 1,
        );

        $buId = getBuId($_SESSION['city'], $_SESSION['buType']);

        if (!empty($buId)) {
            $whereSQL['o.PARENT_ID'] = $buId;
        }
        $course = M('course')->alias('c');
        $course->join('left join ent_organization_info o on c.branch_id=o.id');
        $count = $course->where($whereSQL)->count();// 查询满足要求的总记录数
        $Page = getpage($count, 6);// 实例化分页类 传入总记录数和每页显示的记录数(6)

        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $course = M('course')->alias('c');
        $course->join('left join ent_organization_info o on c.branch_id=o.id');
        $courseList = $course->field('c.summary,c.path_picture,c.id,c.display_name,c.course_time_desc,c.course_place,round(c.UNIT_PRICE * c.COURSE_COUNT) total_price')->where($whereSQL)->order('c.id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $this->assign('courseList', $courseList);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->display();
    }

    /**
     * 在线报班
     */
    public function onlineOrder()
    {
        $buId = getBuId($_SESSION['cityCode'], 'Dxb');
        if ($buId == NULL) {
            $this->error('没有查到对应的校区信息，请重新选择校区报名');
        }
        $payConfig = F($buId);
        if ($payConfig == NULL) {
            $orgMp = M('orgMp');
            $payConfig = $orgMp->where(array('ORG_ID' => $buId))->find();
            F($buId, $payConfig);
        }
        if ($payConfig === NULL) {
            $this->error('该校区没有开通在线支付，请联系校区');
        }
        F($payConfig['app_id'], $payConfig);
        $appId = $payConfig['app_id'];
        $mchId = $payConfig['mch_id'];
        $key = $payConfig['mch_key'];
        $appSecret = $payConfig['app_secret'];
        $wxPayConfig = new \WxPayConf($appId, $mchId, $key, $appSecret);
        //设置静态链接
        $nativeLink = new \NativeLink_pub($wxPayConfig);
        //设置静态链接参数
        //设置必填参数
        $product_id = I('get.courseId');
        //生成支付二维码前就缓存课程信息，下单时直接从缓存里取数据
        $course = M('course');
        $where = array('ID' => $product_id);
        $field = array('display_name', 'id', 'course_time_desc', 'course_place', 'round(UNIT_PRICE * COURSE_COUNT) total_price', 'course_no', 'people_count', 'course_count');
        $course = $course->field($field)->where($where)->find();
        $courseName = $course['display_name'];
        F($product_id, $course);
        $nativeLink->setParameter("product_id", $product_id . '-' . $_SESSION['mobile']);//商品id
        //获取链接
        $product_url = $nativeLink->getUrl();

        //使用短链接转换接口WxPayPubHelper
        $shortUrl = new \ShortUrl_pub($wxPayConfig);
        //设置必填参数
        $shortUrl->setParameter("long_url", $product_url);//URL链接
        $this->assign('product_url', $product_url);
        $this->assign('courseName', $courseName);
        $this->display();
    }

}