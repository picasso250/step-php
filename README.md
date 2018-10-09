# Step-PHP

[![travis](https://travis-ci.com/picasso250/step-php.svg?branch=master)](https://travis-ci.com/picasso250/step-php)

PHP开发框架。中文名：一步之遥。距离原生PHP和Laravel都是一步之遥。

## 为什么要有这个框架？

这个框架可以在 **纯PHP** 和 **Laravel** 之间提供一个阶梯。让你的学习曲线不会太过陡峭。

换言之，这应该是你脱离“纯PHP”之后的第一个框架。

我们假设你已经掌握如下技能：

0. 了解HTTP协议的基本知识
0. 会写基本的PHP页面
1. cookie
2. session
3. MySQL

然后，我们将引入3个重要概念：

1. 伪静态（单入口）
2. composer
3. ORM

就这三个概念，不会再多了。

为什么要引入这三个概念呢？因为这三个概念非常重要，事关现代化，效率和安全。
你可以先去搜索引擎上学习一下这三个概念。

router使用的组件是：[bramus/router](https://github.com/bramus/router)

ORM 使用的组件是 [Idiorm](http://idiorm.readthedocs.io/en/latest/)

除了以上三个概念，
Step-PHP也引入了其他一些确实可以提高开发效率的组件（[phpdotenv](https://github.com/vlucas/phpdotenv),[whoops](https://github.com/filp/whoops)），但就不在这里介绍了。

## 简要使用指南

1. 下载源码
1. 将 .env.sample 改名成 .env
2. 在项目根目录下运行 `composer install`
2. 在项目根目录下运行 `php -S 0.0.0.0:8080 -t public` 开启开发服务器
3. 访问 [http://localhost:8080](http://localhost:8080)

好了！你已经做完了一个网站！

[详细使用说明点击这里](doc.md)

### 部署

如果你使用的是 apache, 将root指向public目录，其中的 .htaccess 文件已经准备好了。

如果你使用的是 nginx，那么配置如下：

    server {
        listen 80;
        server_name mydevsite.dev;
        root /var/www/mydevsite/public;

        index index.php;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            # NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini

            # With php5-fpm:
            fastcgi_pass unix:/var/run/php5-fpm.sock;
            fastcgi_index index.php;
            include fastcgi.conf;
            fastcgi_intercept_errors on;
        }
    }

## 写在最后的话

如果你在使用中遇到了什么问题，请联系我。
