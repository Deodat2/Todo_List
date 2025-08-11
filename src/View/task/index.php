<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <title>Liste des tâches</title>
        <link rel="stylesheet" href="/css/style.css" />
    </head>
    <body>
        <h1>Liste des tâches</h1>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <a href="/?page=tasks&subpage=create">Créer une nouvelle tâche</a>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Tags</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr><td colspan="4">Aucune tâche trouvée.</td></tr>
                <?php else: ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['title']) ?></td>
                            <td><?= nl2br(htmlspecialchars($task['description'])) ?></td>
                            <td>
                                <?php
                                if (!empty($task['tags'])) {
                                    echo implode(', ', array_map('htmlspecialchars', $task['tags']));
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="/?page=tasks&subpage=edit&id=<?= $task['id'] ?>">Modifier</a>

                                <form method="POST" action="/?page=tasks" style="display:inline" onsubmit="return confirm('Confirmer la suppression ?');">
                                    <input type="hidden" name="id" value="<?= $task['id'] ?>" />
                                    <input type="hidden" name="action" value="delete" />
                                    <button type="submit" style="color:red;">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <p><a href="/?page=dashboard">Retour au dashboard</a></p>
    </body>
</html>
