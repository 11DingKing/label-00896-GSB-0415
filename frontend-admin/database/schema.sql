-- 企业网站管理系统数据库结构
-- SQLite 3

-- 管理员用户表
CREATE TABLE IF NOT EXISTS cms_admin_user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nickname VARCHAR(50) DEFAULT '',
    email VARCHAR(100) DEFAULT '',
    avatar VARCHAR(255) DEFAULT '',
    status INTEGER DEFAULT 1,
    login_count INTEGER DEFAULT 0,
    last_login_time INTEGER DEFAULT 0,
    last_login_ip VARCHAR(50) DEFAULT '',
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL
);

-- 角色表
CREATE TABLE IF NOT EXISTS cms_admin_role (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255) DEFAULT '',
    status INTEGER DEFAULT 1,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL
);

-- 权限表
CREATE TABLE IF NOT EXISTS cms_admin_permission (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    parent_id INTEGER DEFAULT 0,
    name VARCHAR(50) NOT NULL,
    path VARCHAR(100) DEFAULT '',
    icon VARCHAR(50) DEFAULT '',
    type INTEGER DEFAULT 1,
    sort INTEGER DEFAULT 0,
    status INTEGER DEFAULT 1,
    created_at INTEGER NOT NULL
);

-- 用户角色关联表
CREATE TABLE IF NOT EXISTS cms_admin_role_user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    role_id INTEGER NOT NULL,
    UNIQUE(user_id, role_id)
);

-- 角色权限关联表
CREATE TABLE IF NOT EXISTS cms_admin_role_permission (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    role_id INTEGER NOT NULL,
    permission_id INTEGER NOT NULL,
    UNIQUE(role_id, permission_id)
);

-- 操作日志表
CREATE TABLE IF NOT EXISTS cms_operation_log (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER DEFAULT 0,
    username VARCHAR(50) DEFAULT '',
    module VARCHAR(50) DEFAULT '',
    action VARCHAR(50) DEFAULT '',
    description VARCHAR(255) DEFAULT '',
    ip VARCHAR(50) DEFAULT '',
    user_agent VARCHAR(500) DEFAULT '',
    request_url VARCHAR(255) DEFAULT '',
    request_method VARCHAR(10) DEFAULT '',
    request_data TEXT DEFAULT '',
    created_at INTEGER NOT NULL
);

-- 分类表
CREATE TABLE IF NOT EXISTS cms_category (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    parent_id INTEGER DEFAULT 0,
    name VARCHAR(50) NOT NULL,
    type VARCHAR(20) NOT NULL,
    description VARCHAR(255) DEFAULT '',
    sort INTEGER DEFAULT 0,
    status INTEGER DEFAULT 1,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL
);

-- 新闻表
CREATE TABLE IF NOT EXISTS cms_news (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER DEFAULT 0,
    title VARCHAR(200) NOT NULL,
    cover VARCHAR(255) DEFAULT '',
    summary VARCHAR(500) DEFAULT '',
    content TEXT,
    author VARCHAR(50) DEFAULT '',
    source VARCHAR(100) DEFAULT '',
    views INTEGER DEFAULT 0,
    is_top INTEGER DEFAULT 0,
    is_recommend INTEGER DEFAULT 0,
    status INTEGER DEFAULT 1,
    published_at INTEGER DEFAULT 0,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL
);

-- 产品表
CREATE TABLE IF NOT EXISTS cms_product (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER DEFAULT 0,
    name VARCHAR(200) NOT NULL,
    cover VARCHAR(255) DEFAULT '',
    images TEXT DEFAULT '',
    description VARCHAR(500) DEFAULT '',
    content TEXT,
    price DECIMAL(10,2) DEFAULT 0,
    sort INTEGER DEFAULT 0,
    is_recommend INTEGER DEFAULT 0,
    status INTEGER DEFAULT 1,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL
);

-- 案例表
CREATE TABLE IF NOT EXISTS cms_case_study (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER DEFAULT 0,
    title VARCHAR(200) NOT NULL,
    cover VARCHAR(255) DEFAULT '',
    images TEXT DEFAULT '',
    client VARCHAR(100) DEFAULT '',
    description VARCHAR(500) DEFAULT '',
    content TEXT,
    sort INTEGER DEFAULT 0,
    is_recommend INTEGER DEFAULT 0,
    status INTEGER DEFAULT 1,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL
);

