<?php /*a:2:{s:104:"/Users/ding/Documents/GSB 3/4.15/label-00896-GSB-0415/frontend-admin/app/view/admin/dashboard/index.html";i:1776232727;s:109:"/Users/ding/Documents/GSB 3/4.15/label-00896-GSB-0415/frontend-admin/app/view/admin/../admin/layout/base.html";i:1776232727;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>仪表盘 - <?php echo htmlentities((string) $site_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
            --primary-color: #1890ff;
            --sidebar-bg: #001529;
            --sidebar-text: rgba(255,255,255,0.65);
            --sidebar-active: #1890ff;
        }

        * { box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            background-color: #f0f2f5;
            min-height: 100vh;
        }

        /* 侧边栏 */
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-logo {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 12px 24px;
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo h1 {
            color: #fff;
            font-size: 18px;
            margin: 0;
            font-weight: 600;
        }

        .sidebar-menu {
            padding: 16px 0;
        }

        .menu-item {
            display: block;
            padding: 12px 24px;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: all 0.3s;
        }

        .menu-item:hover, .menu-item.active {
            color: #fff;
            background: var(--sidebar-active);
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 16px;
        }

        .menu-group-title {
            padding: 12px 24px 8px;
            color: rgba(255,255,255,0.35);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submenu {
            background: rgba(0,0,0,0.2);
        }

        .submenu .menu-item {
            padding-left: 50px;
            font-size: 13px;
        }

        /* 主内容区 */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .admin-header {
            height: var(--header-height);
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-content {
            padding: 24px;
        }

        /* 卡片 */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 16px 24px;
            font-weight: 600;
        }

        .card-body {
            padding: 24px;
        }

        /* 表格 */
        .table {
            margin-bottom: 0;
        }

        .table th {
            background: #fafafa;
            font-weight: 600;
            border-bottom: 1px solid #f0f0f0;
            padding: 12px 16px;
        }

        .table td {
            padding: 12px 16px;
            vertical-align: middle;
        }

        /* 按钮 */
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: #40a9ff;
            border-color: #40a9ff;
        }

        /* 表单 */
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border-radius: 6px;
            padding: 8px 12px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(24,144,255,0.1);
        }

        /* 表单验证错误样式 */
        .form-group-wrapper {
            position: relative;
            padding-bottom: 20px;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #ff4d4f;
            background-image: none;
        }

        .form-control.is-invalid:focus,
        .form-select.is-invalid:focus {
            border-color: #ff4d4f;
            box-shadow: 0 0 0 3px rgba(255, 77, 79, 0.1);
        }

        .invalid-feedback {
            display: none;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 12px;
            color: #ff4d4f;
        }

        .form-control.is-invalid ~ .invalid-feedback,
        .form-select.is-invalid ~ .invalid-feedback,
        .form-control.is-invalid + .invalid-feedback,
        .form-select.is-invalid + .invalid-feedback {
            display: block;
        }

        /* 分页 */
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: #262626;
            border-radius: 4px;
            margin: 0 2px;
        }

        .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* 状态标签 */
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .status-badge.success {
            background: #f6ffed;
            color: #52c41a;
            border: 1px solid #b7eb8f;
        }

        .status-badge.danger {
            background: #fff2f0;
            color: #ff4d4f;
            border: 1px solid #ffccc7;
        }

        /* 用户下拉 */
        .user-dropdown .dropdown-toggle::after {
            display: none;
        }

        .user-dropdown .dropdown-menu {
            min-width: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* 统计卡片 */
        .stat-card {
            background: #fff;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 600;
            color: #262626;
        }

        .stat-card .stat-label {
            color: #8c8c8c;
            font-size: 14px;
        }

        /* 操作按钮组 */
        .action-btns .btn {
            padding: 4px 8px;
            font-size: 13px;
        }

        /* 搜索表单 */
        .search-form {
            background: #fff;
            padding: 16px 24px;
            border-radius: 8px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .search-form .form-control,
        .search-form .form-select {
            height: 36px;
        }

        /* 图片预览 */
        .img-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #f0f0f0;
        }

        /* 面包屑 */
        .breadcrumb {
            margin-bottom: 0;
            background: transparent;
            padding: 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "/";
        }

        /* 交互过渡效果 */
        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn-primary:hover {
            box-shadow: 0 4px 12px rgba(24, 144, 255, 0.35);
        }

        .menu-item {
            transition: all 0.2s ease;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #fafafa;
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        .form-control, .form-select {
            transition: all 0.2s ease;
        }

        .page-link {
            transition: all 0.2s ease;
        }

        .page-link:hover {
            transform: translateY(-1px);
        }

        .action-btns .btn {
            transition: all 0.2s ease;
        }

        .action-btns .btn:hover {
            transform: scale(1.05);
        }

        /* Toast 动画 */
        .toast {
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* 图片预览悬停效果 */
        .img-preview {
            transition: transform 0.2s ease;
        }

        .img-preview:hover {
            transform: scale(1.1);
        }

        /* 用户头像悬停 */
        .user-dropdown .bg-primary {
            transition: all 0.2s ease;
        }

        .user-dropdown:hover .bg-primary {
            transform: scale(1.1);
        }

        /* ========== 自定义 Select 下拉框 (Element Plus 风格) ========== */
        .el-select {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .el-select__input {
            width: 100%;
            height: 38px;
            padding: 0 36px 0 15px;
            border: 1px solid #dcdfe6;
            border-radius: 8px;
            background: #fff;
            font-size: 14px;
            color: #303133;
            cursor: pointer;
            outline: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            box-sizing: border-box;
        }

        .el-select__input:hover {
            border-color: #c0c4cc;
        }

        .el-select.is-focus .el-select__input {
            border-color: var(--primary-color);
        }

        .el-select__input .el-select__text {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .el-select__input .el-select__text.is-placeholder {
            color: #a8abb2;
        }

        .el-select__arrow {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #c0c4cc;
            font-size: 12px;
            transition: transform 0.3s;
            pointer-events: none;
        }

        .el-select.is-focus .el-select__arrow {
            transform: translateY(-50%) rotate(180deg);
        }

        .el-select__dropdown {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            width: 100%;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 16px 0 rgba(0,0,0,0.08), 0 3px 6px -4px rgba(0,0,0,0.12), 0 9px 28px 8px rgba(0,0,0,0.05);
            z-index: 2000;
            padding: 8px 0;
            display: none;
            max-height: 280px;
            overflow-y: auto;
        }

        /* 三角箭头 - 向上指向 */
        .el-select__dropdown::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 50%;
            margin-left: -6px;
            width: 12px;
            height: 12px;
            background: #fff;
            border-left: 1px solid #e4e7ed;
            border-top: 1px solid #e4e7ed;
            transform: rotate(45deg);
        }

        .el-select.is-focus .el-select__dropdown {
            display: block;
            animation: elSelectDropdown 0.25s ease;
        }

        @keyframes elSelectDropdown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .el-select__option {
            padding: 12px 20px;
            font-size: 14px;
            color: #303133;
            cursor: pointer;
            transition: background 0.2s;
            line-height: 1.5;
            margin: 2px 0;
            border-radius: 4px;
        }

        .el-select__option:hover {
            background: #f5f7fa;
        }

        .el-select__option.is-selected {
            color: var(--primary-color);
            font-weight: 600;
            background: #f0f5ff;
        }

        .el-select__option.is-disabled {
            color: #c0c4cc;
            cursor: not-allowed;
        }

        .el-select__option.is-disabled:hover {
            background: transparent;
        }

        /* 隐藏原生 select */
        .el-select select.form-select {
            display: none !important;
        }

        /* 筛选卡片样式 */
        .filter-card {
            background: #fff;
            padding: 20px 24px;
            border-radius: 8px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 4px solid var(--primary-color);
            position: relative;
            z-index: 10;
            overflow: visible;
        }

        .filter-card .el-select {
            min-width: 140px;
        }

        /* 筛选栏输入框和下拉框统一样式 */
        .filter-card .form-control,
        .filter-card .form-select,
        .filter-card .el-select__input {
            font-size: 14px;
            height: 38px;
        }

        /* 输入框placeholder颜色 */
        .filter-card .form-control::placeholder {
            color: #c0c4cc;
        }

        .filter-card .form-control::-webkit-input-placeholder {
            color: #c0c4cc;
        }

        .filter-card .form-control::-moz-placeholder {
            color: #c0c4cc;
        }

        .filter-card .form-control:-ms-input-placeholder {
            color: #c0c4cc;
        }

        /* 数据卡片 */
        .data-card {
            background: #fff;
            border-radius: 8px;
            padding: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            overflow: visible;
            position: relative;
            z-index: 1;
        }

        .data-card .table {
            margin: 0;
        }

        .data-card .pagination-wrap {
            padding: 16px 24px;
            border-top: 1px solid #f0f0f0;
            position: relative;
            z-index: 100;
            overflow: visible;
        }

        /* 分页样式优化 */
        .pagination-wrap .pagination {
            margin: 0;
            gap: 4px;
        }
        .pagination-wrap .page-item .page-link {
            border: 1px solid #d9d9d9;
            color: #333;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            min-width: 32px;
            text-align: center;
        }
        .pagination-wrap .page-item .page-link:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: #fff;
        }
        .pagination-wrap .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }
        .pagination-wrap .page-item.disabled .page-link {
            color: #bfbfbf;
            background: #f5f5f5;
            border-color: #d9d9d9;
        }

        /* 分页容器不换行 */
        .pagination-wrap {
            white-space: nowrap;
        }

        .pagination-wrap > div {
            white-space: nowrap;
        }

        /* 分页文字间距 */
        .pagination-wrap .text-muted {
            margin-left: 0.5rem;
        }

        /* 状态标签 */
        .status-tag {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
        }

        .status-tag.success {
            background: #f6ffed;
            color: #52c41a;
            border: 1px solid #b7eb8f;
        }

        .status-tag.danger {
            background: #fff2f0;
            color: #ff4d4f;
            border: 1px solid #ffccc7;
        }

        .status-tag.warning {
            background: #fffbe6;
            color: #faad14;
            border: 1px solid #ffe58f;
        }

        /* 操作按钮不换行 */
        .action-btns {
            white-space: nowrap;
        }

        /* 确认弹窗 */
        .confirm-modal .modal-content {
            border-radius: 8px;
            border: none;
        }

        .confirm-modal .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        .confirm-modal .modal-body {
            text-align: center;
            padding: 24px;
        }

        .confirm-modal .modal-body .bi-exclamation-circle {
            font-size: 48px;
            color: #faad14;
            margin-bottom: 16px;
        }

        .confirm-modal .modal-footer {
            border-top: none;
            justify-content: center;
            padding-top: 0;
        }

        /* Modal body 统一内边距 */
        .modal-body {
            padding: 24px;
        }
    </style>
    
<style>
/* 欢迎横幅 */
.welcome-banner {
    background: linear-gradient(135deg, #1890ff 0%, #096dd9 50%, #0050b3 100%);
    border-radius: 12px;
    padding: 32px;
    color: #fff;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}
.welcome-banner::before {
    content: '';
    position: absolute;
    right: -50px;
    top: -50px;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}
.welcome-banner::after {
    content: '';
    position: absolute;
    right: 50px;
    bottom: -80px;
    width: 160px;
    height: 160px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}
.welcome-banner h2 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 8px;
}
.welcome-banner p {
    opacity: 0.9;
    margin: 0;
}
.welcome-banner .time-info {
    margin-top: 16px;
    font-size: 13px;
    opacity: 0.8;
}

/* 统计卡片增强 */
.stat-card-new {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border-top: 3px solid var(--primary-color);
}
.stat-card-new:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}
.stat-card-new .stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    margin-bottom: 16px;
}
.stat-card-new .stat-icon.blue { background: linear-gradient(135deg, #1890ff 0%, #096dd9 100%); }
.stat-card-new .stat-icon.green { background: linear-gradient(135deg, #52c41a 0%, #389e0d 100%); }
.stat-card-new .stat-icon.orange { background: linear-gradient(135deg, #fa8c16 0%, #d46b08 100%); }
.stat-card-new .stat-icon.red { background: linear-gradient(135deg, #ff4d4f 0%, #cf1322 100%); }
.stat-card-new .stat-icon.cyan { background: linear-gradient(135deg, #13c2c2 0%, #08979c 100%); }

.stat-card-new .stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1;
    margin-bottom: 4px;
}
.stat-card-new .stat-label {
    color: #8c8c8c;
    font-size: 14px;
    margin-bottom: 12px;
}
.stat-card-new .stat-footer {
    display: flex;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #f5f5f5;
    font-size: 13px;
}
.stat-card-new .stat-footer .trend {
    display: flex;
    align-items: center;
    margin-right: 12px;
}
.stat-card-new .stat-footer .trend.up { color: #52c41a; }
.stat-card-new .stat-footer .trend.down { color: #ff4d4f; }
.stat-card-new .stat-footer .desc { color: #8c8c8c; }

/* 快捷操作 */
.quick-actions {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    margin-bottom: 24px;
    border-top: 3px solid var(--primary-color);
}
.quick-actions h5 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #1a1a2e;
}
.quick-action-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 16px;
    border-radius: 12px;
    background: linear-gradient(135deg, #f0f7ff 0%, #e6f4ff 100%);
    transition: all 0.3s;
    text-decoration: none;
    color: #595959;
    border: 1px solid #d6e8fa;
}
.quick-action-item:hover {
    background: linear-gradient(135deg, #e6f4ff 0%, #bae0ff 100%);
    color: #1890ff;
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(24, 144, 255, 0.15);
    border-color: #91caff;
}
.quick-action-item i {
    font-size: 32px;
    margin-bottom: 10px;
    color: #1890ff;
    transition: transform 0.3s;
}
.quick-action-item:hover i {
    transform: scale(1.1);
}
.quick-action-item span {
    font-size: 13px;
    font-weight: 500;
}

/* 数据列表卡片 */
.list-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border-top: 3px solid var(--primary-color);
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.list-card .card-header {
    background: #fff;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
    font-weight: 600;
    font-size: 15px;
    display: flex;
    align-items: center;
    flex-shrink: 0;
}
.list-card .card-header i {
    margin-right: 8px;
    color: #1890ff;
}
.list-card .card-body {
    flex: 1;
    padding: 0;
    overflow: hidden;
}

/* 系统信息 */
.system-info {
    background: #fff;
    border-radius: 12px;
    padding: 0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border-top: 3px solid var(--primary-color);
    overflow: hidden;
}
.system-info .info-header {
    background: linear-gradient(135deg, #f0f7ff 0%, #e6f4ff 100%);
    padding: 20px 24px;
    border-bottom: 1px solid #e6f4ff;
}
.system-info .info-header h5 {
    font-size: 16px;
    font-weight: 600;
    margin: 0;
    color: #1a1a2e;
    display: flex;
    align-items: center;
}
.system-info .info-header h5 i {
    color: #1890ff;
    margin-right: 10px;
}
.system-info .info-body {
    padding: 24px;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.info-card {
    background: #fafafa;
    border-radius: 8px;
    padding: 16px;
    display: flex;
    align-items: center;
    transition: all 0.3s;
}
.info-card:hover {
    background: #f0f7ff;
    transform: translateX(4px);
}
.info-card .info-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, #1890ff 0%, #096dd9 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    margin-right: 12px;
    flex-shrink: 0;
}
.info-card .info-content .label {
    font-size: 12px;
    color: #8c8c8c;
    margin-bottom: 2px;
}
.info-card .info-content .value {
    font-size: 14px;
    color: #1a1a2e;
    font-weight: 500;
}
@media (max-width: 992px) {
    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 576px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

</head>
<body>
    <!-- 侧边栏 -->
    <aside class="admin-sidebar">
        <div class="sidebar-logo">
            <h1><i class="bi bi-grid-3x3-gap-fill me-2"></i>CMS管理</h1>
        </div>
        <nav class="sidebar-menu">
            <a href="<?php echo url('/admin/dashboard'); ?>" class="menu-item" data-path="dashboard">
                <i class="bi bi-speedometer2"></i>仪表盘
            </a>

            <div class="menu-group-title">内容管理</div>
            <a href="<?php echo url('/admin/news'); ?>" class="menu-item" data-path="news">
                <i class="bi bi-newspaper"></i>新闻管理
            </a>
            <a href="<?php echo url('/admin/product'); ?>" class="menu-item" data-path="product">
                <i class="bi bi-box-seam"></i>产品管理
            </a>
            <a href="<?php echo url('/admin/case'); ?>" class="menu-item" data-path="case">
                <i class="bi bi-briefcase"></i>案例管理
            </a>

            <div class="menu-group-title">系统管理</div>
            <a href="<?php echo url('/admin/message'); ?>" class="menu-item" data-path="message">
                <i class="bi bi-chat-dots"></i>留言管理
            </a>
            <a href="<?php echo url('/admin/config'); ?>" class="menu-item" data-path="config">
                <i class="bi bi-gear"></i>系统配置
            </a>
            <a href="<?php echo url('/admin/log'); ?>" class="menu-item" data-path="log">
                <i class="bi bi-clock-history"></i>操作日志
            </a>
        </nav>
    </aside>

    <!-- 主内容区 -->
    <main class="admin-main">
        <header class="admin-header">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo url('/admin/dashboard'); ?>">首页</a></li>
                        
<li class="breadcrumb-item active">仪表盘</li>

                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center">
                <a href="<?php echo url('/'); ?>" target="_blank" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="bi bi-box-arrow-up-right"></i> 访问前台
                </a>
                <div class="dropdown user-dropdown">
                    <a href="javascript:void(0)" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;">
                            <i class="bi bi-person"></i>
                        </div>
                        <span class="text-dark"><?php echo htmlentities((string) (isset($admin_user['nickname']) && ($admin_user['nickname'] !== '')?$admin_user['nickname']:$admin_user['username'])); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-danger" href="<?php echo url('/admin/auth/logout'); ?>"><i class="bi bi-box-arrow-right me-2"></i>退出登录</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="admin-content">
            
<!-- 欢迎横幅 -->
<div class="welcome-banner">
    <h2>👋 欢迎回来，<?php echo htmlentities((string) (isset($admin_user['nickname']) && ($admin_user['nickname'] !== '')?$admin_user['nickname']:$admin_user['username'])); ?>！</h2>
    <p>今天也是充满活力的一天，开始管理您的网站内容吧</p>
    <div class="time-info">
        <i class="bi bi-calendar3 me-1"></i>
        <span id="currentTime"></span>
    </div>
</div>

<!-- 统计卡片 -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card-new">
            <div class="stat-icon blue"><i class="bi bi-newspaper"></i></div>
            <div class="stat-value"><?php echo htmlentities((string) (isset($stats['news_count']) && ($stats['news_count'] !== '')?$stats['news_count']:0)); ?></div>
            <div class="stat-label">新闻总数</div>
            <div class="stat-footer">
                <span class="trend up"><i class="bi bi-arrow-up me-1"></i>12%</span>
                <span class="desc">较上月</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card-new">
            <div class="stat-icon green"><i class="bi bi-box-seam"></i></div>
            <div class="stat-value"><?php echo htmlentities((string) (isset($stats['product_count']) && ($stats['product_count'] !== '')?$stats['product_count']:0)); ?></div>
            <div class="stat-label">产品总数</div>
            <div class="stat-footer">
                <span class="trend up"><i class="bi bi-arrow-up me-1"></i>8%</span>
                <span class="desc">较上月</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card-new">
            <div class="stat-icon orange"><i class="bi bi-briefcase"></i></div>
            <div class="stat-value"><?php echo htmlentities((string) (isset($stats['case_count']) && ($stats['case_count'] !== '')?$stats['case_count']:0)); ?></div>
            <div class="stat-label">案例总数</div>
            <div class="stat-footer">
                <span class="trend up"><i class="bi bi-arrow-up me-1"></i>5%</span>
                <span class="desc">较上月</span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card-new">
            <div class="stat-icon red"><i class="bi bi-chat-dots"></i></div>
            <div class="stat-value"><?php echo htmlentities((string) (isset($stats['unread_count']) && ($stats['unread_count'] !== '')?$stats['unread_count']:0)); ?></div>
            <div class="stat-label">未读留言</div>
            <div class="stat-footer">
                <span class="desc">待处理消息</span>
            </div>
        </div>
    </div>
</div>

<!-- 快捷操作 -->
<div class="quick-actions">
    <h5><i class="bi bi-lightning-charge me-2"></i>快捷操作</h5>
    <div class="row g-3">
        <div class="col-4 col-md-2">
            <a href="<?php echo url('/admin/news'); ?>" class="quick-action-item" onclick="openAdd('news')">
                <i class="bi bi-plus-circle"></i>
                <span>发布新闻</span>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="<?php echo url('/admin/product'); ?>" class="quick-action-item">
                <i class="bi bi-box-seam"></i>
                <span>添加产品</span>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="<?php echo url('/admin/case'); ?>" class="quick-action-item">
                <i class="bi bi-briefcase"></i>
                <span>添加案例</span>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="<?php echo url('/admin/message'); ?>" class="quick-action-item">
                <i class="bi bi-envelope"></i>
                <span>查看留言</span>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="<?php echo url('/admin/banner'); ?>" class="quick-action-item">
                <i class="bi bi-image"></i>
                <span>管理Banner</span>
            </a>
        </div>
        <div class="col-4 col-md-2">
            <a href="<?php echo url('/admin/config'); ?>" class="quick-action-item">
                <i class="bi bi-gear"></i>
                <span>系统设置</span>
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- 最新留言 -->
    <div class="col-lg-6">
        <div class="list-card">
            <div class="card-header">
                <i class="bi bi-chat-square-text"></i>最新留言
                <a href="<?php echo url('/admin/message'); ?>" class="btn btn-sm btn-outline-primary ms-auto">查看全部</a>
            </div>
            <div class="card-body">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>姓名</th>
                            <th>内容</th>
                            <th width="100">时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($messages) || $messages instanceof \think\Collection || $messages instanceof \think\Paginator): $i = 0; $__LIST__ = $messages;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$msg): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><span class="fw-medium"><?php echo htmlentities((string) $msg['name']); ?></span></td>
                            <td class="text-muted"><?php echo htmlentities((string) mb_substr($msg['content'],0,25,'utf-8')); ?>...</td>
                            <td><small class="text-muted"><?php echo htmlentities((string) date('m-d H:i',!is_numeric($msg['created_at'])? strtotime($msg['created_at']) : $msg['created_at'])); ?></small></td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; if(empty($messages) || (($messages instanceof \think\Collection || $messages instanceof \think\Paginator ) && $messages->isEmpty())): ?>
                        <tr><td colspan="3" class="text-center text-muted py-4">暂无留言</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 最新新闻 -->
    <div class="col-lg-6">
        <div class="list-card">
            <div class="card-header">
                <i class="bi bi-newspaper"></i>最新新闻
                <a href="<?php echo url('/admin/news'); ?>" class="btn btn-sm btn-outline-primary ms-auto">查看全部</a>
            </div>
            <div class="card-body">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>标题</th>
                            <th width="80">浏览</th>
                            <th width="100">时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($news) || $news instanceof \think\Collection || $news instanceof \think\Paginator): $i = 0; $__LIST__ = $news;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><span class="fw-medium"><?php echo htmlentities((string) mb_substr($item['title'],0,20,'utf-8')); ?>...</span></td>
                            <td><span class="badge bg-light text-dark"><?php echo htmlentities((string) $item['views']); ?></span></td>
                            <td><small class="text-muted"><?php echo htmlentities((string) date('m-d H:i',!is_numeric($item['created_at'])? strtotime($item['created_at']) : $item['created_at'])); ?></small></td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; if(empty($news) || (($news instanceof \think\Collection || $news instanceof \think\Paginator ) && $news->isEmpty())): ?>
                        <tr><td colspan="3" class="text-center text-muted py-4">暂无新闻</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 系统信息 -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="system-info">
            <div class="info-header">
                <h5><i class="bi bi-info-circle"></i>系统信息</h5>
            </div>
            <div class="info-body">
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-box"></i></div>
                        <div class="info-content">
                            <div class="label">系统版本</div>
                            <div class="value">Enterprise CMS v1.0.0</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-filetype-php"></i></div>
                        <div class="info-content">
                            <div class="label">PHP版本</div>
                            <div class="value"><?php echo phpversion(); ?></div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-code-slash"></i></div>
                        <div class="info-content">
                            <div class="label">ThinkPHP版本</div>
                            <div class="value"><?php echo app()->version(); ?></div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-database"></i></div>
                        <div class="info-content">
                            <div class="label">数据库</div>
                            <div class="value">SQLite 3</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-pc-display"></i></div>
                        <div class="info-content">
                            <div class="label">服务器系统</div>
                            <div class="value"><?php echo php_uname('s'); ?></div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-icon"><i class="bi bi-hdd-rack"></i></div>
                        <div class="info-content">
                            <div class="label">运行环境</div>
                            <div class="value"><?php echo htmlentities((string) mb_substr((isset($_SERVER['SERVER_SOFTWARE']) && ($_SERVER['SERVER_SOFTWARE'] !== '')?$_SERVER['SERVER_SOFTWARE']:'Apache'),0,20,'utf-8')); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
    </main>

    <!-- 确认弹窗 -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:360px;">
            <div class="modal-content" style="border-radius:12px;border:none;">
                <div class="modal-body text-center py-4">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size:48px;"></i>
                    <h5 class="mt-3 mb-2" id="confirmTitle">确认操作</h5>
                    <p class="text-muted mb-0" id="confirmMessage">确定要执行此操作吗？</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmBtn">确定</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script>
        // 菜单激活状态
        (function() {
            var path = window.location.pathname;
            var menuItems = document.querySelectorAll('.sidebar-menu .menu-item');

            menuItems.forEach(function(item) {
                var dataPath = item.getAttribute('data-path');
                if (dataPath) {
                    // 检查路径是否匹配
                    if (path.indexOf('/admin/' + dataPath) !== -1 ||
                        (dataPath === 'dashboard' && (path === '/admin' || path === '/admin/' || path.indexOf('/admin/dashboard') !== -1))) {
                        item.classList.add('active');
                    }
                }
            });
        })();

        // 全局 AJAX 设置，确保携带 Cookie
        $.ajaxSetup({
            xhrFields: {
                withCredentials: true
            },
            crossDomain: false
        });
    </script>
    <script>
        // Toast 提示
        function showToast(message, type = 'success') {
            $('.custom-toast').remove();
            var bgColor = type === 'success' ? '#52c41a' : '#ff4d4f';
            var icon = type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill';
            var toast = $(`
                <div class="custom-toast" style="
                    position: fixed;
                    top: 24px;
                    left: 50%;
                    transform: translateX(-50%);
                    z-index: 9999;
                    background: #fff;
                    padding: 12px 24px;
                    border-radius: 8px;
                    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    animation: toastIn 0.3s ease;
                ">
                    <i class="bi bi-${icon}" style="color:${bgColor};font-size:18px;"></i>
                    <span style="color:#333;font-size:14px;">${message}</span>
                </div>
                <style>
                    @keyframes toastIn {
                        from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
                        to { opacity: 1; transform: translateX(-50%) translateY(0); }
                    }
                    @keyframes toastOut {
                        from { opacity: 1; transform: translateX(-50%) translateY(0); }
                        to { opacity: 0; transform: translateX(-50%) translateY(-20px); }
                    }
                </style>
            `);
            $('body').append(toast);
            setTimeout(function() {
                toast.css('animation', 'toastOut 0.3s ease forwards');
                setTimeout(function() { toast.remove(); }, 300);
            }, 2500);
        }

        // AJAX 表单提交
        function submitForm(form, callback) {
            const $form = $(form);
            const $btn = $form.find('[type="submit"]');
            const btnText = $btn.html();

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>处理中...');

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        showToast(res.msg, 'success');
                        if (callback) callback(res);
                    } else {
                        showToast(res.msg, 'error');
                    }
                },
                error: function() {
                    showToast('请求失败，请重试', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(btnText);
                }
            });
        }

        // 确认删除
        function confirmDelete(url, callback) {
            if (confirm('确定要删除吗？此操作不可恢复！')) {
                $.post(url, {}, function(res) {
                    if (res.code === 1) {
                        showToast(res.msg, 'success');
                        if (callback) callback(res);
                        else location.reload();
                    } else {
                        showToast(res.msg, 'error');
                    }
                }, 'json');
            }
        }

        // 切换状态 - 不刷新页面，只更新当前行
        function toggleStatus(url, $btn) {
            $.post(url, {}, function(res) {
                if (res.code === 1) {
                    showToast(res.msg, 'success');
                    if ($btn && $btn.length) {
                        var $row = $btn.closest('tr');
                        var $statusTag = $row.find('.status-tag');
                        // 切换状态标签
                        if ($statusTag.hasClass('success')) {
                            $statusTag.removeClass('success').addClass('danger');
                            $statusTag.text($statusTag.text().replace('已发布', '草稿').replace('上架', '下架'));
                            $btn.removeClass('btn-outline-warning').addClass('btn-outline-success');
                            $btn.text($btn.text().replace('下架', '发布').replace('下架', '上架'));
                        } else {
                            $statusTag.removeClass('danger').addClass('success');
                            $statusTag.text($statusTag.text().replace('草稿', '已发布').replace('下架', '上架'));
                            $btn.removeClass('btn-outline-success').addClass('btn-outline-warning');
                            $btn.text($btn.text().replace('发布', '下架').replace('上架', '下架'));
                        }
                    } else {
                        location.reload();
                    }
                } else {
                    showToast(res.msg, 'error');
                }
            }, 'json');
        }

        // 全局表单验证函数
        function setFieldError($input, message) {
            $input.addClass('is-invalid');
            var $feedback = $input.siblings('.invalid-feedback');
            if ($feedback.length === 0) {
                $feedback = $input.closest('.form-group-wrapper').find('.invalid-feedback');
            }
            if ($feedback.length && message) {
                $feedback.text(message);
            }
        }

        function clearFieldError($input) {
            $input.removeClass('is-invalid');
        }

        function clearAllErrors($form) {
            $form.find('.is-invalid').removeClass('is-invalid');
        }

        // 输入时清除错误
        $(document).on('input', '.form-control.is-invalid, .form-select.is-invalid', function() {
            clearFieldError($(this));
        });

        // ========== 自定义 Select 组件 ==========
        function initElSelect($select) {
            if ($select.closest('.el-select').length) return; // 已初始化

            var options = [];
            var selectedValue = $select.val();
            var selectedText = '';
            var placeholder = '请选择';

            $select.find('option').each(function() {
                var $opt = $(this);
                var val = $opt.val();
                var text = $opt.text();
                var disabled = $opt.prop('disabled');

                if (val === '' && !selectedValue) {
                    placeholder = text || '请选择';
                }

                options.push({
                    value: val,
                    text: text,
                    disabled: disabled,
                    selected: val == selectedValue
                });

                if (val == selectedValue && val !== '') {
                    selectedText = text;
                }
            });

            var $wrapper = $('<div class="el-select"></div>');
            var displayText = selectedText || placeholder;
            var textClass = selectedText ? 'el-select__text' : 'el-select__text is-placeholder';
            var $input = $('<div class="el-select__input"><span class="' + textClass + '">' + displayText + '</span></div>');
            var $arrow = $('<i class="bi bi-chevron-down el-select__arrow"></i>');
            var $dropdown = $('<div class="el-select__dropdown"></div>');

            options.forEach(function(opt) {
                var cls = 'el-select__option';
                if (opt.selected && opt.value !== '') cls += ' is-selected';
                if (opt.disabled) cls += ' is-disabled';
                var $opt = $('<div class="' + cls + '" data-value="' + opt.value + '">' + opt.text + '</div>');
                $dropdown.append($opt);
            });

            $select.wrap($wrapper);
            $select.before($input);
            $select.before($arrow);
            $select.after($dropdown);

            var $elSelect = $select.closest('.el-select');

            // 点击打开/关闭
            $input.on('click', function(e) {
                e.stopPropagation();
                $('.el-select.is-focus').not($elSelect).removeClass('is-focus');
                $elSelect.toggleClass('is-focus');
            });

            // 选择选项
            $dropdown.on('click', '.el-select__option:not(.is-disabled)', function() {
                var val = String($(this).attr('data-value') || '');
                var text = $(this).text();

                $select.val(val).trigger('change');
                $dropdown.find('.el-select__option').removeClass('is-selected');
                $(this).addClass('is-selected');

                var $textSpan = $input.find('.el-select__text');
                if (val === '') {
                    $textSpan.addClass('is-placeholder').text(placeholder);
                } else {
                    $textSpan.removeClass('is-placeholder').text(text);
                }

                $elSelect.removeClass('is-focus');
            });
        }

        // 点击外部关闭
        $(document).on('click', function() {
            $('.el-select.is-focus').removeClass('is-focus');
        });

        // 阻止下拉框内点击冒泡
        $(document).on('click', '.el-select__dropdown', function(e) {
            e.stopPropagation();
        });

        // 初始化所有 select
        $(function() {
            $('.form-select').each(function() {
                initElSelect($(this));
            });
        });

        // Modal 打开时重新初始化
        $(document).on('shown.bs.modal', function() {
            setTimeout(function() {
                $('.modal .form-select').each(function() {
                    initElSelect($(this));
                });
            }, 100);
        });

        // ========== 自定义确认弹窗 ==========
        var confirmCallback = null;
        var confirmModalInstance = null;

        // 显示确认弹窗
        function showConfirm(title, message, callback) {
            $('#confirmTitle').text(title);
            $('#confirmMessage').text(message);
            confirmCallback = callback;
            if (!confirmModalInstance) {
                confirmModalInstance = new bootstrap.Modal(document.getElementById('confirmModal'));
            }
            confirmModalInstance.show();
        }

        // 确认按钮点击事件
        $(document).on('click', '#confirmBtn', function() {
            if (confirmCallback) {
                confirmCallback();
                confirmCallback = null;
            }
            confirmModalInstance.hide();
        });

        // 弹窗关闭时清理
        $('#confirmModal').on('hidden.bs.modal', function() {
            document.body.classList.remove('modal-open');
            var backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
        });

        // 确认删除 - 不刷新页面，只移除行
        function confirmDelete(url, $row) {
            showConfirm('确认删除', '此操作不可恢复，确定继续吗？', function() {
                $.post(url, {}, function(res) {
                    if (res.code === 1) {
                        showToast(res.msg, 'success');
                        // 如果传入了行元素，移除该行
                        if ($row && $row.length) {
                            $row.fadeOut(300, function() { $(this).remove(); });
                        } else {
                            // 否则刷新页面
                            setTimeout(function() { location.reload(); }, 500);
                        }
                    } else {
                        showToast(res.msg, 'error');
                    }
                }, 'json');
            });
        }
        function validateRequired($input, fieldName) {
            var value = $input.val();
            if (typeof value === 'string') {
                value = value.trim();
            }
            if (!value || (Array.isArray(value) && value.length === 0)) {
                setFieldError($input, '请输入' + fieldName);
                return false;
            }
            return true;
        }

        // 验证手机号
        function validatePhone($input) {
            var value = $input.val().trim();
            if (value && !/^1[3-9]\d{9}$/.test(value)) {
                setFieldError($input, '请输入正确的手机号码');
                return false;
            }
            return true;
        }

        // 验证邮箱
        function validateEmail($input) {
            var value = $input.val().trim();
            if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                setFieldError($input, '请输入正确的邮箱地址');
                return false;
            }
            return true;
        }

        // 切换分页数量
        function changePageSize(limit) {
            var url = new URL(window.location.href);
            url.searchParams.set('limit', limit);
            url.searchParams.set('page', '1'); // 重置到第一页
            window.location.href = url.toString();
        }
    </script>
    
<script>
// 显示当前时间
function updateTime() {
    var now = new Date();
    var weekDays = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
    var year = now.getFullYear();
    var month = String(now.getMonth() + 1).padStart(2, '0');
    var day = String(now.getDate()).padStart(2, '0');
    var hours = String(now.getHours()).padStart(2, '0');
    var minutes = String(now.getMinutes()).padStart(2, '0');
    var seconds = String(now.getSeconds()).padStart(2, '0');
    var weekDay = weekDays[now.getDay()];

    $('#currentTime').text(year + '年' + month + '月' + day + '日 ' + weekDay + ' ' + hours + ':' + minutes + ':' + seconds);
}
updateTime();
setInterval(updateTime, 1000);
</script>

</body>
</html>
