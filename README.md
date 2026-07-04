# 抖音解析工具 (Douyin Parser Tool)

> 抖音链接解析与账号内容导出服务平台 — 支持单作品解析、主页作品/点赞/收藏批量导出、自动更新监控、对象存储集成

## 📋 功能概览

| 功能 | 说明 |
|------|------|
| **单作品解析** | 解析单个抖音视频/图文/实况图，获取播放地址、封面、音乐等信息 |
| **主页作品解析** | 批量解析指定抖音用户主页的所有作品 |
| **点赞作品解析** | 解析用户点赞过的作品列表 |
| **收藏作品解析** | 解析用户收藏的作品列表 |
| **自动更新监控** | 定时检测已解析账号的新作品，增量更新并邮件通知 |
| **文件管理** | 在线预览、下载、删除已生成的链接文件 |
| **下载代理** | 代理下载抖音视频/图片资源到本地服务器 |
| **对象存储集成** | 支持阿里云 OSS、腾讯云 COS、火山引擎 TOS、S3 兼容存储 |
| **邮件通知** | SMTP 邮件发送，支持注册验证码、自动更新通知 |
| **用户系统** | 注册/登录/密码重置，独立 Cookie 管理，解析记录管理 |

## 🏗️ 项目结构

```
├── index.php                 # 导航首页
├── parser.html               # 主页解析页面
├── single.php                # 单作品解析页面
├── watch.php                 # 自动更新监控页面
├── account.php               # 账号管理页面
├── settings.php              # 后台管理页面
├── user.php                  # 用户中心页面
├── login.php                 # 登录页面
├── register.php              # 注册页面
├── forgot.php                # 忘记密码页面
├── downloads.php             # 下载管理页面
├── config.php                # 数据库配置
├── composer.json             # Composer 依赖
│
├── api/                      # API 接口层
│   ├── Douyin.php            # 核心解析入口（单作品/主页/点赞/收藏）
│   ├── douyin_common.php     # 通用解析函数（HTTP请求、URL处理、数据提取）
│   ├── douyin_single.php     # 单作品解析逻辑
│   ├── douyin_account.php    # 账号内容解析逻辑
│   ├── AccountCookie.php     # 账号 Cookie 管理接口
│   ├── auth_api.php          # 认证接口（登录/登出/初始化）
│   ├── user_api.php          # 用户接口（注册/更新/邮箱绑定）
│   ├── settings_api.php      # 全局设置接口（Cookie 管理）
│   ├── file_manager.php      # 文件管理接口（列表/预览/下载/删除）
│   ├── file_preview.php      # 文件预览接口（视频/图片在线预览）
│   ├── download_proxy.php    # 下载代理接口
│   ├── server_save.php       # 服务器端保存接口
│   ├── manage_records.php    # 解析记录管理接口
│   └── auto_update.php       # 自动更新接口
│
├── includes/                 # 核心库
│   ├── auth.php              # 认证系统（会话/CSRF/管理员/用户认证）
│   ├── email.php             # 邮件发送（PHPMailer 封装）
│   └── storage.php           # 对象存储配置管理
│
├── assets/                   # 前端资源
│   ├── css/                  # 样式文件
│   │   ├── index.css         # 首页样式
│   │   ├── parser.css        # 解析页面样式
│   │   ├── watch.css         # 监控页面样式
│   │   ├── settings.css      # 后台管理样式
│   │   ├── downloads.css     # 下载管理样式
│   │   ├── install.css       # 安装页面样式
│   │   ├── ui.css            # 通用 UI 组件样式
│   │   └── source-guardian.css
│   └── js/                   # JavaScript 文件
│       ├── parser.js         # 解析页面逻辑
│       ├── watch.js          # 监控页面逻辑
│       ├── auth.js           # 认证逻辑
│       ├── account.js        # 账号管理逻辑
│       ├── settings.js       # 后台设置逻辑
│       ├── single.js         # 单作品解析逻辑
│       ├── user.js           # 用户中心逻辑
│       ├── downloads.js      # 下载管理逻辑
│       ├── install.js        # 安装逻辑
│       └── app-console.js    # 应用控制台
│
├── data/                     # 数据存储
│   ├── admin.php             # 管理员配置
│   ├── settings.php          # 全局设置（Cookie 等）
│   └── storage.php           # 对象存储配置
│
├── downloads/                # 下载文件目录
│   ├── sessions/             # 会话下载目录
│   └── users/                # 用户下载目录
│
├── install/                  # 安装程序
│   ├── install.php           # 安装向导页面
│   ├── install_ajax.php      # 安装 AJAX 接口
│   └── database.sql          # 数据库建表 SQL
│
├── tasks/                    # 计划任务
│   └── cron_auto_update.php  # 自动更新计划任务脚本
│
├── logs/                     # 日志目录
├── system/                   # 系统文件
└── vendor/                   # Composer 依赖包
```

