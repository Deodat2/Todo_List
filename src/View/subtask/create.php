<?php
// Inclure le header
require __DIR__ . '/../partials/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <title>Créer une tâche</title>
        <link rel="stylesheet" href="/css/styles.css" />
    </head>

    <body>

        <div class="edit-form">

            <h1>Créer une nouvelle tâche</h1>

            <?php if (!empty($_SESSION['error'])): ?>

                <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>

                <?php unset($_SESSION['error']); ?>

            <?php endif; ?>

            <form method="POST" action="/?page=tasks" id="task-form">

                <input type="hidden" name="action" value="create" />

                <label for="title">Titre :</label>
                <input type="text" id="title" name="title" placeholder="Nom de la tâche" required />

                <label for="description">Description :</label>
                <textarea id="description" name="description" rows="5" placeholder="Détaillez la tâche..."></textarea>

                <label>Tags :</label>
                <div id="tag-container" class="tag-container"></div>

                <div class="available-tags">
                    <span class="tag-option" data-tag="urgent">Urgent</span>
                    <span class="tag-option" data-tag="maison">Maison</span>
                    <span class="tag-option" data-tag="travail">Travail</span>
                    <span class="tag-option" data-tag="important">Important</span>
                </div>

                <label for="new-tag">Créer un nouveau tag :</label>
                <input type="text" id="new-tag" placeholder="Nouveau tag..." />

                <!-- Champ caché pour envoyer les tags en POST -->
                <div id="hidden-tags"></div>

                <button type="submit" class="btn">Créer</button>

            </form>

        </div>

        <script>

            const taskForm = document.getElementById('task-form');
            const tagContainer = document.getElementById('tag-container');
            const availableTags = document.querySelectorAll('.tag-option');
            const newTagInput = document.getElementById('new-tag');
            const hiddenTags = document.getElementById('hidden-tags');

            let selectedTags = [];

            // Fonction pour afficher les tags sélectionnés
            function renderTags() {
                tagContainer.innerHTML = '';
                hiddenTags.innerHTML = '';

                selectedTags.forEach(tag => {
                    // Création de l'élément visuel
                    const tagEl = document.createElement('div');
                    tagEl.classList.add('tag');
                    tagEl.innerHTML = `${tag} <span class="remove-tag">&times;</span>`;

                    // Suppression au clic sur la croix
                    tagEl.querySelector('.remove-tag').addEventListener('click', () => {
                        selectedTags = selectedTags.filter(t => t !== tag);
                        renderTags();
                    });

                    tagContainer.appendChild(tagEl);

                    // Champ caché pour le formulaire
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'tags[]';
                    hiddenInput.value = tag;
                    hiddenTags.appendChild(hiddenInput);

                });

            }

            // Clic sur un tag disponible
            availableTags.forEach(el => {

                el.addEventListener('click', () => {

                    const tag = el.dataset.tag;

                    if (!selectedTags.includes(tag)) {

                        selectedTags.push(tag);

                        renderTags();

                    }

                });

            });

            // Ajout d'un nouveau tag avec Entrée
            newTagInput.addEventListener('keypress', e => {

                if (e.key === 'Enter') {

                    e.preventDefault();

                    const newTag = newTagInput.value.trim();

                    if (newTag && !selectedTags.includes(newTag)) {
                        
                        selectedTags.push(newTag);

                        renderTags();

                    }

                    newTagInput.value = '';

                }

            });

            taskForm.addEventListener('submit', function(e) {
                if (selectedTags.length === 0) {
                    e.preventDefault(); // empêche l’envoi
                    alert("Veuillez ajouter au moins un tag avant de créer la tâche.");
                }
            });

        </script>

    </body>
    
</html>