-- 留言表
CREATE TABLE IF NOT EXISTS cms_message (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) DEFAULT '',
    phone VARCHAR(20) DEFAULT '',
    company VARCHAR(100) DEFAULT '',
    subject VARCHAR(200) DEFAULT '',
    content TEXT NOT NULL,
    is_read INTEGER DEFAULT 0,
    reply TEXT DEFAULT '',
    replied_at INTEGER DEFAULT 0,
    ip VARCHAR(50) DEFAULT '',
    created_at INTEGER NOT NULL
);

-- 系统配置表
CREATE TABLE IF NOT EXISTS cms_system_config (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    group_name VARCHAR(50) NOT NULL,
    key_name VARCHAR(50) NOT NULL,
    value TEXT DEFAULT '',
    type VARCHAR(20) DEFAULT 'text',
    description VARCHAR(255) DEFAULT '',
    sort INTEGER DEFAULT 0,
    UNIQUE(group_name, key_name)
);

-- Banner表
CREATE TABLE IF NOT EXISTS cms_banner (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(100) DEFAULT '',
    subtitle VARCHAR(200) DEFAULT '',
    image VARCHAR(255) NOT NULL,
    link VARCHAR(255) DEFAULT '',
    sort INTEGER DEFAULT 0,
    status INTEGER DEFAULT 1,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL
);

-- 单页内容表
CREATE TABLE IF NOT EXISTS cms_page (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(200) NOT NULL,
    content TEXT,
    seo_title VARCHAR(200) DEFAULT '',
    seo_keywords VARCHAR(255) DEFAULT '',
    seo_description VARCHAR(500) DEFAULT '',
    status INTEGER DEFAULT 1,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL
);

-- 创建索引
CREATE INDEX IF NOT EXISTS idx_news_category ON cms_news(category_id);
CREATE INDEX IF NOT EXISTS idx_news_status ON cms_news(status);
CREATE INDEX IF NOT EXISTS idx_product_category ON cms_product(category_id);
CREATE INDEX IF NOT EXISTS idx_product_status ON cms_product(status);
CREATE INDEX IF NOT EXISTS idx_case_category ON cms_case_study(category_id);
CREATE INDEX IF NOT EXISTS idx_case_status ON cms_case_study(status);
CREATE INDEX IF NOT EXISTS idx_message_read ON cms_message(is_read);
CREATE INDEX IF NOT EXISTS idx_log_user ON cms_operation_log(user_id);
CREATE INDEX IF NOT EXISTS idx_log_created ON cms_operation_log(created_at);

-- 初始化数据

-- 默认管理员 (密码: admin123)
INSERT INTO cms_admin_user (username, password, nickname, email, status, created_at, updated_at)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '超级管理员', 'admin@example.com', 1, strftime('%s','now'), strftime('%s','now'));

-- 默认角色
INSERT INTO cms_admin_role (name, description, status, created_at, updated_at)
VALUES ('超级管理员', '拥有所有权限', 1, strftime('%s','now'), strftime('%s','now'));

INSERT INTO cms_admin_role (name, description, status, created_at, updated_at)
VALUES ('内容编辑', '负责内容管理', 1, strftime('%s','now'), strftime('%s','now'));

-- 用户角色关联
INSERT INTO cms_admin_role_user (user_id, role_id) VALUES (1, 1);

-- 权限菜单
INSERT INTO cms_admin_permission (parent_id, name, path, icon, type, sort, status, created_at) VALUES
(0, '仪表盘', '/admin/dashboard', 'bi-speedometer2', 1, 1, 1, strftime('%s','now')),
(0, '内容管理', '', 'bi-file-text', 1, 2, 1, strftime('%s','now')),
(2, '新闻管理', '/admin/news', '', 1, 1, 1, strftime('%s','now')),
(2, '产品管理', '/admin/product', '', 1, 2, 1, strftime('%s','now')),
(2, '案例管理', '/admin/case', '', 1, 3, 1, strftime('%s','now')),
(2, '分类管理', '/admin/category', '', 1, 4, 1, strftime('%s','now')),
(2, 'Banner管理', '/admin/banner', '', 1, 5, 1, strftime('%s','now')),
(2, '单页管理', '/admin/page', '', 1, 6, 1, strftime('%s','now')),
(0, '用户管理', '', 'bi-people', 1, 3, 1, strftime('%s','now')),
(9, '用户列表', '/admin/user', '', 1, 1, 1, strftime('%s','now')),
(9, '角色管理', '/admin/role', '', 1, 2, 1, strftime('%s','now')),
(0, '系统管理', '', 'bi-gear', 1, 4, 1, strftime('%s','now')),
(12, '系统配置', '/admin/config', '', 1, 1, 1, strftime('%s','now')),
(12, '留言管理', '/admin/message', '', 1, 2, 1, strftime('%s','now')),
(12, '操作日志', '/admin/log', '', 1, 3, 1, strftime('%s','now'));

