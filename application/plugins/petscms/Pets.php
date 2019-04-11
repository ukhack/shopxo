<?php
// +----------------------------------------------------------------------
// | ShopXO 国内领先企业级B2C免费开源电商系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011~2019 http://shopxo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Devil
// +----------------------------------------------------------------------
namespace app\plugins\petscms;

use think\Controller;
use app\plugins\petscms\Service;
use app\service\PluginsService;
use app\service\UserService;

/**
 * 宠物管理系统 - 用户宠物管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class Pets extends Controller
{
    private $user;

    /**
     * 构造方法
     * @author   Devil
     * @blog    http://gong.gg/
     * @version 1.0.0
     * @date    2019-03-15
     * @desc    description
     */
    public function __construct()
    {
        parent::__construct();

        $this->user = UserService::LoginUserInfo();
    }

    /**
     * 我的宠物
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function index($params = [])
    {
        // 参数
        $params = input();
        $params['user'] = $this->user;

        // 分页
        $number = 10;

        // 条件
        $where = Service::PetsListWhere($params);

        // 获取总数
        $total = Service::PetsTotal($where);

        // 分页
        $page_params = array(
                'number'    =>  $number,
                'total'     =>  $total,
                'where'     =>  $params,
                'page'      =>  isset($params['page']) ? intval($params['page']) : 1,
                'url'       =>  PluginsHomeUrl('petscms', 'pets', 'index'),
            );
        $page = new \base\Page($page_params);
        $this->assign('page_html', $page->GetPageHtml());

        // 获取列表
        $data_params = array(
            'm'         => $page->GetPageStarNumber(),
            'n'         => $number,
            'where'     => $where,
        );
        $data = Service::PetsList($data_params);
        $this->assign('data_list', $data['data']);
        //print_r($data['data']);

        $this->assign('pets_attribute_is_text_list', Service::$pets_attribute_is_text_list);
        $this->assign('pets_attribute_gender_list', Service::$pets_attribute_gender_list);
        $this->assign('pets_attribute_type_list', Service::$pets_attribute_type_list);
        return $this->fetch('../../../plugins/view/petscms/pets/index');
    }

    /**
     * 宠物添加/编辑页面
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function saveinfo($params = [])
    {
        $this->assign('data', []);
        $this->assign('pets_attribute_is_text_list', Service::$pets_attribute_is_text_list);
        $this->assign('pets_attribute_gender_list', Service::$pets_attribute_gender_list);
        $this->assign('pets_attribute_type_list', Service::$pets_attribute_type_list);
        return $this->fetch('../../../plugins/view/petscms/pets/saveinfo');
    }

    /**
     * 宠物添加/编辑
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2019-03-15T23:51:50+0800
     * @param   [array]          $params [输入参数]
     */
    public function save($params = [])
    {
        // 是否ajax请求
        if(!IS_AJAX)
        {
            return $this->error('非法访问');
        }

        // 用户
        $params['user'] = $this->user;
        return Service::PestSave($params);
    }
}
?>