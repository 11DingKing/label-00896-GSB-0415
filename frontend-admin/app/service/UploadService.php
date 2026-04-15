<?php
declare(strict_types=1);

namespace app\service;

use think\facade\Filesystem;
use think\File;

/**
 * 上传服务
 */
class UploadService
{
    /**
     * 允许的图片类型
     */
    protected array $allowImageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /**
     * 允许的文件类型
     */
    protected array $allowFileTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar'];

    /**
     * 最大图片大小 (2MB)
     */
    protected int $maxImageSize = 2097152;

    /**
     * 最大文件大小 (10MB)
     */
    protected int $maxFileSize = 10485760;

    /**
     * 上传图片
     */
    public function uploadImage(File $file): array
    {
        // 验证文件类型
        $ext = strtolower($file->extension());
        if (!in_array($ext, $this->allowImageTypes)) {
            return ['code' => 0, 'msg' => '不支持的图片格式'];
        }

        // 验证文件大小
        if ($file->getSize() > $this->maxImageSize) {
            return ['code' => 0, 'msg' => '图片大小不能超过2MB'];
        }

        try {
            $saveName = Filesystem::disk('public')->putFile('images/' . date('Ymd'), $file);
            $url = '/uploads/' . $saveName;

            return [
                'code' => 1,
                'msg'  => '上传成功',
                'data' => [
                    'url'  => $url,
                    'name' => $file->getOriginalName(),
                    'size' => $file->getSize(),
                ]
            ];
        } catch (\Exception $e) {
            return ['code' => 0, 'msg' => '上传失败：' . $e->getMessage()];
        }
    }

    /**
     * 上传文件
     */
    public function uploadFile(File $file): array
    {
        // 验证文件类型
        $ext = strtolower($file->extension());
        if (!in_array($ext, $this->allowFileTypes)) {
            return ['code' => 0, 'msg' => '不支持的文件格式'];
        }

        // 验证文件大小
        if ($file->getSize() > $this->maxFileSize) {
            return ['code' => 0, 'msg' => '文件大小不能超过10MB'];
        }

        try {
            $saveName = Filesystem::disk('public')->putFile('files/' . date('Ymd'), $file);
            $url = '/uploads/' . $saveName;

            return [
                'code' => 1,
                'msg'  => '上传成功',
                'data' => [
                    'url'  => $url,
                    'name' => $file->getOriginalName(),
                    'size' => $file->getSize(),
                ]
            ];
        } catch (\Exception $e) {
            return ['code' => 0, 'msg' => '上传失败：' . $e->getMessage()];
        }
    }

    /**
     * 删除文件
     */
    public function deleteFile(string $path): bool
    {
        $fullPath = public_path() . ltrim($path, '/');
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}
