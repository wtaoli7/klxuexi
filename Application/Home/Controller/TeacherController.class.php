<?php
namespace Home\Controller;

use Think\Controller;

header("content-type:text/html;charset=utf-8");

class TeacherController extends CommonController
{

    //名师介绍首页
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
            't.is_public' => '1',
            's.STATUS' => '1',
            'g.STATUS' => '1',
        );
        $whereSQL['CITY_ID'] = $cityId;
        $teacher = M('teacherInfo')->alias('t');
        $teacher->join(' left join ent_teacher_subject s on t.id=s.teacher_id');
        $teacher->join(' left join ent_teacher_grade g on t.id=g.teacher_id');
        $count = $teacher->where($whereSQL)->count("DISTINCT t.ID");// 查询满足要求的总记录数
        $Page = getpage($count, 8);// 实例化分页类 传入总记录数和每页显示的记录数(6)

        $show = $Page->show();// 分页显示输出
        $teacher = M('teacherInfo')->alias('t');
        $teacher->join(' left join ent_teacher_subject s on t.id=s.teacher_id');
        $teacher->join(' left join ent_teacher_grade g on t.id=g.teacher_id');

        $teacherList = $teacher->limit($Page->firstRow . ',' . $Page->listRows)->field('distinct t.id,t.teacher_name,t.path_picture,	CASE
WHEN CHAR_LENGTH(t.DESCRIPTION) > 50 THEN
	CONCAT(
		LEFT (t.DESCRIPTION, 50),
		\'...\'
	) 
ELSE
	t.DESCRIPTION
END DESCRIPTION')->where($whereSQL)->select();// 查询满足要求的总记录数
        $this->assign('selectedGrade', 'gradeAll');
        $this->assign('selectedSubject', 'subjectAll');
        $this->assign('teacherList', $teacherList);
        $this->assign('page', $show);// 赋值分页输出
        //记录当前操作url，切换城市时，重定向到该地址
        session('curUrl', __ACTION__);
        $this->assign('title', '快乐学习官网|星级名师');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->display();
    }

    public function searchTeacher()
    {
        $cityId = $_SESSION['cityInfo']['cityId'];
        $school = D('OrganizationInfo');
        $field = array('display_name,id');
        $this->assign('schoolInfo', $school->queryCitySchool($cityId, $field, '1', ''));// 赋值数据集
        $subject = I('get.subject', '', intval());
        $grade = I('get.grade', '', intval());
        $school = I('get.school', '', intval());
        $teacherName = I('get.teacherName', '', intval());
        $whereSQL = array(
            't.is_public' => '1',
            's.STATUS' => '1',
            'g.STATUS' => '1',
        );

        $whereSQL['CITY_ID'] = $cityId;
        if (!empty($teacherName)) {
            $this->assign('teacherName', $teacherName);
            $whereSQL['t.teacher_name'] = array('like', '%' . $teacherName . '%');
        }
        //设置年级查询条件
        if (!empty($grade)) {
            if (strcmp($grade, 'All') !== 0) {
                $whereSQL['g.GRADE_ID'] = $grade;
                $this->assign('selectedGrade', 'grade' . $grade);
            } else {
                $this->assign('selectedGrade', 'gradeAll');
            }
            $_SESSION['selectedGradeId'] = $grade;
        } else {
            if (ISSET($_SESSION['selectedGradeId'])) {
                $this->assign('selectedGrade', 'grade' . $_SESSION['selectedGradeId']);
                if (strcmp($_SESSION['selectedGradeId'], 'All') !== 0) {
                    $whereSQL['g.GRADE_ID'] = $_SESSION['selectedGradeId'];
                }
            } else {
                $this->assign('selectedGrade', 'gradeAll');
            }
        }

        //设置科目查询条件
        if (!empty($subject)) {
            if (strcmp($subject, 'All') !== 0) {
                $this->assign('selectedSubject', 'subject' . $subject);
                $whereSQL['s.SUBJECT_ID'] = $subject;
            } else {
                $this->assign('selectedSubject', 'subjectAll');
            }
            $_SESSION['selectedSubjectId'] = $subject;
        } else {
            if (ISSET($_SESSION['selectedSubjectId'])) {
                $this->assign('selectedSubject', 'subject' . $_SESSION['selectedSubjectId']);
                if (strcmp($_SESSION['selectedSubjectId'], 'All') !== 0) {
                    $whereSQL['s.SUBJECT_ID'] = $_SESSION['selectedSubjectId'];
                }
            } else {
                $this->assign('selectedSubject', 'subjectAll');
            }
        }

        //设置学校查询条件
        if (!empty($school)) {
            if (strcmp($school, 'All') !== 0) {
                $this->assign('selectedSchool', 'school' . $school);
                $whereSQL['b.branch_id'] = $school;
            } else {
                $this->assign('selectedSchool', 'schoolAll');
            }
            $_SESSION['selectedSchoolId'] = $school;
        } else {
            if (ISSET($_SESSION['selectedSchoolId'])) {
                $this->assign('selectedSchool', 'school' . $_SESSION['selectedSchoolId']);
                if (strcmp($_SESSION['selectedSchoolId'], 'All') !== 0) {
                    $whereSQL['bu_id'] = $_SESSION['selectedSchoolId'];
                }

            } else {
                $this->assign('selectedSchool', 'schoolAll');
            }
        }

        $teacher = M('teacherInfo')->alias('t');
        $teacher->join(' left join ent_teacher_subject s on t.id=s.teacher_id');
        $teacher->join(' left join ent_teacher_grade g on t.id=g.teacher_id');
        $teacher->join(' left join ent_teacher_branch b on t.id= b.teacher_id');
        $count = $teacher->where($whereSQL)->count("DISTINCT t.ID");// 查询满足要求的总记录数
        $Page = getpage($count, 8);// 实例化分页类 传入总记录数和每页显示的记录数(6)

        $show = $Page->show();// 分页显示输出
        $teacher = M('teacherInfo')->alias('t');
        $teacher->join(' left join ent_teacher_subject s on t.id=s.teacher_id');
        $teacher->join(' left join ent_teacher_grade g on t.id=g.teacher_id');
        $teacher->join(' left join ent_teacher_branch b on t.id= b.teacher_id');

        $teacherList = $teacher->limit($Page->firstRow . ',' . $Page->listRows)->field('distinct t.id,t.teacher_name,t.DESCRIPTION,t.path_picture')->where($whereSQL)->select();// 查询满足要求的总记录数
        $this->assign('teacherList', $teacherList);
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('title', '快乐学习官网|星级名师');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->display('index');
    }

    public function showTeacherDetail()
    {
        $teacherId = I('get.teacherId', '', intval());
        $field = array(
            't.id,t.teacher_name,t.path_picture,5 evl_num,t.idea',
        );
        $teacher = M('teacherInfo')->alias('t');
        $teacherInfo = $teacher->field($field)->where(array('t.id' => $teacherId))->find();

        $teacherSubject = M('teacherSubject')->alias('s');
        $teacherSubject->join(' left join ent_subject su on su.id=s.subject_id');
        $teacherSubjectInfo = $teacherSubject->field('s.teacher_id id,group_concat(su.NAME) subject_name')->group('s.teacher_id')->where(array('s. STATUS' => 1, 's.teacher_id' => $teacherId))->find();

        if (!is_null($teacherSubjectInfo) && !empty($teacherSubjectInfo)) {
            $teacherInfo = array_merge($teacherInfo, $teacherSubjectInfo);
        }

        $teacherGrade = M('teacherGrade')->alias('g');
        $teacherGrade->join(' left join ent_grade gr on gr.id=g.grade_id');
        $teacherGradeInfo = $teacherGrade->field('g.teacher_id id,group_concat(gr.grade_name) grade_name')->group('g.teacher_id')->where(array('g.STATUS' => 1, 'g.teacher_id' => $teacherId))->find();

        if (!is_null($teacherGradeInfo)) {
            $teacherInfo = array_merge($teacherInfo, $teacherGradeInfo);
        }

        $this->assign('teacher', $teacherInfo);
        $courseList = M('course')->alias('a')->field('distinct a.id,a.display_name,a.course_no,a.path_picture,a.people_count,a.start_date,a.unit_price,a.course_count')->join('left join ent_course_teacher b on a.id=b.course_id')->where(array('b.TEACHER_ID' => $teacherId, 'a.is_public' => '1'))->select();
        $this->assign('courseList', $courseList);
        //记录当前操作url，切换城市时，重定向到该地址
        $this->assign('title', '快乐学习官网|星级名师');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->display('teacherdetail');
    }

}