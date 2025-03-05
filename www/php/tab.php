<?php
require_once "db.php";

// Récupérer la liste des utilisateurs
$stmt = $pdo->query("SELECT * FROM User");
$users = $stmt->fetchAll();

// Récupérer la liste des bières
$stmt = $pdo->query("SELECT * FROM Beer");
$beers = $stmt->fetchAll();

// Traitement de la suppression
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    if (isset($_POST['delete_type']) && $_POST['delete_type'] === 'beer') {
        $stmt = $pdo->prepare("DELETE FROM Beer WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("DELETE FROM User WHERE id = ?");
    }
    $stmt->execute([$id]);
    header('Location: tab.php');
    exit;
}

// Traitement de la mise à jour du rôle
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $role = $_POST['role'];
    $stmt = $pdo->prepare("UPDATE User SET role = ? WHERE id = ?");
    $stmt->execute([$role, $id]);
    header('Location: tab.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Dashboard BeerCraft</title>
    <style>
        body {
            background: linear-gradient(135deg, rgba(22, 26, 48, 1) 0%, rgba(52, 58, 64, 1) 100%);
        }

        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .hover-glass:hover {
            background: rgba(255, 255, 255, 0.15);
        }
    </style>
</head>

<body class="min-h-screen p-8 text-gray-100">
    <div class="max-w-7xl mx-auto space-y-8">

        <h2 class="text-4xl font-light mb-6 text-center bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Utilisateurs</h2>
        <div class="glass rounded-xl shadow-2xl p-6 border border-gray-200/20">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200/20">
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Prénom</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Nom</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Rôle</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/20">
                    <?php foreach ($users as $user): ?>
                        <tr class="hover-glass transition-all duration-300">
                            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($user['id']) ?></td>
                            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($user['first_name']) ?></td>
                            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($user['last_name']) ?></td>
                            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs <?= $user['role'] === 'admin' ? 'bg-blue-500/30 text-blue-200' : 'bg-purple-500/30 text-purple-200' ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <form method="post" action="" class="inline">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <select name="role" class="bg-blue-500/30 text-blue-200 px-3 py-1 rounded-lg text-sm mr-2">
                                        <option value="member" <?= $user['role'] == 'member' ? 'selected' : '' ?>>Membre</option>
                                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                    <button type="submit" name="update" class="bg-blue-500/30 hover:bg-blue-500/50 px-3 py-1 rounded-lg text-sm transition-all duration-300">Modifier</button>
                                </form>
                                <form method="post" action="" class="inline ml-2">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="delete_type" value="user">
                                    <button type="submit" name="delete" class="bg-red-500/30 hover:bg-red-500/50 px-3 py-1 rounded-lg text-sm transition-all duration-300">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2 class="text-4xl font-light mb-6 text-center bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">Bières</h2>
        <div class="glass rounded-xl shadow-2xl p-6 border border-gray-200/20">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200/20">
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Nom</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Origine</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Alcool</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Description</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Image</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Prix</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Création</th>
                        <th class="px-6 py-4 text-left text-sm font-light tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/20">
                    <?php foreach ($beers as $beer): ?>
                        <tr class="hover-glass transition-all duration-300">
                            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($beer['id']) ?></td>
                            <td class="px-6 py-4 text-sm font-medium text-blue-300"><?= htmlspecialchars($beer['name']) ?></td>
                            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($beer['origin']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs bg-blue-500/30 text-blue-200">
                                    <?= htmlspecialchars($beer['alcohol']) ?>%
                                </span>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <div class="text-sm overflow-hidden hover:overflow-y-auto h-20 hover:h-auto hover:max-h-32 transition-all duration-300 pr-2">
                                    <div class="whitespace-pre-line">
                                        <?= nl2br(htmlspecialchars($beer['description'])) ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="<?= htmlspecialchars($beer['image']) ?>" target="_blank"
                                    class="text-blue-300 hover:text-blue-400 transition-colors duration-300 underline text-sm">Voir</a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-purple-300"><?= htmlspecialchars($beer['average_price']) ?> €</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400"><?= htmlspecialchars($beer['created_at']) ?></td>
                            <td class="px-6 py-4">
                                <form method="post" action="" class="inline">
                                    <input type="hidden" name="id" value="<?= $beer['id'] ?>">
                                    <input type="hidden" name="delete_type" value="beer">
                                    <button type="submit" name="delete" class="bg-red-500/30 hover:bg-red-500/50 px-3 py-1 rounded-lg text-sm transition-all duration-300">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>