<h3><?= $resource['name'] ." ". htmlspecialchars($api['name']) ?></h3>

<table class="table">
<tbody>

<tr>
<th>URI</th>
<td><?= htmlspecialchars($api['uri']) ?></td>
</tr>

<tr>
<th>参数</th>
<td> <?php
    if ($p = $api['input']) {
        $fields = $p['fields'];
        if ($p['is_array']) {
            echo "<strong>数组</strong>, 其中每个元素为:<br>";
        }
        include __DIR__.'/play_ground_fields_plain.php';
    } else echo "无"; ?>
</td>
</tr>

<tr>
<th>返回</th>
<td> <?php
    if ($p = $api['output']) {
        $fields = $p['fields'];
        if ($p['is_array']) {
            echo "<strong>数组</strong>, 其中每个元素为:<br>";
        }
        include __DIR__.'/play_ground_fields_plain.php';
    } else echo "无"; ?>
</td>
</tr>

</tbody>
</table>

<h4>玩一玩</h4>

<form class="" action="<?= $api['uri'] ?>" method="post">
<?php if ($api['input']): ?>
    <dl class="">
    <?php foreach ($api['input']['fields'] as $name => $field): ?>
        <dt><?= $name ?> (<?= htmlspecialchars($field['type']) ?>)</dt>
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