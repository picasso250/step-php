# Step-PHP 文档

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

这个文件的作用有两个：

1. 初始化
2. 路由分发（分发请求）

路由分发将根据用户的请求URL分发给PHP的各个函数处理。

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