-- 角色权限关联 (超级管理员拥有所有权限)
INSERT INTO cms_admin_role_permission (role_id, permission_id)
SELECT 1, id FROM cms_admin_permission;

-- 内容编辑权限
INSERT INTO cms_admin_role_permission (role_id, permission_id) VALUES
(2, 1), (2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 7), (2, 8);

-- 默认分类
INSERT INTO cms_category (parent_id, name, type, sort, status, created_at, updated_at) VALUES
(0, '公司新闻', 'news', 1, 1, strftime('%s','now'), strftime('%s','now')),
(0, '行业动态', 'news', 2, 1, strftime('%s','now'), strftime('%s','now')),
(0, '软件产品', 'product', 1, 1, strftime('%s','now'), strftime('%s','now')),
(0, '硬件产品', 'product', 2, 1, strftime('%s','now'), strftime('%s','now')),
(0, '企业案例', 'case', 1, 1, strftime('%s','now'), strftime('%s','now')),
(0, '政府案例', 'case', 2, 1, strftime('%s','now'), strftime('%s','now'));

-- 系统配置
INSERT INTO cms_system_config (group_name, key_name, value, type, description, sort) VALUES
('basic', 'site_name', '企业网站管理系统', 'text', '网站名称', 1),
('basic', 'site_logo', '/static/home/images/logo.png', 'image', '网站Logo', 2),
('basic', 'site_keywords', '企业网站,CMS,内容管理', 'text', 'SEO关键词', 3),
('basic', 'site_description', '专业的企业网站管理系统', 'textarea', 'SEO描述', 4),
('basic', 'site_icp', '京ICP备12345678号', 'text', 'ICP备案号', 5),
('basic', 'site_copyright', '© 2024 Enterprise CMS. All rights reserved.', 'text', '版权信息', 6),
('contact', 'company_name', '某某科技有限公司', 'text', '公司名称', 1),
('contact', 'company_address', '北京市朝阳区某某大厦', 'text', '公司地址', 2),
('contact', 'company_phone', '400-888-8888', 'text', '联系电话', 3),
('contact', 'company_email', 'contact@example.com', 'text', '联系邮箱', 4),
('contact', 'company_qq', '12345678', 'text', 'QQ号码', 5),
('contact', 'company_wechat', 'wechat_id', 'text', '微信号', 6),
('social', 'weibo_url', '', 'text', '微博链接', 1),
('social', 'wechat_qrcode', '', 'image', '微信二维码', 2);

-- 默认单页
INSERT INTO cms_page (slug, title, content, seo_title, status, created_at, updated_at) VALUES
('about', '关于我们', '<p>这里是关于我们的内容...</p>', '关于我们 - 企业网站', 1, strftime('%s','now'), strftime('%s','now')),
('contact', '联系我们', '<p>这里是联系我们的内容...</p>', '联系我们 - 企业网站', 1, strftime('%s','now'), strftime('%s','now')),
('service', '服务项目', '<p>这里是服务项目的内容...</p>', '服务项目 - 企业网站', 1, strftime('%s','now'), strftime('%s','now'));

-- 默认 Banner 数据
INSERT INTO cms_banner (title, subtitle, image, link, sort, status, created_at, updated_at) VALUES
('数字化转型解决方案', '助力企业实现智能化升级，提升核心竞争力', '/static/images/banner1.jpg', '/product', 1, 1, strftime('%s','now'), strftime('%s','now')),
('专业技术团队', '10年行业经验，为您提供最优质的技术服务', '/static/images/banner2.jpg', '/about', 2, 1, strftime('%s','now'), strftime('%s','now')),
('全方位售后保障', '7x24小时技术支持，让您无后顾之忧', '/static/images/banner3.jpg', '/contact', 3, 1, strftime('%s','now'), strftime('%s','now'));

