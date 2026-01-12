# 📊 全球 VPS 价值计算器 (Pro) | VPS Value Calculator

一个现代化的、基于 Web 的 VPS 剩余价值计算工具。支持实时汇率转换、剩余价值估算、溢价/折价计算，并能生成精美的资产快照图片，一键上传至图床。

## ✨ 主要功能

* **多币种支持**：实时汇率转换 (API 自动获取)，支持 USD, CNY, EUR 等多种货币。
* **智能计算**：基于周期（月/季/年/三年/买断）精确计算剩余价值。
* **资产快照**：生成高质量、高清晰度的资产卡片图片 (支持 Retina 屏幕)。
* **一键上传**：集成本地 PHP 代理，解决跨域问题，支持一键上传图片并生成 Markdown/HTML 代码。
* **安全隐私**：敏感的图床 Token 存储在服务器端，前端不可见。

---

## 🛠️ 部署架构说明 (必读)

为了解决纯前端无法处理的 **跨域 (CORS)**、**Token 安全** 以及 **Canvas 图片污染** 问题，本项目采用了 **前端 (HTML)** + **后端代理 (PHP)** 的架构。

**你需要具备以下环境：**

1. **VPS / 服务器**：用于托管网页文件。
2. **Web 服务器环境**：Nginx、Apache 或 OpenLiteSpeed（推荐使用 1Panel、宝塔面板等快速部署）。
3. **PHP 环境**：PHP 7.4 或更高版本（必须开启 `curl` 扩展）。
4. **图床服务**：一个支持 API 上传的图床（如 Lsky Pro, Chevereto, 或基于 CloudFlare Workers 搭建的图床）。

### 为什么必须用 PHP？

* **解决跨域 (CORS)**：浏览器禁止前端直接向不同域名的图床 API 发送 POST 请求。PHP 在服务器端转发请求，不受此限制。
* **保护 Token**：如果直接在 JS 里写 Token，任何人都能通过“查看源代码”盗用你的图床额度。PHP 将 Token 藏在服务器端。

---

## 🚀 安装与配置指南

### 1. 下载代码

将项目文件下载并上传至你的网站根目录（例如 `/www/wwwroot/tools.nkx.moe`）。
目录结构应如下：

```text
/网站根目录
├── index.html      # 前端界面
└── upload.php      # 后端代理核心 (必须存在)

```

### 2. 配置后端 (upload.php)

打开 `upload.php`，修改顶部的配置区域，填入你的图床信息。

```php
<?php
// upload.php

// --- 配置区域 (请修改这里) ---

// 1. 你的图床 API Token (Bearer Token)
$TOKEN = '你的_图床_TOKEN_粘贴在这里'; 

// 2. 你的图床上传接口地址
// 这里的地址取决于你用的图床程序：
// - Lsky Pro (兰空): https://你的域名/api/v1/upload
// - Chevereto: https://你的域名/api/1/upload
// - CloudFlare ImgBed: 你的 Worker 地址/upload
$API_URL = 'https://img.nkx.moe/upload';

// ---------------------------

```

### 3. 检查 PHP 权限

确保你的 Web 服务器（如 Nginx）有权限读取这些文件。通常保持默认权限（644 或 755）即可。
同时确保 PHP 的 `php.ini` 中 `upload_max_filesize` 和 `post_max_size` 足够大（建议 2M 以上，截图通常很小）。

---

## 🖼️ 图床对接说明

本项目默认适配标准 **JSON 返回格式** 的图床 API（兼容 Lsky Pro V1/V2, Chevereto, EasyImage 等）。

**后端期望的图床返回格式示例：**

```json
{
    "status": true,
    "data": {
        "url": "https://img.nkx.moe/i/2026/01/12/xyz.png"
    }
}

```

*或者直接返回对象：*

```json
{
    "url": "https://img.nkx.moe/i/2026/01/12/xyz.png"
}

```

如果你的 CloudFlare Worker 图床返回格式非常特殊，你可能需要微调 `index.html` 中的 `uploadBlob` 函数里的解析逻辑。

---

## 📜 许可证

MIT License. 随意修改、分发和使用。

---

**Powered by NKX Tools**
