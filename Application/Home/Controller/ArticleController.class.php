<?php
namespace Home\Controller;

use Think\Controller;

header("content-type:text/html;charset=utf-8");

class ArticleController extends CommonController
{

    //学校介绍首页
    public function latestNews()
    {
        $article = M('article')->alias('ent');
        $article = $article->join('left join ent_organization_info org on ent.bu_id=org.id ');
        $where['ent.type'] = array('NOT IN', '4,5，7,8,9');
        $where['ent.status'] = '1';
        $where['ent.is_public'] = '1';
        $where['ent.link_address'] = array('NEQ', '');
        $where['org.parent_id'] = $_SESSION['cityInfo']['cityId'];
        $count = $article->where($where)->count();// 查询满足要求的总记录数
        $page = getpage($count, 6);
        $article = M('article')->alias('ent');
        $article = $article->join('left join ent_article_type a on ent.type=a.id');
        $article = $article->join('left join ent_organization_info org on ent.bu_id=org.id ');
        $data = $article->field(array('ent.summary', 'ent.id as articleid', 'DATE_FORMAT(ent.create_time,\'%Y-%c-%d \') create_time', 'ent.title', 'a.name', 'ent.path_picture', 'a.id as typeid', 'ent.link_address', 'ent.hits'))->where($where)->order('ent.id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        $show = $page->show();// 分页显示输出
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('latestNews', $data);
        //记录当前操作url，切换城市时，重定向到该地址
        session('curUrl', __ACTION__);
        $this->assign('title', '快乐学习官网|最新资讯');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->display();
    }

    //学校介绍首页
    public function latestNewsDetail()
    {
        $articleId = I('get.articleId');
        $article = M('article');
        $where['id'] = $articleId;
        $detail = $article->where($where)->find();
        $article->where($where)->setInc('hits');
        $this->assign('title', '快乐学习官网|最新资讯');
        $this->assign('cityName', session('cityName'));
        $this->assign('cityCode', session('cityCode'));
        $this->assign('detail', $detail);
        $this->display();
    }

}