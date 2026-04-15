<?php
declare(strict_types=1);

namespace app\service;

use app\model\AdminUser;
use think\facade\Session;
use think\facade\Cache;

/**
 * 认证服务
 */
class AuthService
{
    /**
     * 登录失败次数限制
     */
    protected int $maxAttempts = 5;

    /**
     * 锁定时间（秒）
     */
    protected int $lockTime = 900;

    /**
     * 登录验证
     */
    public function login(string $username, string $password): array
    {
        // 检查是否被锁定
        if ($this->isLocked($username)) {
            $remaining = $this->getLockRemainingTime($username);
            return ['code' => 0, 'msg' => "账号已被锁定，请{$remaining}分钟后再试"];
        }

        // 查找用户
        $user = AdminUser::where('username', $username)->find();

        if (!$user) {
            $this->incrementAttempts($username);
            return ['code' => 0, 'msg' => '用户名或密码错误'];
        }

        // 验证密码
        if (!$user->verifyPassword($password)) {
            $this->incrementAttempts($username);
            $remaining = $this->maxAttempts - $this->getAttempts($username);
            return ['code' => 0, 'msg' => "用户名或密码错误，还剩{$remaining}次机会"];
        }

        // 检查状态
        if ($user->status != 1) {
            return ['code' => 0, 'msg' => '账号已被禁用'];
        }

        // 清除登录失败记录
        $this->clearAttempts($username);

        // 更新登录信息
        $user->updateLoginInfo();

        // 获取权限
        $permissions = $user->getPermissions();

        // 获取用户数据（不包含隐藏字段）
        $userData = $user->toArray();
        
        // 存储Session
        Session::set('admin_user', $userData);
        Session::set('admin_permissions', $permissions);
        Session::set('admin_login_time', time());

        return ['code' => 1, 'msg' => '登录成功'];
    }

    /**
     * 退出登录
     */
    public function logout(): void
    {
        Session::delete('admin_user');
        Session::delete('admin_permissions');
    }

    /**
     * 获取当前用户
     */
    public function getCurrentUser(): ?array
    {
        return Session::get('admin_user');
    }

    /**
     * 检查是否被锁定
     */
    protected function isLocked(string $username): bool
    {
        $lockKey = 'login_lock_' . $username;
        return Cache::has($lockKey);
    }

    /**
     * 获取锁定剩余时间
     */
    protected function getLockRemainingTime(string $username): int
    {
        $lockKey = 'login_lock_' . $username;
        $lockTime = Cache::get($lockKey, 0);
        $remaining = $lockTime - time();
        return max(1, (int)ceil($remaining / 60));
    }

    /**
     * 增加失败次数
     */
    protected function incrementAttempts(string $username): void
    {
        $key = 'login_attempts_' . $username;
        $attempts = Cache::get($key, 0) + 1;
        Cache::set($key, $attempts, $this->lockTime);

        // 达到限制次数，锁定账号
        if ($attempts >= $this->maxAttempts) {
            $lockKey = 'login_lock_' . $username;
            Cache::set($lockKey, time() + $this->lockTime, $this->lockTime);
        }
    }

    /**
     * 获取失败次数
     */
    protected function getAttempts(string $username): int
    {
        $key = 'login_attempts_' . $username;
        return (int) Cache::get($key, 0);
    }

    /**
     * 清除失败记录
     */
    protected function clearAttempts(string $username): void
    {
        Cache::delete('login_attempts_' . $username);
        Cache::delete('login_lock_' . $username);
    }
}
