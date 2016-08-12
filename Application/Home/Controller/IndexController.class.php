<?php
namespace Home\Controller;

use Think\Controller;

header("content-type:text/html;charset=utf-8");

class IndexController extends CommonController
{

    private $cityMap = array(
        'xiamen' => array(
            'cityCode' => 'xiamen',
            'cityName' => '厦门',
            'cityId' => '3'
        ),
        'fuzhou' => array(
            'cityCode' => 'fuzhou',
            'cityName' => '福州',
            'cityId' => '5'
        ),
        'nanchang' => array(
            'cityCode' => 'nanchang',
            'cityName' => '南昌',
            'cityId' => '6'
        ),
        'quanzhou' => array(
            'cityCode' => 'quanzhou',
            'cityName' => '泉州',
            'cityId' => '7'
        ),
        'hefei' => array(
            'cityCode' => 'hefei',
            'cityName' => '合肥',
            'cityId' => '9'
        )
    );

    public function page() {
        $this->display();
    }

    public function search()
    {
        require 'sphinxapi.php';
        $s = new \SphinxClient();
        $s->SetServer('localhost', 9312);
        $result = $s->Query('test');
        var_dump($result);
        echo '<br /><br />';
        $result = $s->Query('doc number four');
        var_dump($result);
    }

    private function changeCity($city)
    {
        $cityArray = array('xiamen', 'fuzhou', 'quanzhou', 'nanchang', 'hefei');
        if (isset($city) && in_array($city, $cityArray)) {
            session('cityInfo', $this->cityMap[$city]);
            session('cityName', $this->cityMap[$city]['cityName']);
            session('cityCode', $this->cityMap[$city]['cityCode']);
            redirect(session('curUrl'));
        }
    }

    //默认
    public function index()
    {
        //记录当前操作url，切换城市时，重定向到该地址
        session('curUrl', __ACTION__);
        $this->assign('title', '快乐学习官网|首页');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        session('cityInfo', $this->cityMap[session('cityCode')]);
        $this->assign('indexLatestNews', $this->getLatestNews());
        $this->assign('dxbCourseImgs', $this->getCourseInfo());
        $this->assign('dxbTeacherImgs', $this->getTeachersInfo());
        $this->display();
    }

    public function xiamen()
    {
        $this->changeCity('xiamen');
    }

    public function fuzhou()
    {
        $this->changeCity('fuzhou');
    }

    public function quanzhou()
    {
        $this->changeCity('quanzhou');
    }

    public function hefei()
    {
        $this->changeCity('hefei');
    }

    public function nanchang()
    {
        $this->changeCity('nanchang');
    }

    //获取最新消息
    private
    function getLatestNews()
    {
        $article = M('article')->alias('ent');
        $article->join('left join ent_article_type  entp on ent.type=entp.id ');
        $article->join('left join ent_organization_info org on ent.bu_id=org.id ');
        $where = array(
            'ent.status' => 1,
            'ent.is_public' => 1,
        );
        $where['ent.type'] = array('NOT IN', '4,5,7,8,9');
        $where['org.parent_id'] = $_SESSION['cityInfo']['cityId'];

        $article->where($where)->field(array('summary', 'ent.id as articleId', 'CONCAT(\'\',ent.title) as title', 'name', 'link_address as link', 'hits', 'DATE_FORMAT(ent.CREATE_TIME,\'%Y-%c-%d \') create_time'));
        $data = $article->order('ent.id desc')->limit(4)->select();
        return $data;
    }

    //获取首页的图片集合
    private
    function getCourseInfo()
    {
        $cityId = $_SESSION['cityInfo']['cityId'];
        $course = M('course')->alias('c');
        $course->join('INNER JOIN ent_organization_info e ON c.branch_id=e.id ');
        $ary = array('city_id' => $cityId, 'c.is_recommend' => 1, 'c.is_public' => 1, 'c.status!=0');
        $course->where($ary);
        $data = $course->field(array('c.id as id', 'c.path_picture', 'c.display_name', 'c.course_place school_name', 'round(c.UNIT_PRICE * c.COURSE_COUNT) total_price'))->order('c.create_time desc')->limit(8)->select();
        return $data;
    }

    //获取教师推荐
    private
    function getTeachersInfo()
    {
        $teacher = M('teacher_info')->alias('t');
        $teacher->join('LEFT JOIN ent_organization_info e on t.bu_id = e.id');
        $map['t.evl_num'] = array('EGT', 5);//星级大于等于5星级的教师
        $map["IFNULL(t.path_picture,'')"] = array('NEQ', '');//星级老师的图片不能为空
        $map['e.parent_id'] = $_SESSION['cityInfo']['cityId'];;
        $ary = array('t.status' => 1, 't.is_public' => 1);
        $teacher->where($ary);
        $teacher->where($map);
        $teacher->field(array('t.qualifier', 't.DESCRIPTION', 't.path_picture', 't.id', 't.teacher_name as name', 'CONCAT(t.teacher_Name ,\',\',t.description)as detail '));
        $teacher->limit(4);
        $data = $teacher->select();
        return $data;
    }

    public function aboutus()
    {
        //记录当前操作url，切换城市时，重定向到该地址
        session('curUrl', __ACTION__);
        $this->assign('title', '快乐学习官网|关于我们');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        session('cityInfo', $this->cityMap[session('cityCode')]);
        $this->display();
    }

