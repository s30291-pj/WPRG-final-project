<?php 
require_once __DIR__.'/../include/core.php';
require __DIR__.'/../template/header.php';

$posts = getAllPosts();

?>

<div class="d-flex flex-column align-self-center border border-1 rounded-3 bg-body mt-4 w-100">
    <header class="d-flex justify-content-between p-4 border-bottom bg-light bg-opacity-75 rounded-top-3">
        <h3 class="p-0 m-0">WSZYSTKIE WPISY</h3>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createPostmodal">
            <i class="fa-regular fa-add"></i>
            DODAJ WPIS
        </button>
        
        <div class="modal fade" id="createPostmodal" tabindex="-1" aria-labelledby="createPostmodal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="width: 600px;">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">DODAJ WPIS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body gap-2">
                        <div class="d-flex flex-row gap-2">
                            <input type="text" placeholder="Tytuł" class="form-control" required>
                            <input id="file-input" type="file" class="form-control">
                        </div>
                        <textarea placeholder="Opis" class="form-control mt-2" required></textarea>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </form>
                </div>
            </div>
            </div>
        </div>
    </header>
    <div class="d-flex flex-column p-3 gap-3">
        <div class="d-flex flex-row gap-2">
            <input placeholder="Wyszukaj wpis..." class="form-control border" type="search">
            <select class="form-select border" style="width: 300px; flex-shrink: 0;">
                <option>Najnowsze</option>
                <option>Najstarsze</option>
                <option>Najbardziej Lubiane</option>
                <option>Najmniej Lubiane</option>
            </select>
        </div>
        <?php foreach ($posts as $post): ?>
        <div class="d-flex flex-row border border-1" style="border-radius: var(--bs-border-radius) 30px 30px var(--bs-border-radius);">
            <div class="p-4 d-flex flex-column align-items-start justify-content-start flex-grow-1" style="width: 100%; height: 100%;">
                <span class="badge text-bg-danger d-flex flex-row gap-1 align-items-center">
                    <img src="https://api.dicebear.com/9.x/identicon/svg?seed=<?= urlencode($post->getUser()->getName()) ?>" class="bg-body rounded-circle me-1" style="width: 16px;"> 
                    <?= htmlspecialchars($post->getUser()->getName()) ?>
                </span>
                <a href="#" class="link-dark link-underline link-underline-opacity-0 link-underline-opacity-75-hover">
                    <h3 class="mt-2"><?= htmlspecialchars($post->getTitle()) ?></h3>
                </a>
                <p class="mt-3" style="text-align: justify;">
                    <?= nl2br(htmlspecialchars($post->getContent())) ?>
                </p>
                <a class="link-secondary link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="">Pokaż więcej...</a>
                <div class="d-flex flex-row align-content-between align-items-end mt-5 w-100">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-regular fa-thumbs-up text-danger"></i></span>
                        <button class="input-group-text text-bg-light hover-shadow bg-white">0</button>
                    </div>
                    <a class="text-secondary fw-normal text-decoration-none text-nowrap p-0 m-0" href="">Komentarze (<?= count($post->getComments()) ?>)</a>
                </div>
            </div>
            <div class="position-relative" style="width: 300px; height: 100%; background-color: rgb(224, 216, 216); border-radius: 0px 30px 30px 0px;">
                <span class="badge text-bg-danger position-absolute shadow-lg fs-6" style="z-index: 3; left: -20px; top: 40px;">
                    <!-- Data utworzenia, jeśli dostępna -->
                </span>
                <img src="<?= htmlspecialchars($post->getImageUrl() ?: 'https://picsum.photos/1200/600') ?>" style="object-fit: cover; width: 300px; height: 100%; border-radius: 0px 30px 30px 0px; filter: brightness(0.8)">
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php 
require __DIR__.'/../template/footer.php';
?>