<!DOCTYPE html>
<html lang="zh-cmn-Hans">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>A example of Step-PHP</title>

    <meta name="author" content="xiaochi">
    <link rel="shortcut icon" type="image/ico" href="uri" />

    <meta name="HandheldFriendly" content="true">
    <meta name="apple-mobile-web-app-title" content="Step-PHP">

    <?php if ($_ENV['DEBUG']) echo $GLOBALS['debugbarRenderer']->renderHead() ?>

</head>

<body>
    <?php include $_inner_tpl_list['content'] ?>
    <?php if ($_ENV['DEBUG']) echo $GLOBALS['debugbarRenderer']->render() ?>
</body>

</html>