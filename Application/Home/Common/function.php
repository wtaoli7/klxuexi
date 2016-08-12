<?php
/**
 * Created by PhpStorm.
 * User: Entstudy
 * Date: 2016/4/8
 * Time: 10:45
 */


/**
 * 根据访问客户端IP，获取默认城市
 * @return array
 */
function getDefaultCity()
{
    $cityMap = array( //
        "厦门" => "xiamen",
        "福州" => "fuzhou",
        "泉州" => "quanzhou",
        "南昌" => "nanchang",
        "合肥" => "hefei",
    );

    $client_ip = get_client_ip();
    $Ip = new \Org\Net\IpLocation('UTFWry.dat'); // 实例化类 参数表示Ip地址库文件
    $area = $Ip->getlocation($client_ip); // 获取某个Ip地址所在的位置
    $countryArea = $area['country'] . $area['area'];
    $defaultCity = '厦门';
    $defaultCityCode = 'xiamen';
    foreach ($cityMap as $city => $cityCode) {
        if (strstr($countryArea, $city)) {
            $defaultCity = $city;
            $defaultCityCode = $cityCode;
            break;
        }
    }
    return array('cityName' => $defaultCity, 'cityCode' => $defaultCityCode);
}


/**
 * 基础分页的相同代码封装，使前台的代码更少
 * @param $count 要分页的总记录数
 * @param int $pagesize 每页查询条数
 * @return \Think\Page
 */
function getpage($count, $pagesize = 10)
{
    $p = new \Think\Page($count, $pagesize);
    $p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev', '上一页');
    $p->setConfig('next', '下一页');
    $p->setConfig('last', '末页');
    $p->setConfig('first', '首页');
    $p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
    $p->lastSuffix = false;//最后一页不显示为总页数
    return $p;
}

;
/**
 * 基础分页的相同代码封装，使前台的代码更少
 * @param $count 要分页的总记录数
 * @param int $pagesize 每页查询条数
 * @return \Think\Page
 */
function getSimplePage($count, $pagesize = 10)
{
    $p = new \Think\Page($count, $pagesize);
    $p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev', '上一页');
    $p->setConfig('next', '下一页');
    $p->setConfig('last', '末页');
    $p->setConfig('first', '首页');
    $p->setConfig('theme', '%FIRST%%LINK_PAGE%');
    $p->lastSuffix = false;//最后一页不显示为总页数
    return $p;
}

/**
 *
 *根据团队Id，获取团队的有效科目
 */
function getEnableSubject($buId)
{
    $subjectInfo = F('subjectInfo_' . $buId);
    if (!$subjectInfo) {
        $dictBuRef = M('dictBuRef')->alias('a');
        $dictBuRef->join(' left join ent_subject b on a.DICT_ID=b.ID');
        $where = array(
            'a.DICT_TYPE' => 'tp_subject',
            'b.STATUS' => '1',
            'a.BU_ID' => $buId,
        );
        $field = array(
            'b.id',
            'b.display_name'
        );
        $dictBuRef->field($field)->where($where);
        $subjectInfo = $dictBuRef->select();
        F('subjectInfo_' . $buId, $subjectInfo);
    }
    return $subjectInfo;
}

;

/**
 * 根据团队Id，获取团队的年级
 */
function getEnableGrade($buId)
{
    $gradeInfo = F('gradeInfo_' . $buId);
    if (!$gradeInfo) {
        $dictBuRef = M('dictBuRef')->alias('a');
        $dictBuRef->join(' left join ent_grade b on a.DICT_ID=b.ID');
        $where = array(
            'a.DICT_TYPE' => 'bu_grade_rel',
            'b.STATUS' => '1',
            'a.BU_ID' => $buId,
        );
        $field = array(
            'b.id',
            'b.display_name'
        );
        $dictBuRef->field($field)->where($where);
        $gradeInfo = $dictBuRef->select();
        F('gradeInfo_' . $buId, $gradeInfo);
    }
    return $gradeInfo;
}

