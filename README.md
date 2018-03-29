# Step-PHP

PHP开发框架。中文名：一步之遥。距离原生PHP和Laravel都是一步之遥。

## 为什么要有这个框架？

这个框架可以在纯PHP和Laravel之间提供一个阶梯。让你的学习曲线不会太过陡峭。

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

为什么要引入这三个概念呢？因为这三个概念非常重要，事关现代化，效率和安全。你可以先去搜索引擎上学习一下这三个概念。

接下来，我们依次讲解一下这些概念。

### 伪静态

我们比较如下的两个URL

    /index.php?s=do_somthing&id=1
    /do_something/1

我们会发现第二个URL显得比较“专业”。在很久很久以前，只有纯html静态页面才具有这种URL。后来，人们通过PHP也可以实现这种URL，于是就称之为“伪静态”。

至于如何实现，我们接下来再讲。

### composer

假设你想实现一个功能，比如伪静态。你可以自己研究如何实现，也可以到网上copy代码。当然，懒惰的人（懒惰是程序员的美德）肯定选择后者。

但俗话说的好：“人一定会犯错，机器不会”。那么，能不能让机器做copy代码这件事情，把copy代码这件事情做的好，做的妙呢？

能，答案就是[composer](https://getcomposer.org/)。

但是身在中国，有的时候你会发现composer的安装比较慢（很慢很慢）。那么此时你需要翻墙。

翻墙是程序员的必备技能。是的，我们是技术人员，我们不惧艰险，我们渴望知识，而知识就是力量。

翻墙之后，在命令行下使用代理的步骤参见[这里](http://picasso250.github.io/2015/04/03/agent.html)

### ORM

PHP和MySQL是好朋友。

在如何操作MySQL这件事情上，有很深的学问。有一个模式叫做 ORM，它本是为Java这种OO的强类型语言提供数据转换的。在PHP中，它是封装了的一种访问数据库的方法。

为什么要使用ORM呢？有三个原因：方便，方便，方便。

1. 方便参数化，防止注入；
2. 方便进一步封装；
3. 方便异构数据库转换。

## 简要使用指南

1. 下载源码
1. 将 .env.sample 改名成 .env
2. 在项目根目录下运行 `composer install`
2. 在项目根目录下运行 `php -S 0.0.0.0:8080 -t public` 开启开发服务器
3. 访问 [http://localhost:8080](http://localhost:8080)

[详细说明点击这里](doc.md)

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
