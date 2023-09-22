<?php require __DIR__ . "/../../templates/header.html" ?>
<table>
    <tr>
        <th>ID</th>
        <th>E-mail</th>
        <th>Name</th>
        <th>Password hash</th>
    </tr>
    <?php if (isset($users)) {
        foreach ($users as $user) { ?>
            <tr>
                <td><?= $user->id ?> </td>
                <td><?= $user->email ?> </td>
                <td><?= $user->name ?> </td>
                <td><?= $user->password ?> </td>
            </tr>
        <?php }
    } ?>
</table>
<?php require __DIR__ . "/../../templates/footer.html" ?>
