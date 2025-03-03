<?php

require "signup.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Utilisateurs</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Prénom</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Rôle</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['first_name']) ?></td>
        <td><?= htmlspecialchars($user['last_name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['role']) ?></td>
        <td>
            <form method="post" action="" style="display:inline;">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <select name="role">
                    <option value="member" <?= $user['role'] == 'member' ? 'selected' : '' ?>>Membre</option>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <input type="submit" name="update" value="Modifier">
            </form>
            <form method="post" action="" style="display:inline;">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input type="submit" name="delete" value="Supprimer">
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>