# step-php
PHP开发框架。中文名：一步之遥。距离原生PHP和Laravel都是一步之遥。

## 为什么要有这个框架？

我的设想是，这个框架可以在纯PHP和Laravel之间提供一个阶梯。让你的学习曲线不会太过陡峭。

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

接下来，我们依次讲解一下这三个概念。

## 伪静态

我们比较如下的两个URL

    /index.php?s=do_somthing&id=1
    /do_something/1

我们会发现第二个URL显得比较“专业”。在很久很久以前，只有纯html静态页面才具有这种URL。后来，人们通过PHP也可以实现这种URL，于是就称之为“伪静态”。

至于如何实现，我们接下来再讲。

## composer

我们假设你想实现一个功能，比如伪静态。你可以自己研究如何实现，也可以到网上copy代码。当然，我们懒惰的人（懒惰是程序员的美德）肯定选择后者。

但有句俗话说的好：“人一定会犯错，机器不会”。于是，能不能让机器做copy这件事情，把copy代码这件事情做的好，做的妙呢？

能，答案就是[composer](https://getcomposer.org/)。

但是身在中国，有的时候你会发现composer的安装比较慢（很慢很慢）。那么此时你需要翻墙。

翻墙是程序员的必备技能。我们是技术人员，我们不惧艰险，才能获得更多的知识。

翻墙之后，在命令行下使用代理的步骤参见[这里](http://picasso250.github.io/2015/04/03/agent.html)

## ORM

PHP和MySQL是好朋友。

在如何操作MySQL这件事情上，有很深的学问。有一个模式叫做 ORM，它本是为Java这种OO的强类型语言提供数据转换的。在PHP中，这就是封装了的一种访问数据库的方法。

为什么要使用ORM呢？有三个原因：方便，方便，方便。

1. 方便参数化防止注入；
2. 方便进一步封装；
3. 方便异构数据库转换。

## 简要使用指南

**开发**

1. 下载源码。
1. 将 .env.sample 改名成 .env
2. 在项目根目录下运行 `composer install`
2. 在项目根目录下运行 `php -S 0.0.0.0:8080 -t public` 开启开发服务器
3. 访问 [http://localhost:8080](http://localhost:8080)

## 框架原理

首先，来看看文件夹的结构。

    public/
     |--index.php  # 入口文件
    view/           # html(视图)文件夹
    action.php     # 行为函数
    config.ini     # 配置文件
    .env           # 环境相关的配置
    composer.json
    composer.lock  # 两个composer的配置文件

我们来依次讲解。

### 入口文件

我们说了，伪静态需要一个单入口，这个入口文件就是 `public/index.php`。

这个文件的作用有两个

1. 初始化
2. 路由分发（分发请求）

初始化包括载入配置和配置数据库参数。

路由分发就是伪静态的关键了。它将根据用户的请求URL分发给PHP的各个函数处理。

用户的一个请求过来，如URL是 `GET /` ，那么将会匹配到：

    ->get('/', 'action_index')

其中 `action_index` 就是行为函数，这个函数在文件 `action.php` 中，用户的请求会引起这个函数的执行。

所以，你需要说明用户访问的某个URL会引起什么函数的执行。在 `public/index.php` 中配置好，然后在 `action.php` 中实现这个函数。

更详细的请看[PHP Router class 的文档](https://github.com/dannyvankooten/PHP-Router)。

### 视图文件夹

在view文件夹中，你可以有很多的html文件。

只需要在 action.php 中

    include ROOT.'/view/a.php';

就可以显示给用户看了。

### 配置

配置分为两种，一种是普通配置，一种是随着环境变化的配置。

先说说什么是环境。

大家知道，在我们的开发过程中，总是要先测试的。那么我们就会分所谓的**开发环境** 和 **线上环境**。这就是不同的环境。

不同的环境，参数有可能不同。比如是否打开报错显示，数据库的配置。

那么，这些配置是随环境变化的配置，应该放在 .env 中。

更详细的文档请参考 [PHP dotenv](https://github.com/vlucas/phpdotenv)

而其他的配置，只要不涉密，就放在 config.ini 中。

### ORM 访问数据库

当你在 .env 中配置好数据库的路径用户名密码之后，就可以使用如下方式访问数据库：

    $v = ORM::for_table('user')->find_one();

更多的访问方式请看 [Idiorm’s documentation](http://idiorm.readthedocs.io/en/latest/)

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
