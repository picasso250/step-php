# Step-PHP 文档

## 一个例子网站

接下来，我们将用15分钟做一个博客网站

### 网址设计

我们将让首页作为博客的列表页面。然后还需要有新增、编辑和管理页面。

于是我们的路由如下

    ->get('/', 'action_index')
    ->get('/admin', 'action_blog_admin')
    ->get('/blog/:id', 'action_blog_view')
    ->match(['GET','POST'], '/blog/:id/edit', 'action_blog_edit')
    ->match(['GET','POST'], '/admin/blog/create', 'action_blog_create')

### 新建博客

首先在 view 文件夹中新建 blog_new.php

这将是新增博客的界面。内容如下：

    <h1>新建博客</h1>

    <form action="?" method="POST" >
        <p>
            标题<br>
            <input type="text" name="title">
        </p>
        <p>
            内容<br>
            <textarea name="content" id="content_textarea" cols="30" rows="10"></textarea>
        </p>
        <input type="submit" value="保存博客">
    </form>

接下来，我们在 action.php 中实现此界面的函数。

    function action_blog_new()
    {
        render_with_layout(ROOT_VIEW.'/layout.php', ['content' => ROOT_VIEW.'/blog_new.php']);
    }

现在访问一下 [http://localhost:8080/admin/blog/new](http://localhost:8080/admin/blog/new) 就可以看见新建页面了。

### 数据库设计

在继续之前，我们需要将数据库做好。

在数据库中运行以下建表语句。

    CREATE TABLE `blog` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(150) NOT NULL,
        `content` TEXT NOT NULL,
        PRIMARY KEY (`id`)
    )
    ENGINE=InnoDB
    ;

### 保存数据

    function action_blog_new()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $b = ORM::for_table("blog")->create();
            $b->title = $_POST['title'];
            $b->content = $_POST['content'];
            $b->save();
            header("Location:/blog/$b->id");
            return;
        }
        render_with_layout(ROOT_VIEW.'/layout.php', ['content' => ROOT_VIEW.'/blog_new.php']);
    }

这样我们就完成了保存操作的代码。在保存之后，我们重定向到blog的查看页面。因为我们还没写页面，这时应该是报了一个错。

你可以到数据库中观察是否正确生成了数据。

### 查看

一样的套路。我们首先在 action.php 中添加行为函数。

    function action_blog_view($id)
    {
        $blog = ORM::for_table('blog')->find_one($id);
        render_with_layout(ROOT_VIEW.'/layout.php', ['content' => ROOT_VIEW.'/blog_view.php'], compact('blog'));
    }

然后在 view 里新增视图文件 blog_view.php

    <p>
    <strong>标题:</strong>
    <?= htmlspecialchars($blog->title); ?>
    </p>
    
    <p>
    <strong>内容:</strong>
    <?= htmlspecialchars($blog->content); ?>
    </p>

这样，刷新浏览器就可以看到了。

### 列表页面

我们在 action.php 中修改函数

    function action_index()
    {
        $blog_list = ORM::for_table('blog')->find_many();
        render_with_layout(ROOT_VIEW.'/layout.php', ['content' => ROOT_VIEW.'/blog_list.php'], compact('blog_list'));
    }

然后在view 中添加 blog_list.php 文件

    <h1>博客列表</h1>
    
    <table>
    <tr>
        <th>标题</th>
        <th>内容</th>
    </tr>
    
    <?php foreach ($blog_list as $blog): ?>
        <tr>
        <td><?= htmlspecialchars($blog->title) ?></td>
        <td><?= htmlspecialchars($blog->content) ?></td>
        <td><a href="/blog/<?= $blog->id ?>">链接</a></td>
        </tr>
    <?php endforeach ?>
    </table>

现在访问一下 [http://localhost:8080](http://localhost:8080) 就可以看见列表页面了。

## 数据验证

当用户未按照我们的意愿填写表单时，我们要给予他们反馈。

下面是 action.php 中的 action_blog_new() 函数，我们将之修改为支持报错的版本。

    function action_blog_new()
    {
        $errors = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = _post('title', '');
            if ($title === '') $errors[] = "标题不能为空";
            $content = _post('content', '');
            if ($content === '') $errors[] = "内容不能为空";
            if (!$errors) {
                $b = ORM::for_table("blog")->create();
                $b->title = $title;
                $b->content = $content;
                $b->save();
                header("Location:/blog/$b->id");
                return;
            }
        }
        render_with_layout(ROOT_VIEW.'/layout.php', ['content' => ROOT_VIEW.'/blog_new.php'], compact('errors'));
    }

