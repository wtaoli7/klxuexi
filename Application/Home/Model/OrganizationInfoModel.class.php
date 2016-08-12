<?php


namespace Home\Model;

use Think\Model;

/**
 * Created by PhpStorm.
 * User: Entstudy
 * Date: 2016/6/29
 * Time: 11:42
 */
class OrganizationInfoModel extends Model
{


    /**
     * 获取地区下的校区信息
     * @param $cityId  城市ID
     * @param array $field 字段
     * @param string $productLine 产品线ID：1：大小班，2：个性化
     * @param string $order 排序使用
     * @param string $limit 分页查询使用
     * @return mixed
     */
    public function queryCitySchool($cityId, $field = array(), $productLine = '1,2', $order = '', $limit = '')
    {
        $whereSql = "status=1 and parent_id !=100000042 and parent_id in(select o.id from ent_organization_info o where o.parent_id=" . $cityId . " and o.product_line in(" . $productLine . "))";
        $school = $this->where($whereSql)->field($field);
        if (!empty($order)) {
            $school->order($order);
        }
        if (!empty($limit)) {
            $school->limit($limit);
        }
        return $school->select();
    }

    /**
     * 获取地区下的校区数量
     * @param $cityId  城市ID
     * @param array $field 字段
     * @param string $productLine 产品线ID：1：大小班，2：个性化
     * @return mixed
     */
    public function queryCitySchoolCount($cityId, $productLine = '1,2')
    {
        $whereSql = "status=1 and parent_id !=100000042 and parent_id in(select o.id from ent_organization_info o where o.parent_id=" . $cityId . " and o.product_line in(" . $productLine . "))";
        return $this->where($whereSql)->count();
    }

}