    public function contact()
    {
        //记录当前操作url，切换城市时，重定向到该地址
        session('curUrl', __ACTION__);
        $this->assign('title', '快乐学习官网|关于我们');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        session('cityInfo', $this->cityMap[session('cityCode')]);
        $this->display();
    }

    public function jobs()
    {
        //记录当前操作url，切换城市时，重定向到该地址
        session('curUrl', __ACTION__);
        $this->assign('title', '快乐学习官网|关于我们');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        session('cityInfo', $this->cityMap[session('cityCode')]);
        $this->display();
    }

//个性化首页
    public
    function gxh()
    {
        //记录当前操作url，切换城市时，重定向到该地址
        session('curUrl', __ACTION__);
        $this->assign('title', '快乐学习官网|1对1辅导');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->assignGradeAndSubject();
        $this->display('gxh');
    }


//保存预约信息
    public
    function saveAppointMent()
    {
        $data = null;
        $buID = getBuId($_SESSION['city'], $_SESSION['buType']);
        try {
            $appoint = M("appointment");
            $data["tel_number"] = I('post.telNumber');
            $data["call_name"] = I('post.callName', '', intval());
            $data['subject_name'] = I('post.subjectName');
            $data['bu_id'] = $buID;
            $data["type"] = I('post.type', '', intval());
            $data["type"] = $data["type"] == null ? 1 : $data["type"];
            $data['grade_level'] = I('post.gradeLevel', '', intval());
            $data['remark'] = I('post.remark', '', intval());
            $josnData = $data;
            $josnData['branchId'] = I('post.branchId', '', intval());
            $data['create_time'] = date('Y-m-d H:m:s');
            $appoint->add($data);

            $this->sendAppointInfo($data["call_name"], $data["tel_number"]);
            //同步至SCRM系统
            $this->syncToCRMSystem($josnData);
        } catch (Exception $e) {
            $this->ajaxReturn(array("status" => 0, "subjectName" => $data['subject_name']));
        }
        //清除手机验证码缓存
        F($data["tel_number"], null);
        $data = array("status" => 1, "callName" => $data['call_name']);
        $this->ajaxReturn($data, 'JSON');
    }

    private function sendAppointInfo($name, $phoneNum)
    {
        try {
            $url = C('SEND_VERIFYCODE_URL');
            $param['mobile'] = $phoneNum;
            $msg_content = C('APPOINT_SUCCESS_MSG_COUTENT');
            $msg_content = str_replace("#name#", $name, $msg_content);
            $param['msg_content'] = $msg_content;
            $json = getPostRst($url, $param);
        } catch (Exception $e) {
            \Think\Log::write("【发送预约短信失败】:\n" . $e->getMessage(), \Think\Log::ERROR);
        }
    }

    //发送验证码
    public
    function sendVerifyCode()
    {
        $verify_code = generate_code(6);
        $url = C('SEND_VERIFYCODE_URL');
        $param['mobile'] = $_POST['mobie'];
        $msg_content = C('GET_VCODE_MSG_CONTENT');
        $msg_content = str_replace("#verify_code#", $verify_code, $msg_content);
        $param['msg_content'] = $msg_content;
        $json = getPostRst($url, $param);
        $json = json_decode($json, true);
        //验证码发送成功才保存至缓存和Session
        if ($json['msg'] != null && $json['msg'] == 'OK') {
            //将验证码永久保存至文件流里面,便于页面刷新或打开新的浏览器不需要重新获取验证码
            F($param['mobile'], $verify_code);
        }
        $this->ajaxReturn($json, "json");
    }

    private
    function assignGradeAndSubject()
    {
        if (isset($_SESSION['gxhSubjectList']))
            unset($_SESSION['gxhSubjectList']);
        if (isset($_SESSION['gxhGradeList']))
            unset($_SESSION['gxhGradeList']);
        $buId = getBuId($_SESSION['city'], $_SESSION['buType']);
        $this->assign('gxhSubjectList', getEnableSubject($buId));//显示个性化科目
        $this->assign('gxhGradeList', getEnableGrade($buId));//显示年级
    }

    //验证验证码是否正确
    public
    function checkCode($tel = "", $code = "")
    {
        $state = (F($tel) == $code && $code != null) ? 1 : 0;
        //验证成功之后保存提交的手机号在SESSION
        if ($state == 1) {
            $_SESSION['mobile'] = $tel;
        }
        $this->ajaxReturn(array("status" => $state));

    }

    //同步至CRM系统
    private
    function syncToCRMSystem($appoint = array())
    {
        $grade = explode(",", $appoint['grade_level']);
        $param = null;
        $param = null;
        $buId = getBuId($_SESSION['city'], $_SESSION['buType']);
        $param = "buId=" . $buId;
        $param = $param . "&phone=" . $appoint['tel_number'];
        /*$param = $param . "&branchId=" . $appoint['branchId'];*/
        $param = $param . "&subjectName=" . $appoint['subject_name'];
        $param = $param . "&gradeName=" . $grade[0];
        $param = $param . "&pwd=" . $this->getSecretStr();
        $param = $param . "&call=" . $appoint['call_name'];
        $param = $param . "&note=" . $appoint['remark'];
        $rst = getPostRst(C('SEND_CRM_URL'), $param);
        return $rst;

    }

    //返回密钥
    function getSecretStr()
    {
        return base64_encode(C('BASECODE_KEY'));
    }

}