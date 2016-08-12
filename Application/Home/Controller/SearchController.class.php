<?php
namespace Home\Controller;

use Think\Controller;

header("content-type:text/html;charset=utf-8");

/**
 * 搜索控制器
 * @package Home\Controller
 */
class SearchController extends CommonController
{

    public function search()
    {
        $keywords = $_GET['keywords'];

        if (!empty($keywords)) {
            $this->searchTeacher($keywords);
            $this->searchCourse($keywords);
            $this->searchSchool($keywords);
            $this->searchArticle($keywords);
            $this->assign('result', array_merge($this->teacherInfo, $this->courseInfo, $this->schoolInfo, $this->articleInfo));
        }

        $this->assign('keywords', $keywords);
        $this->assign('title', '快乐学习官网|搜索');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->display();
    }

    //高亮显示函数
    private function bat_highlight($message, $words, $color = '#ff0000')
    {
        if (!empty($words)) {
            $highlightarray = explode('+', $words);
            $sppos = strrpos($message, chr(0) . chr(0) . chr(0));
            if ($sppos !== FALSE) {
                $specialextra = substr($message, $sppos + 3);
                $message = substr($message, 0, $sppos);
            }
            $message = preg_replace(array("/(^|>)([^<]+)(?=<|$)/sUe", "/<highlight>(.*)<\/highlight>/siU"), array("\highlight('\\2', \$highlightarray, '\\1')", "<strong><font color=\"$color\">\\1</font></strong>"), $message);
            if ($sppos !== FALSE) {
                $message = $message . chr(0) . chr(0) . chr(0) . $specialextra;
            }
        }
        return $message;
    }

    private function highlight($text, $words, $prepend)
    {
        $text = str_replace('\"', '"', $text);
        foreach ($words AS $key => $replaceword) {
            $text = str_replace($replaceword, '<highlight>' . $replaceword . '</highlight>', $text);
        }
        return "$prepend$text";
    }

    private function searchTeacher($keywords)
    {
        $teacher = M('teacherInfo')->alias('t');
        $cityId = $_SESSION['cityInfo']['cityId'];
        $field = array('CONCAT(\'【老师】\',TEACHER_NAME) NAME,id,\'1\' type ', 'path_picture');
        $where = array();
        $where['CITY_ID'] = $cityId;
        $where['is_public'] = '1';
        $where['STATUS'] = '1';
        $where['TEACHER_NAME'] = array('LIKE', '%' . $keywords . '%');
        $this->teacherInfo = $teacher->field($field)->where($where)->select();
    }

    private function searchCourse($keywords)
    {
        $course = M('course')->alias('t');
        $cityId = $_SESSION['cityInfo']['cityId'];
        $field = array('CONCAT(\'【课程】\',display_name) name,id,\'2\' type', 'path_picture');
        $where = array();
        $where['CITY_ID'] = $cityId;
        $where['is_public'] = '1';
        $where['STATUS'] = '1';
        $where['display_name'] = array('LIKE', '%' . $keywords . '%');
        $this->courseInfo = $course->field($field)->where($where)->select();
    }

    private function searchSchool($keywords)
    {
        $school = M('organizationInfo')->alias('t');
        $cityId = $_SESSION['cityInfo']['cityId'];
        $field = array('CONCAT(\'【校区】\',display_name) name,id,\'3\' type', 'path_picture');
        $where = array();
        $where['ORG_TYPE'] = '4';
        $where['STATUS'] = '1';
        $where['PARENT_ID'] = array('neq', '100000042');
        $subQuery = M('organizationInfo')->alias('a')->join('left  join ent_pictures b on a.id=b.branch_id')
            ->field('a.id')->where(array('a.parent_id' => $cityId))->buildSql();
        $where2 = 'PARENT_ID in' . $subQuery;
        $where['display_name'] = array('LIKE', '%' . $keywords . '%');
        $this->schoolInfo = $school->field($field)->where($where2)->where($where)->select();
    }

    private function searchArticle($keywords)
    {
        $article = M('article')->alias('t');
        $article->join('left join ent_organization_info a on t.bu_id=a.ID ');
        $cityId = $_SESSION['cityInfo']['cityId'];
        $field = array('CONCAT(\'【资讯】\',t.title) name,t.id,\'4\' type', 't.path_picture');
        $where = array();
        $where['a.PARENT_ID'] = $cityId;
        $where['is_public'] = '1';
        $where['a.STATUS'] = '1';
        $where['t.STATUS'] = '1';
        $where['title'] = array('LIKE', '%' . $keywords . '%');
        $this->articleInfo = $article->field($field)->where($where)->select();
    }


}