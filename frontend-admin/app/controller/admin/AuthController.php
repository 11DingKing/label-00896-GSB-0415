<?php
declare(strict_types=1);

namespace app\controller\admin;

use app\controller\BaseController;
use app\service\AuthService;
use think\facade\View;
use think\facade\Session;
use think\facade\Cache;

/**
 * 后台认证控制器
 */
class AuthController extends BaseController
{
    /**
     * 登录页面
     */
    public function login()
    {
        // 生成验证码唯一标识
        $captchaKey = bin2hex(random_bytes(16));
        View::assign('captcha_key', $captchaKey);

        if ($this->request->isPost()) {
            $data = $this->request->post();

            // 验证用户名
            if (empty($data['username'])) {
                return $this->error('请输入用户名');
            }

            // 验证密码
            if (empty($data['password'])) {
                return $this->error('请输入密码');
            }

            // 执行登录
            $authService = new AuthService();
            $result = $authService->login($data['username'], $data['password']);

            if ($result['code'] == 1) {
                return $this->success($result['msg'], ['url' => (string)url('/admin/dashboard')]);
            }

            return $this->error($result['msg']);
        }

        return View::fetch('auth/login');
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $authService = new AuthService();
        $authService->logout();

        return redirect((string)url('/admin/auth/login'));
    }

    /**
     * 验证码
     */
    public function captcha()
    {
        // 获取验证码 key
        $key = $this->request->get('key', '');
        if (empty($key)) {
            $key = bin2hex(random_bytes(16));
        }

        // 生成验证码
        $code = $this->generateCaptchaCode(4);

        // 保存到缓存（5分钟有效）
        Cache::set('captcha_' . $key, strtolower($code), 300);

        // 创建验证码图片
        return $this->createCaptchaImage($code);
    }

    /**
     * 生成验证码字符
     */
    private function generateCaptchaCode(int $length = 4): string
    {
        $chars = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
     * 创建验证码图片
     */
    private function createCaptchaImage(string $code): \think\Response
    {
        $width = 130;
        $height = 50;

        // 创建图像
        $image = imagecreatetruecolor($width, $height);

        // 背景色
        $bgColor = imagecolorallocate($image, 245, 250, 255);
        imagefill($image, 0, 0, $bgColor);

        // 添加干扰线
        for ($i = 0; $i < 5; $i++) {
            $lineColor = imagecolorallocate($image, random_int(150, 200), random_int(150, 200), random_int(150, 200));
            imageline($image, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $lineColor);
        }

        // 添加干扰点
        for ($i = 0; $i < 50; $i++) {
            $pointColor = imagecolorallocate($image, random_int(100, 200), random_int(100, 200), random_int(100, 200));
            imagesetpixel($image, random_int(0, $width), random_int(0, $height), $pointColor);
        }

        // 写入验证码文字
        $fontSize = 5; // 内置字体大小
        $textColor = imagecolorallocate($image, random_int(0, 100), random_int(0, 100), random_int(0, 100));
        $textWidth = imagefontwidth($fontSize) * strlen($code);
        $x = ($width - $textWidth) / 2;
        $y = ($height - imagefontheight($fontSize)) / 2;
        imagestring($image, $fontSize, (int)$x, (int)$y, $code, $textColor);

        // 输出图像
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return response($imageData, 200, ['Content-Type' => 'image/png']);
    }
}
