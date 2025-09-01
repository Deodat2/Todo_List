<?php if (isset($_SESSION['success'])): ?>

    <div class="flash-message success">

        <?= htmlspecialchars($_SESSION['success']) ?>

    </div>

    <?php unset($_SESSION['success']); ?>

<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>

    <div class="flash-message error">

        <?= htmlspecialchars($_SESSION['error']) ?>

    </div>

    <?php unset($_SESSION['error']); ?>

<?php endif; ?>

<!-- Barres de recherche -->
<div style="display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 10px; padding-right: 10px;">

    <input type="text" id="title-search" placeholder="Rechercher par titre..." 
        style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">

    <input type="text" id="tag-search" placeholder="Rechercher par tag..." 
        style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">

</div>

<div class="task-cards">
    
    <?php if (empty($tasks)): ?>

        <p>Aucune tâche trouvée.</p>

    <?php else: ?>

        <?php foreach ($tasks as $task): ?>

            <div class="task-card"

                data-title="<?= htmlspecialchars(strtolower($task['title'])) ?>"
                data-tags="<?= htmlspecialchars(strtolower(implode(',', $task['tags'] ?? []))) ?>">
                 
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


                <p class="task-desc" >
                    
                    <a href="/?page=tasks&subpage=subtasks&id=<?= $task['id'] ?>" class="task-desc">

                        <?= htmlspecialchars(mb_strimwidth($task['description'], 0, 80, '...')) ?>
                    
                    </a>
                
                </p>


                <div class="task-card-footer">
                    
                    <div>

                        <form method="POST" action="'/?page=tasks&subpage=delete&id=<?= $task['id'] ?>'" class="delete-form" 
                        
                            onsubmit="return confirm('Supprimer cette tâche ?');">

                            <input type="hidden" name="id" value="<?= $task['id'] ?>" />

                            <input type="hidden" name="action" value="delete" />

                            <button type="submit" class="delete-btn">🗑</button>

                        </form>

                    </div>
                    

                    <div>

                        <span><?= htmlspecialchars($task['status']) ?></span>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<script>
    // Gestion dropdown tags
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.tag-dropdown').forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        if (e.target.closest('.tag-dropdown-btn')) {
            const dropdown = e.target.closest('.tag-dropdown');
            dropdown.classList.toggle('open');
        }
    });

    // Filtrage par titre et tag
    const titleSearch = document.getElementById('title-search');
    const tagSearch = document.getElementById('tag-search');

    function filterTasks() {
        const titleValue = titleSearch.value.toLowerCase();
        const tagValue = tagSearch.value.toLowerCase();
        const cards = document.querySelectorAll('.task-card');

        cards.forEach(card => {
            const title = card.getAttribute('data-title');
            const tags = card.getAttribute('data-tags');

            const matchTitle = title.includes(titleValue) || titleValue === '';
            const matchTag = tags.includes(tagValue) || tagValue === '';

            // Afficher si les deux conditions sont respectées
            if (matchTitle && matchTag) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    titleSearch.addEventListener('input', filterTasks);
    tagSearch.addEventListener('input', filterTasks);

    document.addEventListener("DOMContentLoaded", () => {
        const flashMessages = document.querySelectorAll(".flash-message");

        flashMessages.forEach(msg => {
            setTimeout(() => {
                msg.style.opacity = "0";
                msg.style.transition = "opacity 0.5s ease";

                setTimeout(() => msg.remove(), 500); // suppression après fade-out
            }, 2000); // 2 secondes d'affichage
        });
    });
</script>
