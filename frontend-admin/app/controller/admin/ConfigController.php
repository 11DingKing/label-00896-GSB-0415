<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use think\facade\View;
use app\model\SystemConfig;

/**
 * 后台系统配置控制器
 */
class ConfigController extends BaseController
{
    protected $middleware = ['admin', 'log'];

    /**
     * 配置页面
     */
    public function index()
    {
        $group = $this->request->get('group', 'basic');

        $configs = SystemConfig::getGroupConfig($group);

        View::assign([
            'configs' => $configs,
            'group'   => $group,
        ]);

        return View::fetch('config/index');
    }

    /**
     * 保存配置
     */
    public function save()
    {
        $group = $this->request->post('group', 'basic');
        $data = $this->request->post('config', []);

        if (empty($data)) {
            return $this->error('没有要保存的配置');
        }

        SystemConfig::saveConfig($group, $data);

        return $this->success('保存成功');
    }
}