## 🚀 快速开始

### 环境要求

- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- PDO MySQL 扩展
- cURL 扩展
- Composer（用于安装依赖）

### 安装步骤

1. **克隆项目到 Web 目录**

```bash
git clone https://github.com/your-username/douyin-parser.git
cd douyin-parser
```

2. **安装 Composer 依赖**

```bash
composer install
```

3. **配置 Web 服务器**

   将 Web 服务器根目录指向项目目录，确保以下目录可写：
   - `data/`
   - `downloads/`
   - `logs/`

4. **访问安装向导**

   在浏览器中访问 `http://your-domain/install/install.php`，按照向导完成：
   - 创建数据库
   - 导入表结构
   - 初始化管理员账号

5. **完成安装**

   安装完成后，`install/install.lock` 文件会自动生成，安装目录可安全删除。

### 计划任务配置

如需自动更新监控功能，添加以下 crontab：

```bash
# 每30分钟执行一次自动更新检查
*/30 * * * * php /path/to/tasks/cron_auto_update.php --token=YOUR_CRON_TOKEN
```

`CRON_TOKEN` 可在用户中心生成。

## 🔧 配置指南

### 抖音 Cookie 配置

解析用户主页/点赞/收藏内容需要提供有效的抖音 Cookie：

1. 登录抖音网页版（https://www.douyin.com）
2. 打开浏览器开发者工具（F12）
3. 在 Network 标签中复制任意请求的 `Cookie` 请求头
4. 在后台管理 → Cookie 设置中粘贴保存

### 对象存储配置

支持以下云存储服务：

| 服务 | Provider 值 | SDK |
|------|-------------|-----|
| 阿里云 OSS | `oss` | `alibabacloud/oss-v2` |
| 腾讯云 COS | `cos` | `qcloud/cos-sdk-v5` |
| 火山引擎 TOS | `tos` | `volcengine/ve-tos-php-sdk` |
| S3 兼容存储 | `s3` | AWS S3 SDK |

### 邮件通知配置

支持 SMTP 邮件发送，用于：
- 注册验证码
- 密码重置
- 自动更新通知

## 🛡️ 安全特性

- **CSRF 防护**：所有写操作接口均验证 CSRF Token
- **会话安全**：HttpOnly + SameSite=Lax Cookie，HTTPS 自动启用 Secure 标志
- **Cookie 安全存储**：管理员 Cookie 以 `<?php exit; ?>` 前缀加密存储，防止直接访问
- **域名白名单**：下载代理仅允许抖音相关域名资源
- **文件路径防护**：文件名严格过滤，防止路径穿越攻击
- **密码安全**：使用 `password_hash()` 加密存储
- **邮箱验证码**：60 秒发送间隔限制，10 分钟有效期

## 📄 输出文件格式

### 视频链接文件 (`*_videos.txt`)

```
标题: 作品标题
作者: 作者昵称
播放地址: https://...
封面地址: https://...
音乐标题: 背景音乐
音乐作者: 音乐作者
音乐链接: https://...
---
标题: 下一个作品
...
```

### 图片链接文件 (`*_images.txt`)

```
标题: 作品标题
作者: 作者昵称
图片地址: https://...
封面地址: https://...
音乐标题: 背景音乐
---
标题: 下一个作品
...
```

## 📦 依赖

- [`phpmailer/phpmailer`](https://github.com/PHPMailer/PHPMailer) — 邮件发送
- [`alibabacloud/oss-v2`](https://github.com/aliyun/aliyun-oss-php-sdk) — 阿里云 OSS SDK
- [`qcloud/cos-sdk-v5`](https://github.com/tencentyun/cos-php-sdk-v5) — 腾讯云 COS SDK
- [`volcengine/ve-tos-php-sdk`](https://github.com/volcengine/ve-tos-php-sdk) — 火山引擎 TOS SDK

## 📝 日志

- `logs/douyin_debug.log` — 抖音 API 调试日志
- `logs/cron_auto_update.log` — 自动更新计划任务日志
- `logs/auth.log` — 认证日志
- `logs/auth_cache.json` — 认证缓存

## ⚖️ 许可证

本项目仅供学习和研究使用。使用本工具解析抖音内容时，请遵守抖音平台的服务条款和相关法律法规。

---

> **免责声明**：本工具仅用于技术学习和研究，不得用于任何商业用途或非法目的。使用者应自行承担使用风险。
