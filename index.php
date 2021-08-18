<?php
/**
 * 应用入口
 */

// 应用命名空间（请与应用所在目录名保持一致）
const APP_NAMESPACE = 'app';

// 是否显示错误信息（默认值：false）
const SHOW_ERROR = true;

// 是否开启日志（生产环境建议关闭，默认值：false）
const LOG_ON = false;

// 是否启用布局功能（默认值：false）
const LAYOUT_ON = true;

// 是否开启REST模式的API接口功能（默认值：false）
const REST_ON = true;

// Onepager分页大小（每页显示的项目数）
const ONEPAGER_PAGE_SIZE = 18;

// Tutorial分页大小（每页显示的项目数）
const TUTORIAL_PAGE_SIZE = 10;

// Resource分页大小（每页显示的项目数）
const RESOURCE_PAGE_SIZE = 12;

// 默认Onepager预览图
const ONEPAGER_DEFAULT_PREVIEW = '/uploads/onepager_default.jpg';

// 默认Tutorial预览图
const TUTORIAL_DEFAULT_PREVIEW = '/uploads/tutorial_default.jpg';

// 默认Resource预览图
const RESOURCE_DEFAULT_PREVIEW = '/uploads/resource_default.jpg';

// 摘要长度
const EXCERPT_LENGTH = 200;

// 价格区间
const PRICE_RANGE = [0, 5, 10, 20];

// 人民币兑换积分比率（1人民币兑10积分）
const RATE = 10;

// Cookies Salt
const COOKIES_SALT = 'H6JRg1KCGymfJnjX%Cy8D!UW7C#7Qd%f';

// 活跃用户统计（*天内登陆过的用户）
const ACTIVE_PERIOD = 30;

// 侧栏同类一页书推荐数量
const RECO_POST = 3;

// 自动连接数据库
define('DB_AUTO_CONNECT', true);

// 启用CSRF令牌
define('CSRF_TOKEN_ON', true);

// 引入 MiniFramework 就是这么简单
require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'Bootstrap.php';
