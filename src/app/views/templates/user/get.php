<?php require __DIR__ . "/../../templates/header.html" ?>
<?php if (isset($user)) { ?>
    <b>ID: </b> <?= $user->id ?>
    <b>Name: </b> <?= $user->name ?>
    <b>Email: </b> <?= $user->email ?>
<?php } else {?>
    <b>User not found!</b>
<?php }
require __DIR__ . "/../../templates/footer.html" ?>
