<?php
// +---------------------------------------------------------------------------
// | Mini Framework
// +---------------------------------------------------------------------------
// | Copyright (c) 2015-2021 http://www.sunbloger.com
// +---------------------------------------------------------------------------
// | Licensed under the Apache License, Version 2.0 (the "License");
// | you may not use this file except in compliance with the License.
// | You may obtain a copy of the License at
// |
// | http://www.apache.org/licenses/LICENSE-2.0
// |
// | Unless required by applicable law or agreed to in writing, software
// | distributed under the License is distributed on an "AS IS" BASIS,
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// | See the License for the specific language governing permissions and
// | limitations under the License.
// +---------------------------------------------------------------------------
// | Source: https://github.com/jasonweicn/miniframework
// +---------------------------------------------------------------------------
// | Author: Jason Wei <jasonwei06@hotmail.com>
// +---------------------------------------------------------------------------
// | Website: http://www.sunbloger.com/miniframework
// +---------------------------------------------------------------------------
namespace Mini\Base;

class Exception extends \Exception
{

    /**
     * 构造
     *
     * @param string $message 错误信息
     * @param int $code 错误代码
     */
    public function __construct($message, $code = 0, $level = Log::ERROR, $position = null)
    {
        Log::record($message, $level, $position);
        parent::__construct($message, $code);
    }

    /**
     * 重构 toString
     */
    public function __toString()
    {
        if (SHOW_ERROR === true) {
            return parent::__toString();
        } else {
            self::showErrorPage($this->code);
        }
    }

    /**
     * 显示自定义的报错内容
     *
     * @param int $code
     */
    public static function showErrorPage($code)
    {
        $http = Http::getInstance();
        $status = $http->isStatus($code);

        if ($status === false) {
            $code = 500;
            $status = $http->isStatus($code);
        }

        $info = <<<EOT
        <!DOCTYPE html>
        <html>
        <head>
        <title>{$code} Error</title>
        <style type="text/css">
            html {
                font-size: 62.5%;
            }
            body {
                background-color: #fff;
                color: #000;
                font-family: helvetica, arial, sans-serif;
                font-size: 1.4em;
                line-height: 1.5;
                text-align: center;
            }
            a, a:hover, a:focus {
                color: blue;
            }
            .centered {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            }
            .emoji {
            font-size: 9em;
            }
            .title {
            font-size: 3em;
            line-height: 0em;
            color: grey;
            }
            .code {
            font-size: 1.5em;
            }
        </style>
        </head>
        <body class="centered">
        <div class="emoji">😭</div>
        <p class="title">Ooooops!</p>
        <p class="code"><strong>{$code}</strong> {$status}</p>
        <p>您遭遇到了错误，请<a href="javascript:history.back();">返回</a>重试或尝试搜索。</p>
        </body>
        </html>
EOT;

        $http->header('Content-Type', 'text/html; charset=utf-8')->response($code, $info);

        die();
    }
}
