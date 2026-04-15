<?php
/**
 * 路由配置
 */

use think\facade\Route;
use think\middleware\SessionInit;

// 前台路由
Route::get('/', 'Index/index');
Route::get('/about', 'Index/about');
Route::get('/contact', 'Index/contact');
Route::post('/message', 'Index/message');

// 新闻路由
Route::get('/news', 'NewsController/index');
Route::get('/news/:id', 'NewsController/detail')->pattern(['id' => '\d+']);

// 产品路由
Route::get('/product', 'ProductController/index');
Route::get('/product/:id', 'ProductController/detail')->pattern(['id' => '\d+']);

// 案例路由
Route::get('/case', 'CaseController/index');
Route::get('/case/:id', 'CaseController/detail')->pattern(['id' => '\d+']);

// 后台所有路由
Route::group('admin', function () {
    // 认证（不需要登录验证）
    Route::get('auth/login', 'admin.AuthController/login');
    Route::post('auth/login', 'admin.AuthController/login');
    Route::get('auth/logout', 'admin.AuthController/logout');
    Route::get('auth/captcha', 'admin.AuthController/captcha');
})->middleware(SessionInit::class);

Route::group('admin', function () {
    // 仪表盘
    Route::get('dashboard', 'admin.DashboardController/index');
    Route::get('/', 'admin.DashboardController/index');

    // 用户管理
    Route::get('user', 'admin.UserController/index');
    Route::get('user/create', 'admin.UserController/create');
    Route::post('user/save', 'admin.UserController/save');
    Route::get('user/edit/:id', 'admin.UserController/edit');
    Route::post('user/update/:id', 'admin.UserController/update');
    Route::post('user/delete/:id', 'admin.UserController/delete');
    Route::post('user/status/:id', 'admin.UserController/status');

    // 角色管理
    Route::get('role', 'admin.RoleController/index');
    Route::get('role/create', 'admin.RoleController/create');
    Route::post('role/save', 'admin.RoleController/save');
    Route::get('role/edit/:id', 'admin.RoleController/edit');
    Route::post('role/update/:id', 'admin.RoleController/update');
    Route::post('role/delete/:id', 'admin.RoleController/delete');
    Route::get('role/permission/:id', 'admin.RoleController/permission');
    Route::post('role/permission/:id', 'admin.RoleController/permission');

    // 新闻管理
    Route::get('news', 'admin.NewsController/index');
    Route::get('news/create', 'admin.NewsController/create');
    Route::post('news/save', 'admin.NewsController/save');
    Route::get('news/edit', 'admin.NewsController/edit');
    Route::get('news/get/:id', 'admin.NewsController/get');
    Route::post('news/update', 'admin.NewsController/update');
    Route::post('news/delete/:id', 'admin.NewsController/delete');
    Route::post('news/status/:id', 'admin.NewsController/status');

    // 产品管理
    Route::get('product', 'admin.ProductController/index');
    Route::get('product/create', 'admin.ProductController/create');
    Route::post('product/save', 'admin.ProductController/save');
    Route::get('product/edit', 'admin.ProductController/edit');
    Route::get('product/get/:id', 'admin.ProductController/get');
    Route::post('product/update', 'admin.ProductController/update');
    Route::post('product/delete/:id', 'admin.ProductController/delete');
    Route::post('product/status/:id', 'admin.ProductController/status');

    // 案例管理
    Route::get('case', 'admin.CaseController/index');
    Route::get('case/create', 'admin.CaseController/create');
    Route::post('case/save', 'admin.CaseController/save');
    Route::get('case/edit', 'admin.CaseController/edit');
    Route::get('case/get/:id', 'admin.CaseController/get');
    Route::post('case/update', 'admin.CaseController/update');
    Route::post('case/delete/:id', 'admin.CaseController/delete');
    Route::post('case/status/:id', 'admin.CaseController/status');

    // 分类管理
    Route::get('category', 'admin.CategoryController/index');
    Route::get('category/create', 'admin.CategoryController/create');
    Route::post('category/save', 'admin.CategoryController/save');
    Route::get('category/edit/:id', 'admin.CategoryController/edit');
    Route::post('category/update/:id', 'admin.CategoryController/update');
    Route::post('category/delete/:id', 'admin.CategoryController/delete');

    // 单页管理
    Route::get('page', 'admin.PageController/index');
    Route::get('page/create', 'admin.PageController/create');
    Route::post('page/save', 'admin.PageController/save');
    Route::get('page/edit/:id', 'admin.PageController/edit');
    Route::post('page/update/:id', 'admin.PageController/update');
    Route::post('page/delete/:id', 'admin.PageController/delete');

    // 留言管理
    Route::get('message', 'admin.MessageController/index');
    Route::get('message/view', 'admin.MessageController/view');
    Route::post('message/reply/:id', 'admin.MessageController/reply');
    Route::post('message/delete/:id', 'admin.MessageController/delete');
    Route::post('message/batch-delete', 'admin.MessageController/batchDelete');
    Route::post('message/mark-read', 'admin.MessageController/markRead');

    // 系统配置
    Route::get('config', 'admin.ConfigController/index');
    Route::post('config/save', 'admin.ConfigController/save');

    // 操作日志
    Route::get('log', 'admin.LogController/index');
    Route::post('log/clear', 'admin.LogController/clear');
    Route::post('log/clear-expired', 'admin.LogController/clearExpired');

    // 上传
    Route::post('upload/image', 'admin.UploadController/image');
    Route::post('upload/file', 'admin.UploadController/file');
})->middleware([SessionInit::class, 'admin']);
