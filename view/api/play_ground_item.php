<h3><?= $resource['name'] ." ". htmlspecialchars($api['name']) ?></h3>

<table class="table">
<tbody>

<tr>
<th>URI</th>
<td><?= htmlspecialchars($api['uri']) ?></td>
</tr>

<tr>
<th>参数</th>
<td> <?php if ($fields = $api['input']) include __DIR__.'/play_ground_fields.php'; else echo "无"; ?> </td>
</tr>

<tr>
<th>返回</th>
<td> <?php if ($fields = $api['output']) {
    if (count($fields) == 1 && $fields[0][0] == '[') {
        echo "<strong>数组</strong>, 其中每个元素为:<br>";
        $fields = $field_table[substr($fields[0], 1, strlen($fields[0])-2)];
        include __DIR__.'/play_ground_fields_plain.php';
    } else {
        $fields = [];
        foreach ($api['output'] as $field_name) {
            $fields[$field_name] = $field_table[$resource['name']][$field_name];
        }
        include __DIR__.'/play_ground_fields_plain.php';
    }
    } else echo "无"; ?>
</td>
</tr>

</tbody>
</table>

<h4>玩一玩</h4>

<form class="" action="<?= $api['uri'] ?>" method="post">
<?php if ($api['input']): ?>
    <dl class="">
    <?php foreach ($api['input'] as $name): $field = $field_table[$resource['name']][$name]; ?>
        <dt><?= $name ?> <?= htmlspecialchars($field['type']) ?></dt>
        <dd>
            <input type="text" name="<?= $name ?>" value="">
            <span><?= htmlspecialchars($field['description']) ?></span>
        </dd>
    <?php endforeach ?>
    </dl>
<?php else: ?>
<div>不需要参数</div>
<?php endif ?>
    <input type="submit" name="" value="发起请求">
</form>
<pre></pre>

<hr>