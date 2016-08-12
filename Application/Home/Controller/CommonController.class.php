<?php
namespace Home\Controller;

use Think\Controller;

header("content-type:text/html;charset=utf-8");

class CommonController extends Controller
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

    //初始化
    public function _initialize()
    {
        //如果没有城市信息，设置默认城市
        if (!session('?cityCode') || !session('?cityInfo')) {
            $defaultCity = getDefaultCity();
            session('cityInfo', $this->cityMap[$defaultCity['cityCode']]);
            session('cityName', $defaultCity['cityName']);
            session('cityCode', $defaultCity['cityCode']);
        }

    }

}