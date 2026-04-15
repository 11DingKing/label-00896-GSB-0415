<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use app\service\UploadService;

/**
 * 后台上传控制器
 */
class UploadController extends BaseController
{
    protected $middleware = ['admin'];

    /**
     * 上传图片
     */
    public function image()
    {
        $file = $this->request->file('file');
        if (!$file) {
            return $this->error('请选择要上传的图片');
        }

        $uploadService = new UploadService();
        $result = $uploadService->uploadImage($file);

        if ($result['code'] == 1) {
            return $this->success($result['msg'], $result['data']);
        }

        return $this->error($result['msg']);
    }

    /**
     * 上传文件
     */
    public function file()
    {
        $file = $this->request->file('file');
        if (!$file) {
            return $this->error('请选择要上传的文件');
        }

        $uploadService = new UploadService();
        $result = $uploadService->uploadFile($file);

        if ($result['code'] == 1) {
            return $this->success($result['msg'], $result['data']);
        }

        return $this->error($result['msg']);
    }
}
