# 抖音主页解析工具

🎯 抖音主页内容自动监控与解析系统

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![GitHub Stars](https://img.shields.io/github/stars/01Anlan/dyzy.svg)](https://github.com/01Anlan/dyzy/stargazers)
[![GitHub Forks](https://img.shields.io/github/forks/01Anlan/dyzy.svg)](https://github.com/01Anlan/dyzy/network)

一个功能强大的抖音主页内容自动监控系统，支持实时内容更新检测、邮件通知、自动化下载和数据库管理。

## ✨ 核心特性

### 🚀 主要功能
- **🔄 智能解析** - 自动解析抖音主页视频和图片链接
- **📧 邮件通知** - 支持SMTP邮件通知，多种触发条件
- **💾 自动下载** - 发现新内容自动保存到本地文件
- **📊 状态监控** - 实时查看系统运行状态和历史记录
- **🛡️ 自动更新** - 基于计划任务的自动内容更新监控

### 🎨 用户体验
- **📱 响应式界面** - 完美适配桌面和移动设备
- **🎯 精美邮件模板** - 专业的SVG图标和现代化设计
- **⚙️ 灵活配置** - 支持自定义监控间隔和文件命名
- **🔍 文件预览** - 在线预览已保存的文件内容

### 🔧 技术特性
- **💽 数据库存储** - 使用MySQL数据库存储解析记录和配置
- **📨 多邮件支持** - 集成PHPMailer和备用邮件方案
- **🛡️ 错误处理** - 完善的异常处理和日志记录系统
- **🔒 安全防护** - 文件路径安全检查和输入验证

## 🛠️ 系统架构

### 核心文件说明

| 文件 | 功能描述 |
|------|----------|
| `index.php` | 主界面，提供用户交互界面 |
| `Douyin.php` | 抖音链接解析核心功能 |
| `auto_update.php` | 自动更新管理接口 |
| `cron_auto_update.php` | 计划任务执行脚本 |
| `file_manager.php` | 文件管理功能 |
| `manage_records.php` | 解析记录管理 |
| `file_preview.php` | 文件预览功能 |
| `config.php` | 数据库配置 |

### 数据库结构

```sql
-- 解析记录表 (parse_records)
-- 自动更新日志表 (auto_update_logs)  
-- 邮件配置表 (email_configs)
🛠️ 安装部署

环境要求

· PHP 7.4 或更高版本
· 支持cURL扩展
· 邮件服务（SMTP）

快速开始

1. 克隆项目

```bash
git clone https://github.com/01Anlan/dyzy.git
cd dyzy
```

1. 配置环境

```bash
# 确保服务器支持PHP和cURL
# 检查PHP版本
php -v

# 检查cURL扩展
php -m | grep curl
```

1. 配置邮件服务

· 编辑配置文件设置SMTP参数
· 配置发件人邮箱和授权码
· 测试邮件发送功能

1. 访问应用

· 将项目部署到Web服务器
· 通过浏览器访问首页
· 开始配置监控任务

📖 使用说明

基本配置

1. 设置监控链接
   · 输入要监控的抖音主页链接
   · 支持多种抖音链接格式
2. 配置监控参数
   · 设置检查间隔（5分钟到24小时）
   · 选择监控类型（视频/图片）
   · 自定义保存文件名
3. 启用邮件通知
   · 配置SMTP服务器信息
   · 设置通知条件
   · 测试邮件功能

高级功能

· 自动清理 - 定期清理旧文件释放空间
· 历史记录 - 查看所有监控操作记录
· 状态监控 - 实时显示系统运行状态

⚙️ 配置文件

主要配置文件说明：
```bash
// config.php
$config = [
    'smtp_host' => 'smtp.qq.com',
    'smtp_port' => 465,
    'smtp_secure' => 'ssl',
    'smtp_username' => 'your-email@qq.com',
    'smtp_password' => 'your-auth-code'
];
```
🔧 故障排除

常见问题

Q: 监控任务无法启动？
A:检查PHP环境和文件权限，确保logs目录可写。

Q: 收不到邮件通知？
A:验证SMTP配置，检查邮箱的垃圾邮件文件夹。

Q: 解析失败？
A:尝试更换抖音主页链接，确保链接格式正确。

日志查看

系统运行日志保存在 logs/ 目录下，可帮助诊断问题。

🤝 贡献指南

欢迎提交 Issue 和 Pull Request！

1. Fork 本仓库
2. 创建特性分支 (git checkout -b feature/AmazingFeature)
3. 提交更改 (git commit -m 'Add some AmazingFeature')
4. 推送到分支 (git push origin feature/AmazingFeature)
5. 开启 Pull Request

📄 许可证

本项目采用 MIT 许可证 - 查看 LICENSE 文件了解详情。

📞 支持联系

· 提交 Issue: GitHub Issues
· 邮箱联系: zhcnli@qq.com

🙏 致谢

感谢以下开源项目：

· PHPMailer - 邮件发送功能
· 其他依赖项...

---

如果这个项目对你有帮助，请给个 ⭐ 星标支持！

