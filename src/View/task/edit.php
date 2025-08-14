<?php
// Inclure le header
require __DIR__ . '/../partials/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <title>Modifier la tâche</title>
        <link rel="stylesheet" href="/css/styles.css" />
    </head>
    <body>

        <div class="edit-form">

            <h1>Modifier la tâche</h1>

            <?php if (!empty($_SESSION['error'])): ?>

                <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>

                <?php unset($_SESSION['error']); ?>

            <?php endif; ?>

            <form method="POST" action="/?page=tasks">

                <input type="hidden" name="action" value="update" />      
                <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']) ?>" />

                <label for="title">Titre :</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required />

                <label for="description">Description :</label>
                <textarea id="description" name="description" rows="5"><?= htmlspecialchars($task['description']) ?></textarea>

                <label>Tags :</label>
                <div class="tag-container" id="tag-container">

                    <?php if (!empty($task['tags'])): ?>

                        <?php foreach ($task['tags'] as $tag): ?>

                            <div class="tag">

                                <?= htmlspecialchars($tag) ?>

                                <button type="button" onclick="removeTag(this)">×</button>

                                <input type="hidden" name="tags[]" value="<?= htmlspecialchars($tag) ?>">

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    <input type="text" class="add-tag" placeholder="Ajouter un tag..." id="tag-input">

                </div>

                <button type="submit" class="bn">Mettre à jour</button>

            </form>

        </div>

        <script>

            const tagContainer = document.getElementById('tag-container');

            const tagInput = document.getElementById('tag-input');
            
            tagInput.addEventListener('keypress', function(e) {

                if (e.key === 'Enter') {

                    e.preventDefault();

                    const newTag = tagInput.value.trim();

                    if (newTag) {

                        const tagElement = document.createElement('div');

                        tagElement.classList.add('tag');

                        tagElement.innerHTML = `

                            ${newTag}
                            <button type="button" onclick="removeTag(this)">×</button>
                            <input type="hidden" name="tags[]" value="${newTag}">
                        `;

                        tagContainer.insertBefore(tagElement, tagInput);

                        tagInput.value = '';

                    }

                }

            });

            function removeTag(button) {

                button.parentElement.remove();

            }

        </script>

    </body>
            
</html>
