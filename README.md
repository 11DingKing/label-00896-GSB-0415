# 企业网站管理系统 (Enterprise CMS)

基于 ThinkPHP 8.0 + SQLite 的企业网站管理系统，包含后台管理和前台展示功能。

---

## How to Run

### 使用 Docker Compose 运行（推荐）

```bash
# 根目录构建并启动服务
docker-compose up --build -d

# 查看运行状态
docker-compose ps

# 查看日志
docker-compose logs -f cms
```

### 停止服务

```bash
docker-compose down
```

### 清理数据重新开始

```bash
docker-compose down -v
docker-compose up --build -d
```

---

## Services

| 服务名称 | 端口 | 说明 |
|---------|------|------|
| cms | 8081 | 企业网站管理系统（前台+后台） |

### 访问地址

- **前台网站**: http://localhost:8081
- **后台管理**: http://localhost:8081/admin

---

## 测试账号

| 角色 | 用户名 | 密码 |
|------|--------|------|
| 超级管理员 | admin | admin123 |

---

## 题目内容

设计并开发一个功能全面的通用企业网站管理系统，该系统需包含完整的后台管理系统、前台展示网站及后端服务架构。系统采用SQLite作为数据库存储方案，需设计并实现完整的数据库结构，包括但不限于用户表、权限表、内容表、配置表等核心数据表，并确保表结构设计合理、字段定义规范、关系设置正确。界面设计应达到专业级水准，具备美观的视觉效果和大气的整体风格，需包含响应式布局设计、统一的色彩方案、清晰的排版层次、直观的交互体验及符合现代审美的UI组件。技术实现要求：1. 使用ThinkPHP框架进行开发，采用传统MVC架构，不采用前后端分离模式2. 实现完整的用户认证与权限管理系统，包括角色分配、权限控制、操作日志记录等功能3. 开发企业网站常用功能模块，如新闻管理、产品展示、案例展示、联系表单、留言管理、系统配置等4. 设计并实现合理的缓存策略，提升系统性能5. 添加完善的错误处理机制和安全防护措施，包括SQL注入防护、XSS攻击防护、CSRF防护等6. 确保代码结构清晰、注释完整、命名规范，符合企业级开发标准系统需满足以下技术指标：- 页面加载速度：首屏加载时间≤2秒- 浏览器兼容性：支持Chrome、Firefox、Safari、Edge等主流浏览器最新版本及前两个版本- 安全性：通过基本安全漏洞扫描，无高危安全隐患- 可维护性：代码模块化程度高，便于后续功能扩展和系统维护交付成果应包括：完整的源代码、数据库文件、详细的开发文档、部署说明文档及用户操作手册。

---

## 项目介绍

### 功能特性

#### 后台管理
- 🔐 用户认证与权限管理（RBAC）
- 📰 新闻内容管理
- 📦 产品信息管理
- 💼 案例展示管理
- 📄 单页内容管理
- 💬 留言反馈管理
- ⚙️ 系统配置管理
- 📋 操作日志记录

#### 前台展示
- 🏠 响应式首页
- 📰 新闻中心
- 📦 产品展示
- 💼 案例展示
- 📞 联系我们
- 💬 在线留言

### 技术栈

- **后端框架**: ThinkPHP 8.0
- **数据库**: SQLite 3
- **前端框架**: Bootstrap 5 + jQuery
- **图标库**: Bootstrap Icons
- **富文本编辑器**: TinyMCE
- **容器化**: Docker + Docker Compose

### 安全特性

- ✅ SQL 注入防护（PDO 预处理）
- ✅ XSS 攻击防护（输出转义）
- ✅ CSRF 防护（Token 验证）
- ✅ 密码加密存储（bcrypt）
- ✅ 登录失败锁定
- ✅ 操作日志记录

### 项目结构

```
├── frontend-admin/         # 管理后台项目（ThinkPHP）
│   ├── app/               # 应用目录
│   │   ├── common/        # 公共函数
│   │   ├── controller/    # 控制器
│   │   ├── middleware/    # 中间件
│   │   ├── model/         # 数据模型
│   │   ├── service/       # 业务服务
│   │   ├── validate/      # 验证器
│   │   └── view/          # 视图模板
│   ├── config/            # 配置文件
│   ├── database/          # 数据库文件
│   ├── docs/              # 用户手册
│   ├── public/            # 公共资源
│   ├── route/             # 路由配置
│   ├── runtime/           # 运行时目录
│   ├── Dockerfile         # Docker 构建文件
│   └── composer.json      # PHP 依赖配置
├── docs/                   # 项目文档
│   └── project_design.md  # 项目设计文档
├── docker-compose.yml     # Docker Compose 配置
├── .gitignore             # Git 忽略规则
└── README.md              # 项目说明文档
```

### 浏览器支持

- Chrome (最新版及前两个版本)
- Firefox (最新版及前两个版本)
- Safari (最新版及前两个版本)
- Edge (最新版及前两个版本)

### 开发文档

- [项目设计文档](docs/project_design.md)
- [用户操作手册](frontend-admin/docs/user_manual.md)

---

## License

MIT License