对应的，blog_new.php 也需要修改

    <h1>新建博客</h1>

    <form action="?" method="POST" >
        <?php if ($errors): ?>
            <div id="error_explanation">
            <h2>
                有 <?= count($errors) ?> 个错误
            </h2>
            <ul>
                <?php foreach($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach ?>
            </ul>
            </div>
        <?php endif ?>
        <p>
            标题<br>
            <input type="text" name="title" value="<?= htmlentities(_post('title', '')) ?>">
        </p>
        <p>
            内容<br>
            <textarea name="content" cols="30" rows="10"><?= htmlentities(_post('content', '')) ?></textarea>
        </p>
        <input type="submit" value="保存博客">
    </form>

### 编辑博客

接下来我们处理编辑。首先在action.php中新增函数

    function action_blog_edit($id)
    {
        $errors = [];
        $blog = ORM::for_table('blog')->find_one($id);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $blog->title = _post('title', '');
            if ($blog->title === '') $errors[] = "标题不能为空";
            $blog->content = _post('content', '');
            if ($blog->content === '') $errors[] = "内容不能为空";
            if (!$errors) {
                $blog->save();
                header("Location:/blog/$blog->id");
                return;
            }
        }
        render_with_layout(ROOT_VIEW.'/layout.php', ['content' => ROOT_VIEW.'/blog_edit.php'], compact('errors', 'blog'));
    }

然后在 view中新增 blog_edit.php

    <h1>新建博客</h1>

    <form action="?" method="POST" >
        <?php if ($errors): ?>
            <div id="error_explanation">
            <h2>
                有 <?= count($errors) ?> 个错误
            </h2>
            <ul>
                <?php foreach($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach ?>
            </ul>
            </div>
        <?php endif ?>
        <p>
            标题<br>
            <input type="text" name="title" value="<?= htmlentities($blog->title) ?>">
        </p>
        <p>
            内容<br>
            <textarea name="content" cols="30" rows="10"><?= htmlentities($blog->content) ?></textarea>
        </p>
        <input type="submit" value="保存博客">
    </form>

### DRY

有一个原则，是不要重复你自己。我们可以发现，我们在创建和编辑的时候，有很多代码都是重复的。接下来我们致力于消除这些代码。

action.php中的代码可以变为：

    function blog_check($blog, &$errors)
    {
        $blog->title = _post('title', '');
        if ($blog->title === '') $errors[] = "标题不能为空";
        $blog->content = _post('content', '');
        if ($blog->content === '') $errors[] = "内容不能为空";
    }

    function action_blog_new()
    {
        $errors = [];
        $blog = ORM::for_table("blog")->create();
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            blog_check($blog, $errors);
            if (!$errors) {
                $blog->save();
                header("Location:/blog/$blog->id");
                return;
            }
        }
        render_with_layout(ROOT_VIEW.'/layout.php', ['content' => ROOT_VIEW.'/blog_new.php'], compact('errors', 'blog'));
    }

    function action_blog_edit($id)
    {
        $errors = [];
        $blog = ORM::for_table('blog')->find_one($id);
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            blog_check($blog, $errors);
            if (!$errors) {
                $blog->save();
                header("Location:/blog/$blog->id");
                return;
            }
        }
        render_with_layout(ROOT_VIEW.'/layout.php', ['content' => ROOT_VIEW.'/blog_edit.php'], compact('errors', 'blog'));
    }

而view也可以从2个文件变成三个文件。我们将公共部分提取成 blog_form.php

blog_form.php

    <form action="?" method="POST" >
        <?php if ($errors): ?>
            <div id="error_explanation">
            <h2>
                有 <?= count($errors) ?> 个错误
            </h2>
            <ul>
                <?php foreach($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach ?>
            </ul>
            </div>
        <?php endif ?>
        <p>
            标题<br>
            <input type="text" name="title" value="<?= htmlentities($blog->title) ?>">
        </p>
        <p>
            内容<br>
            <textarea name="content" cols="30" rows="10"><?= htmlentities($blog->content) ?></textarea>
        </p>
        <input type="submit" value="保存博客">
    </form>

blog_new.php

    <h1>新建博客</h1>

    <?php include __DIR__.'/blog_form.php' ?>

blog_edit.php

    <h1>新建博客</h1>

    <?php include __DIR__.'/blog_form.php' ?>

好了，我们的博客小网站就开发完毕了。你可以自己加入新欢的功能。

## 三个重要概念

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

## 框架原理

首先，来看看文件夹的结构。

    public/
     |--index.php  # 入口文件
    view/           # html(视图)文件夹
    action.php     # 行为函数
    lib.php        # 库函数
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

更详细的请看[router 的文档](https://github.com/bephp/router/blob/master/README.zh-CN.md)。

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
