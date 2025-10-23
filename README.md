抖音主页解析工具

🎯 抖音主页解析工具

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![GitHub Stars](https://img.shields.io/github/stars/yourusername/douyin-monitor.svg)](https://github.com/yourusername/douyin-monitor/stargazers)
[![GitHub Forks](https://img.shields.io/github/forks/yourusername/douyin-monitor.svg)](https://github.com/yourusername/douyin-monitor/network)
一个功能强大的抖音主页内容自动监控系统，支持实时内容更新检测、邮件通知和自动化下载。

✨ 特性

🚀 核心功能

· 🔄 自动监控 - 定时检查抖音主页内容更新
· 📧 智能通知 - 支持邮件通知，多种触发条件
· 💾 自动下载 - 发现新内容自动保存到本地
· 📊 状态监控 - 实时查看监控状态和历史记录

🎨 用户体验

· 📱 响应式界面 - 完美适配桌面和移动设备
· 🎯 精美邮件模板 - 专业的SVG图标和现代化设计
· ⚙️ 灵活配置 - 支持自定义监控间隔和文件命名

🔧 技术特性

· 📨 多邮件支持 - 集成PHPMailer和备用邮件方案
· 🛡️ 错误处理 - 完善的异常处理和日志记录
· 💽 文件存储 - 使用JSON文件存储，无需数据库

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

```php
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
