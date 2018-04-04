<!DOCTYPE html>
<html lang="zh-cmn-Hans">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>API文档+PlayGround</title>

    <meta name="author" content="xiaochi">

    <meta name="HandheldFriendly" content="true">
    <meta name="apple-mobile-web-app-title" content="Step-PHP">

    <link rel="stylesheet" href="//lib.sinaapp.com/js/bootstrap/v3.0.0/css/bootstrap.min.css">
    <style>
    body {
        padding: 1em;
    }
    </style>

    <script src="//lib.sinaapp.com/js/jquery/3.1.0/jquery-3.1.0.min.js" charset="utf-8"></script>

</head>

<body>

    <h1><?= htmlspecialchars($GLOBALS['config_api']['core']['name']) ?> API文档+PlayGround</h1>

    <dl class="public_params">
        <dt>名称</dt>
        <dd>
            <?= htmlspecialchars($GLOBALS['config_api']['core']['name']) ?>
        </dd>
        <dt>版本</dt>
        <dd>
            <?= htmlspecialchars($GLOBALS['config_api']['core']['version']) ?>
        </dd>
        <dt>URI</dt>
        <dd>
            <?= htmlspecialchars($GLOBALS['config_api']['core']['uri']) ?>
        </dd>
    </dl>

    <h2>公共参数</h2>
    <?php if ($field_table['core']): ?>
    <dl class="public_params">
    <?php foreach($field_table['core'] as $field_name => $a): ?>
        <dt><?= $field_name ?> (<?= $a['type'] ?>)</dt>
        <dd>
            <input type="text" name="l" id="<?= $field_name ?>" value="">
            <span><?= htmlspecialchars($a['description']) ?></span>
        </dd>
    <?php endforeach ?>
    </dl>
    <?php else: ?>
    无
    <?php endif ?>

    <hr>

    <h2>接口列表</h2>

    <?php foreach ($resources as $resource): //var_dump($resource['methods']) ?>
        <?php foreach ($resource['methods'] as $api): ?>
        <?php if ($api) {
            include __DIR__.'/play_ground_item.php'; } ?>
        <?php endforeach ?>
    <?php endforeach ?>

    <h2>类型详解</h2>

    <?php foreach ($field_table as $big_type => $value): //print_r($value); ?>
        <?php if($value): ?><h3 id="_id_type_<?= $big_type ?>"><?= $big_type ?></h3><?php endif ?>
        <?php if ($fields = $value) include __DIR__.'/play_ground_fields_plain.php' ?>
    <?php endforeach ?>

    <script type="text/javascript">
        function get_form_dict(form) {
            var data = {};
            $(form).find('input').each(function () {
                data[$(this).attr('name')] = $(this).val();
            });
            return data;
        }
        $(function () {
            $('form').each(function () {
                var $prev = $(this).prev();
                $prev.text($prev.text()+" "+$(this).attr('action'));
            });
            var token = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjkxM0ZBOEY1MTgzQzYwMkUwQjQxMjdFMEY0Q0JFOUQ1NjE1QjNERTIiLCJ0eXAiOiJKV1QifQ.eyJuYmYiOjE1MjIwNDU2NzcsImV4cCI6MTUyMjA0OTI3NywiaXNzIjoiaHR0cHM6Ly9hY2Nlc3MudHVodS53b3JrIiwiYXVkIjpbImh0dHBzOi8vYWNjZXNzLnR1aHUud29yay9yZXNvdXJjZXMiLCJzaXRlX3NhcGkiLCJzaXRlX3Nob3BhcGkiLCJzaXRlX3dvcmtzaG9wYXBpIl0sImNsaWVudF9pZCI6InNhcGkudHVodS5jbiIsInN1YiI6IjA4ZDU2Yzc2NGNiMmMxNjg1N2MzNTEwNzY3NDJkNWVkIiwiYXV0aF90aW1lIjoxNTIyMDQzNDAwLCJpZHAiOiJUZWNobmljaWFuIiwiaWQiOjEyMDQyLCJuYW1lIjoi6YCU6JmO5rWL6K-VY2wiLCJzY29wZSI6WyJzaXRlX3NhcGkiLCJzaXRlX3Nob3BhcGkiLCJzaXRlX3dvcmtzaG9wYXBpIiwib2ZmbGluZV9hY2Nlc3MiXSwiYW1yIjpbIlRlY2huaWNpYW4iXX0.gxHPVKbvjTxkzKbnr-qsqkG7hcPICh93Wt0Y1-fnqPgw5aYDUaMBdl8NFglrEYWVxJOecuAwSAcXEtxlABMLJVhuF4oXpADrymvbuKHscXSuIqOxyUkArni-WyUMONBO9xieKqTgE8ug2vLEWt55dHmR0IGQ4DdXLxBrypr6iLY2bKIigY2N0POK-axDyc6Keg7jxxHV2n5Su4YyxYd2JSuZAEo_uOzfDl-cY1ZkYEnoEldveQQwfNJFRUJRW-FUknKO_DdboL5vr4t7P1EUeqetiObZFiFNHuO62Pzxn8Ss51J8S9IwZ3OVCzbG6XHt2-keD9FyV3sGqpH3f3YGvQ';
            $('form').submit(function (e) {
                e.preventDefault();
                var $form = $(this);
                var $btn = $form.find('[type="submit"]').prop('disabled', true);
                var data = get_form_dict(this);
                var url = $(this).attr('action');
                // url += "&_skip_auth_=1&_login_user_id="+$('#_login_user_id').val();
                url = 'https://xue.tuhu.cn'+url;
                $.ajax({
                    type: "POST",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", 'Bearer '+token);
                    },
                    url: url,
                    data: JSON.stringify({postData: (data)}),
                    success: function (ret) {
                        console.log(ret.data);
                        $form.next().html(JSON.stringify(ret, null, 4));
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
    
</body>

</html>