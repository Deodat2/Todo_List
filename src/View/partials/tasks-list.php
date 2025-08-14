<?php if (!empty($_SESSION['success'])): ?>

    <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            
    <?php unset($_SESSION['success']); ?>

<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>

    <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>

    <?php unset($_SESSION['error']); ?>

<?php endif; ?>

<div class="task-cards">

    <?php if (empty($tasks)): ?>

        <p>Aucune tâche trouvée.</p>

    <?php else: ?>

        <?php foreach ($tasks as $task): ?>

            <div class="task-card">
                
                <!-- Header de la carte -->
                <div class="task-card-header">

                    <h3 class="task-title"><?= htmlspecialchars($task['title']) ?></h3>

                    <?php if (!empty($task['tags'])): ?>

                        <?php if (count($task['tags']) === 1): ?>

                            <span class="task-badge"><?= htmlspecialchars($task['tags'][0]) ?></span>

                        <?php else: ?>

                            <div class="tag-dropdown">

                                <button class="tag-dropdown-btn">¤</button>

                                <div class="tag-dropdown-menu">

                                    <?php foreach ($task['tags'] as $tag): ?>

                                        <span class="task-badge"><?= htmlspecialchars($tag) ?></span>

                                    <?php endforeach; ?>

                                </div>

                            </div>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>

                <!-- Description -->
                <p class="task-desc" onclick="window.location.href='/?page=tasks&subpage=edit&id=<?= $task['id'] ?>'">
                    
                    <?= htmlspecialchars(mb_strimwidth($task['description'], 0, 80, '...')) ?>
                
                </p>

                <!-- Footer avec bouton suppression -->
                <div class="task-card-footer">

                    <form method="POST" action="'/?page=tasks&subpage=delete&id=<?= $task['id'] ?>'" class="delete-form" 

                        onsubmit="return confirm('Supprimer cette tâche ?');">

                        <input type="hidden" name="id" value="<?= $task['id'] ?>" />

                        <input type="hidden" name="action" value="delete" />

                        <button type="submit" class="delete-btn">🗑</button>

                    </form>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<script>

    document.addEventListener('click', function(e) {

        // Fermer tous les dropdowns si clic à l'extérieur
        document.querySelectorAll('.tag-dropdown').forEach(dropdown => {

            if (!dropdown.contains(e.target)) {

                dropdown.classList.remove('open');

            }

        });

        // Ouvrir/fermer celui sur lequel on clique
        if (e.target.closest('.tag-dropdown-btn')) {

            const dropdown = e.target.closest('.tag-dropdown');

            dropdown.classList.toggle('open');

        }

    });

</script>
