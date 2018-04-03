<table class="table">
<thead>
<tr>
<th>名称</th>
<th>类型</th>
<th>描述</th>
</tr>
</thead>
<tbody>
<?php foreach ($fields as $field_name => $field): ?>
    <tr>
    <th><?= $field_name ?></th>
    <td>
        <?php if ($field['type'][0] == '['): ?>
        <a href="#_id_type_<?= substr($field['type'], 1, strlen($field['type'])-2) ?>"><?= $field['type'] ?></a>
        <?php else: echo $field['type']; endif ?></td>
    <td><?= $field['description'] ?></td>
    </tr>
<?php endforeach ?>

</tbody>
</table>