-- 默认留言数据
INSERT INTO cms_message (name, email, phone, company, subject, content, is_read, ip, created_at) VALUES
('张先生', 'zhangsan@example.com', '13800138001', '某科技公司', '产品咨询', '您好，我想了解一下贵公司的ERP系统，请问可以提供详细的功能介绍和报价吗？我们公司大约有200人规模。', 1, '192.168.1.100', strftime('%s','now')-86400),
('李女士', 'lisi@company.com', '13900139002', '某制造企业', '技术支持', '我们在使用过程中遇到了一些问题，系统偶尔会出现卡顿，希望能够安排技术人员远程协助排查一下。', 0, '192.168.1.101', strftime('%s','now')-43200),
('王经理', 'wangjl@corp.cn', '13700137003', '某贸易有限公司', '合作咨询', '我们公司正在进行数字化转型，希望能够与贵公司建立长期合作关系，请问如何进一步沟通？', 0, '192.168.1.102', strftime('%s','now')-3600);

-- 添加更多管理员用户
INSERT INTO cms_admin_user (username, password, nickname, email, status, created_at, updated_at)
VALUES ('editor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '内容编辑', 'editor@example.com', 1, strftime('%s','now'), strftime('%s','now'));

INSERT INTO cms_admin_user (username, password, nickname, email, status, created_at, updated_at)
VALUES ('manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '运营经理', 'manager@example.com', 1, strftime('%s','now'), strftime('%s','now'));

-- 用户角色关联
INSERT INTO cms_admin_role_user (user_id, role_id) VALUES (2, 2);
INSERT INTO cms_admin_role_user (user_id, role_id) VALUES (3, 1);

-- 添加更多角色
INSERT INTO cms_admin_role (name, description, status, created_at, updated_at)
VALUES ('访客用户', '仅可查看内容', 1, strftime('%s','now'), strftime('%s','now'));

-- 默认新闻数据
INSERT INTO cms_news (category_id, title, summary, content, author, views, is_top, is_recommend, status, published_at, created_at, updated_at) VALUES
(1, '公司荣获2024年度科技创新企业称号', '近日，我公司在年度科技创新评选中脱颖而出，荣获"2024年度科技创新企业"荣誉称号，这是对我们持续创新的肯定。', '<h3>创新驱动发展</h3><p>在过去的一年中，公司持续加大研发投入，不断推出创新产品和解决方案。我们的技术团队攻克了多项技术难题，获得了多项发明专利。</p><h3>荣誉背后的努力</h3><p>此次获奖是对公司全体员工辛勤付出的肯定。我们将继续秉承创新理念，为客户提供更优质的产品和服务。</p><h3>展望未来</h3><p>面对新的机遇和挑战，公司将继续加大创新投入，拓展业务领域，努力成为行业领军企业。</p>', '张编辑', 1256, 1, 1, 1, strftime('%s','now'), strftime('%s','now'), strftime('%s','now')),
(1, '公司成功完成A轮融资，估值突破5亿', '公司宣布完成A轮融资，本轮融资由知名投资机构领投，融资金额达到8000万元，公司估值突破5亿元。', '<h3>融资详情</h3><p>本轮融资由国内知名投资机构领投，多家战略投资者跟投。融资资金将主要用于产品研发、市场拓展和人才引进。</p><h3>发展规划</h3><p>公司计划在未来两年内，将研发团队扩大一倍，推出多款创新产品，并积极开拓海外市场。</p><h3>投资者评价</h3><p>领投机构合伙人表示："我们非常看好公司的发展前景和团队的执行力，相信公司将成为行业的佼佼者。"</p>', '李编辑', 892, 1, 1, 1, strftime('%s','now')-86400, strftime('%s','now')-86400, strftime('%s','now')-86400),
(2, '人工智能技术在企业数字化转型中的应用', '随着AI技术的快速发展，越来越多的企业开始将人工智能应用于业务流程优化，本文探讨AI在企业数字化转型中的关键作用。', '<h3>AI赋能企业转型</h3><p>人工智能技术正在深刻改变企业的运营方式。从智能客服到数据分析，从流程自动化到决策支持，AI正在各个领域发挥重要作用。</p><h3>应用场景分析</h3><p>在制造业，AI可以实现预测性维护；在零售业，AI可以优化库存管理；在金融业，AI可以提升风控能力。</p><h3>实施建议</h3><p>企业在引入AI技术时，应该先明确业务需求，选择合适的应用场景，循序渐进地推进数字化转型。</p>', '王分析师', 2341, 0, 1, 1, strftime('%s','now')-172800, strftime('%s','now')-172800, strftime('%s','now')-172800),
(2, '2024年企业管理软件发展趋势报告', '本报告分析了2024年企业管理软件市场的主要发展趋势，包括云原生、低代码、智能化等关键方向。', '<h3>云原生成为主流</h3><p>越来越多的企业选择云原生架构的管理软件，以获得更好的弹性和可扩展性。</p><h3>低代码平台兴起</h3><p>低代码开发平台让业务人员也能参与应用开发，大大提高了企业的响应速度。</p><h3>智能化深入应用</h3><p>AI技术与管理软件深度融合，为企业提供更智能的决策支持。</p>', '研究中心', 1876, 0, 1, 1, strftime('%s','now')-259200, strftime('%s','now')-259200, strftime('%s','now')-259200),
(1, '公司技术团队荣获最佳技术创新团队奖', '在第十届软件技术大会上，我公司技术团队凭借卓越的创新能力和技术实力，荣获"最佳技术创新团队"大奖。', '<h3>团队实力展现</h3><p>此次获奖是对技术团队多年努力的认可。团队在云计算、大数据、人工智能等领域取得了多项突破性成果。</p><h3>创新成果</h3><p>团队自主研发的智能分析平台已服务超过500家企业客户，帮助企业提升运营效率30%以上。</p>', '公司新闻', 654, 0, 0, 1, strftime('%s','now')-345600, strftime('%s','now')-345600, strftime('%s','now')-345600),
(2, '云计算安全最佳实践指南', '本文汇总了企业在使用云计算服务时需要注意的安全事项，帮助企业构建安全可靠的云环境。', '<h3>身份与访问管理</h3><p>实施最小权限原则，使用多因素认证，定期审计用户权限。</p><h3>数据保护</h3><p>对敏感数据进行加密存储和传输，定期备份数据，制定数据恢复计划。</p><h3>网络安全</h3><p>配置安全组和网络ACL，使用VPN或专线连接，实施入侵检测和防护。</p>', '安全专家', 1432, 0, 0, 1, strftime('%s','now')-432000, strftime('%s','now')-432000, strftime('%s','now')-432000);

-- 默认产品数据
INSERT INTO cms_product (category_id, name, description, content, price, sort, is_recommend, status, created_at, updated_at) VALUES
(3, '企业资源管理系统 ERP Pro', '一站式企业资源管理解决方案，涵盖财务、采购、库存、生产、销售全流程管理。', '<h3>产品概述</h3><p>ERP Pro是一款面向中大型企业的综合管理系统，帮助企业实现资源优化配置和业务流程标准化。</p><h3>核心功能</h3><ul><li>财务管理：总账、应收应付、成本核算、财务报表</li><li>采购管理：供应商管理、采购订单、采购入库</li><li>库存管理：多仓库管理、库存预警、盘点作业</li><li>生产管理：工单管理、物料需求、生产计划</li><li>销售管理：客户管理、销售订单、发货管理</li></ul><h3>技术特点</h3><p>采用微服务架构，支持私有云部署，提供丰富的API接口。</p>', 299999.00, 1, 1, 1, strftime('%s','now'), strftime('%s','now')),
(3, '智能客户关系管理系统 CRM Plus', '基于AI技术的新一代CRM系统，助力企业精准营销、高效管理客户关系。', '<h3>产品概述</h3><p>CRM Plus融合人工智能技术，提供智能线索评分、客户画像分析、销售预测等创新功能。</p><h3>核心功能</h3><ul><li>客户管理：360度客户视图、客户分级、生命周期管理</li><li>销售管理：商机管理、销售漏斗、业绩分析</li><li>营销管理：活动管理、线索培育、营销自动化</li><li>服务管理：工单管理、服务记录、满意度调查</li></ul><h3>智能特性</h3><p>AI驱动的线索评分、智能推荐下一步行动、自然语言查询。</p>', 99999.00, 2, 1, 1, strftime('%s','now'), strftime('%s','now')),
(3, '协同办公平台 OA Enterprise', '新一代智能协同办公平台，让企业沟通更高效、流程更规范、管理更智能。', '<h3>产品概述</h3><p>OA Enterprise是面向现代企业的综合办公平台，覆盖日常办公、流程审批、知识管理等多个场景。</p><h3>核心功能</h3><ul><li>工作流：可视化流程设计、多级审批、流程监控</li><li>即时通讯：企业IM、群组沟通、文件共享</li><li>日程管理：日程安排、会议管理、任务协同</li><li>文档管理：文档协作、版本控制、权限管理</li></ul>', 59999.00, 3, 1, 1, strftime('%s','now'), strftime('%s','now')),
(4, '智能数据采集终端 DC-500', '高性能工业级数据采集终端，支持多种协议，适用于各类工业物联网场景。', '<h3>产品概述</h3><p>DC-500是专为工业环境设计的边缘计算设备，可实现设备数据的实时采集、处理和上传。</p><h3>技术规格</h3><ul><li>处理器：ARM Cortex-A72 四核 1.5GHz</li><li>内存：4GB DDR4</li><li>存储：32GB eMMC + SD卡扩展</li><li>接口：RS485 x4, RS232 x2, 以太网 x2, USB x4</li><li>协议支持：Modbus, OPC UA, MQTT, HTTP</li></ul><h3>工业特性</h3><p>宽温设计(-20°C~70°C)，宽压输入(9-36V)，IP40防护等级。</p>', 4999.00, 1, 1, 1, strftime('%s','now'), strftime('%s','now')),
(4, '工业平板电脑 IP-1500', '15寸工业级平板电脑，采用电容触控屏，适用于产线看板、设备监控等场景。', '<h3>产品概述</h3><p>IP-1500是一款高性能工业平板电脑，具备优异的显示效果和稳定的运行性能。</p><h3>技术规格</h3><ul><li>屏幕：15英寸TFT LCD，1024x768分辨率</li><li>触控：电容式十点触控</li><li>处理器：Intel Celeron J1900 四核</li><li>内存：4GB/8GB DDR3L</li><li>存储：64GB/128GB SSD</li></ul>', 6999.00, 2, 0, 1, strftime('%s','now'), strftime('%s','now')),
(3, '人力资源管理系统 HR Cloud', '覆盖招聘、入职、考勤、薪酬、绩效全流程的云端人力资源管理平台。', '<h3>产品概述</h3><p>HR Cloud帮助企业实现人力资源管理的数字化转型，提升HR工作效率。</p><h3>核心功能</h3><ul><li>招聘管理：职位发布、简历筛选、面试安排</li><li>员工管理：入转调离、合同管理、档案管理</li><li>考勤管理：多种打卡方式、排班管理、请假审批</li><li>薪酬管理：薪资计算、社保公积金、个税申报</li><li>绩效管理：目标设定、绩效考核、360度评价</li></ul>', 39999.00, 4, 1, 1, strftime('%s','now'), strftime('%s','now'));

-- 默认案例数据
INSERT INTO cms_case_study (category_id, title, client, description, content, sort, is_recommend, status, created_at, updated_at) VALUES
(5, '某大型制造企业数字化转型项目', '鑫达制造集团', '为该集团打造一体化数字工厂解决方案，实现生产、质量、设备、能源全面数字化管理。', '<h3>项目背景</h3><p>客户是一家年产值超50亿的大型制造企业，拥有多个生产基地，面临生产数据孤岛、管理效率低下等问题。</p><h3>解决方案</h3><p>我们为客户设计了完整的数字化转型方案，包括：</p><ul><li>MES系统：实现生产过程透明化管理</li><li>SCADA系统：实时采集设备运行数据</li><li>能源管理：智能监控和优化能源使用</li><li>质量管理：全流程质量追溯</li></ul><h3>项目成果</h3><ul><li>生产效率提升25%</li><li>设备综合效率(OEE)从65%提升至82%</li><li>能源成本降低15%</li><li>质量合格率提升至99.5%</li></ul>', 1, 1, 1, strftime('%s','now'), strftime('%s','now')),
(5, '某零售连锁企业全渠道营销系统', '优选生活连锁', '打造线上线下一体化的全渠道营销系统，助力企业实现数字化营销转型。', '<h3>项目背景</h3><p>客户是一家拥有200+门店的连锁零售企业，希望打通线上线下渠道，实现全渠道营销。</p><h3>解决方案</h3><ul><li>全渠道中台：统一商品、库存、会员、订单管理</li><li>小程序商城：支持在线购物、到店自提</li><li>会员运营：积分、优惠券、会员等级</li><li>营销工具：拼团、秒杀、满减等促销活动</li></ul><h3>项目成果</h3><ul><li>线上销售额占比从5%提升至30%</li><li>会员复购率提升40%</li><li>营销ROI提升60%</li></ul>', 2, 1, 1, strftime('%s','now'), strftime('%s','now')),
(6, '某市政务服务一体化平台', '某市政务服务中心', '为该市打造集政务服务、公共服务于一体的综合性政务服务平台。', '<h3>项目背景</h3><p>客户希望实现"一网通办"，让群众和企业办事更加便捷高效。</p><h3>解决方案</h3><ul><li>统一服务门户：PC端+移动端+自助终端多端覆盖</li><li>事项管理：1500+政务事项在线办理</li><li>电子证照：电子身份证、营业执照等证照电子化</li><li>智能客服：AI问答机器人7x24小时服务</li></ul><h3>项目成果</h3><ul><li>群众满意度达到95%以上</li><li>平均办事时间缩短70%</li><li>"最多跑一次"事项覆盖率达到90%</li></ul>', 1, 1, 1, strftime('%s','now'), strftime('%s','now')),
(5, '某物流企业智慧仓储系统', '顺捷物流科技', '为该物流企业打造智慧仓储管理系统，实现仓储作业的自动化和智能化。', '<h3>项目背景</h3><p>客户拥有10万平方米仓储面积，传统管理方式效率低、出错率高。</p><h3>解决方案</h3><ul><li>WMS系统：智能库位管理、波次策略、RF作业</li><li>AGV调度：自动化搬运机器人调度系统</li><li>视觉分拣：AI视觉识别自动分拣</li><li>数据看板：实时监控仓储运营数据</li></ul><h3>项目成果</h3><ul><li>仓储作业效率提升50%</li><li>库存准确率达到99.9%</li><li>人工成本降低30%</li></ul>', 3, 1, 1, strftime('%s','now'), strftime('%s','now')),
(6, '某区智慧园区管理平台', '某经济开发区', '为园区打造智慧化管理平台，实现园区招商、服务、运营的数字化管理。', '<h3>项目背景</h3><p>客户园区面积5平方公里，入驻企业200+家，需要提升园区服务和管理水平。</p><h3>解决方案</h3><ul><li>企业服务门户：政策推送、申报办理、咨询服务</li><li>招商管理：招商线索、洽谈跟进、签约管理</li><li>物业管理：报修、缴费、访客管理</li><li>数据分析：园区经济指标、企业画像分析</li></ul><h3>项目成果</h3><ul><li>企业服务满意度提升30%</li><li>招商效率提升50%</li><li>物业服务响应时间缩短60%</li></ul>', 2, 0, 1, strftime('%s','now'), strftime('%s','now')),
(5, '某金融机构风控系统升级', '安信金融集团', '为该金融机构升级风险控制系统，利用AI技术提升风险识别和防控能力。', '<h3>项目背景</h3><p>客户面临日益复杂的金融风险环境，原有风控系统无法满足业务发展需求。</p><h3>解决方案</h3><ul><li>智能反欺诈：基于机器学习的实时欺诈检测</li><li>信用评估：多维度信用评分模型</li><li>风险预警：异常行为监测和预警</li><li>合规管理：监管报送、合规检查</li></ul><h3>项目成果</h3><ul><li>欺诈损失降低60%</li><li>审批效率提升3倍</li><li>风险识别准确率提升至95%</li></ul>', 4, 0, 1, strftime('%s','now'), strftime('%s','now'));