;


/**
 * 根据团队Id，获取团队的校区
 */
function getEnableBranch($buId)
{
    $branchInfo = F('branchInfo_' . $buId);
    if (!$branchInfo) {
        $branch = M('organizationInfo')->alias('o');
        $where = array(
            'o.PARENT_ID' => $buId,
            'o.STATUS' => '1',
        );
        $field = array(
            'o.id',
            'o.display_name',
            'o.address',
            'o.org_name',
            'o.telephone',
            'o.position_x',
            'o.position_y',
        );
        $branch->field($field)->where($where);
        $branchInfo = $branch->select();
        F('branchInfo_' . $buId, $branchInfo);
    }
    return $branchInfo;
}

;


/**
 *  获取地区联系方式
 */
function contactNumber()
{
    $cityCode = $_SESSION['cityInfo']['cityCode'];
    $buMap = array( //城市团队信息映射
        "xiamen" => "精品小班：0592-2042086,2042053，1对1辅导：0592-2042000",
        "fuzhou" => "精品小班：0591-88086086，1对1辅导：0591-85398880",
        "quanzhou" => "精品小班：0595-28092888、28092988，1对1辅导：0595-28092886、28092998",
        "hefei" => "联系电话：400-0551-000",
        "nanchang" => "联系电话：0791-86297777、82219620",
    );
    return !$buMap[$cityCode] ? '0592-2042666' : $buMap[$cityCode];
}

;

function getURL($type, $id)
{
    if ($type === '1') {
        echo U('Teacher/showTeacherDetail', array('teacherId' => $id));
    }
    if ($type === '2') {
        echo U('Course/showCourseDetail', array('courseId' => $id));
    }
    if ($type === '3') {
        echo U('School/schoolDetail', array('schoolId' => $id));
    }
    if ($type === '4') {
        $article = M('article')->alias('a');
        $data = $article->where(array('a.id' => $id))->find();
        if (strpos($data['link_address'], 'http') !== false) {
            echo $data['link_address'];
        } else {
            echo U('Article/latestNewsDetail', array('articleId' => $id));
        }

    }

}

;


/**
 * 根据城市编码，获取团队ID
 * @param $cityCode  默认使用厦门
 * @return string
 */

function getBuId($cityCode = 'xiamen', $buType = 'Dxb')
{
    $buMap = array( //城市团队信息映射
        "xiamenDxb" => "11",
        "fuzhouDxb" => "17",
        "quanzhouDxb" => "26",
        "hefeiDxb" => "29",
        "nanchangDxb" => "22",
        "xiamenJiayin" => "100000042",
    );
    return !$buMap[$cityCode . $buType] ? '11' : $buMap[$cityCode . $buType];
}

;
//将数组进行拆分
function getSplitAry($array = array(), $showCount = 4)
{
    $start = 0;
    $lists = array();
    $num = count($array) - 1;
    do {
        $lists[] = array_slice($array, $start, $showCount);
        $start += $showCount;
    } while (($start + $showCount) <= $num);
    if ($num - ($start - 1) >= 1)
        $lists[] = array_slice($array, $start, $num - ($start - 1));
    return $lists;
}

;

/**
 * Post请求
 * @param $url 请求的路径
 * @param array $param 请求的参数
 * @return JSON
 */
function getPostRst($url, $param = array())
{
    $rst = null;
    try {
        $httph = curl_init($url);
        curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($httph, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0(compatible;MSIE6.0;windowsNT5.0)");
        curl_setopt($httph, CURLOPT_POST, true);
        curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
        curl_setopt($httph, CURLOPT_RETURNTRANSFER, true);
        $rst = curl_exec($httph);
        curl_close($httph);
        return $rst;
    } catch (Exception $e) {
        return '{"code":"-1","msg":"erro"}';
    }
    return $rst;

}


/**
 * 生成6位数验证码
 * @param int $length 生成的长度
 * @return string
 */
function generate_code($length = 6)
{
    return str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}