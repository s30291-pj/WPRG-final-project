<?php
require 'header.php';

$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'newest';
$params = [':uid' => $_SESSION['user_id'] ?? 0];

$sql = 'SELECT p.*, u.username,
 (SELECT COUNT(*) FROM likes l WHERE l.post_id=p.id) AS likes,
 (SELECT COUNT(*) FROM likes l2 WHERE l2.post_id=p.id AND l2.user_id=:uid) AS liked
 FROM posts p JOIN users u ON p.user_id=u.id';

if ($search) {
    $sql .= ' WHERE p.title LIKE :s';
    $params[':s'] = "%$search%";
}
switch ($sort) {
    case 'oldest': $sql .= ' ORDER BY p.created_at ASC'; break;
    case 'likes_desc': $sql .= ' ORDER BY likes DESC'; break;
    case 'likes_asc': $sql .= ' ORDER BY likes ASC'; break;
    default: $sql .= ' ORDER BY p.created_at DESC';
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>

<div class="d-flex flex-column align-self-center border border-1 rounded-3 bg-body mt-4 w-100">
    <header class="d-flex justify-content-between p-4 border-bottom bg-light bg-opacity-75 rounded-top-3">
        <h3>WSZYSTKIE WPISY</h3>
        <?php if (!empty($_SESSION['user_id'])): ?>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createPostmodal">
            <i class="fa-regular fa-add"></i> DODAJ WPIS
        </button>
        <?php endif; ?>
        <div class="modal fade" id="createPostmodal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="width:600px">
                <div class="modal-header"><h5>DODAJ WPIS</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                <form action="create_post.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input name="title" class="form-control mb-2" placeholder="Tytuł" required>
                    <input type="file" name="image" class="form-control mb-2">
                    <textarea name="content" class="form-control" placeholder="Opis" required></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
                </form>
            </div></div>
        </div>
    </header>
    <div class="d-flex flex-column p-3 gap-3">
        <form class="d-flex gap-2 mb-3" method="get">
            <input name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Wyszukaj wpis..." class="form-control">
            <select name="sort" class="form-select" style="width:300px">
                <option value="newest" <?php if($sort=='newest')echo'selected';?>>Najnowsze</option>
                <option value="oldest" <?php if($sort=='oldest')echo'selected';?>>Najstarsze</option>
                <option value="likes_desc" <?php if($sort=='likes_desc')echo'selected';?>>Najbardziej Lubiane</option>
                <option value="likes_asc" <?php if($sort=='likes_asc')echo'selected';?>>Najmniej Lubiane</option>
            </select>
            <button class="btn btn-outline-secondary">Filtruj</button>
        </form>
        <?php foreach($posts as $p): ?>
        <div id="post-<?php echo $p['id']; ?>" class="d-flex flex-row border border-1 mb-3" style="border-radius: var(--bs-border-radius) 30px 30px var(--bs-border-radius);">
            <div class="p-4 d-flex flex-column align-items-start justify-content-start flex-grow-1">
                <span class="badge text-bg-danger d-flex flex-row gap-1 align-items-center">
                    <img src="https://api.dicebear.com/9.x/identicon/svg?seed=<?php echo urlencode($p['username']); ?>" class="bg-body rounded-circle me-1" style="width: 16px;">
                    <?php echo htmlspecialchars($p['username']); ?>
                </span>
                
                <h3 class="text-dark mt-2"><?php echo htmlspecialchars($p['title']); ?></h3>

                <div class="post-content mt-3 w-100">
                    <p id="preview-<?php echo $p['id']; ?>" style="text-align: justify;">
                        <?php echo nl2br(htmlspecialchars(strlen($p['content'])>200
                            ? substr($p['content'],0,200).'...'
                            : $p['content']
                        )); ?>
                        <?php if(strlen($p['content']) > 200): ?>
                            <a href="#" class="link-secondary toggle-content" data-id="<?php echo $p['id']; ?>" data-state="preview">Pokaż więcej...</a>
                        <?php endif; ?>
                    </p>
                    <p id="full-<?php echo $p['id']; ?>" class="d-none" style="text-align: justify;">
                        <?php echo nl2br(htmlspecialchars($p['content'])); ?>
                        <a href="#" class="link-secondary toggle-content" data-id="<?php echo $p['id']; ?>" data-state="full">Zwiń</a>
                    </p>
                </div>
                <div class="d-flex flex-row align-items-end mt-3 w-100 flex-grow-1">
                    <div class="input-group">
                        <?php if(!empty($_SESSION['user_id'])): ?>
                            <button type="button" class="input-group-text" onclick="window.location.href='like.php?post_id=<?php echo $p['id']; ?>'">
                                <i class="<?php echo $p['liked'] ? 'fa-solid' : 'fa-regular'; ?> fa-thumbs-up text-danger"></i>
                            </button>
                        <?php else: ?>
                            <span class="input-group-text"><i class="fa-regular fa-thumbs-up text-danger"></i></span>
                        <?php endif; ?>
                        <span class="input-group-text bg-white"><?php echo $p['likes']; ?></span>
                    </div>
                    <?php if(!empty($_SESSION['user_id']) && $_SESSION['user_id']==$p['user_id']): ?>
                        <div class="d-flex gap-2 ms-3">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editPostModal-<?php echo $p['id']; ?>">Edytuj</button>
                            <a href="delete_post.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-danger">Usuń</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="position-relative" style="width: 300px; min-height: 100%; background-color: rgb(205, 199, 199); border-radius: 0px 30px 30px 0px;">
                <span class="badge text-bg-danger position-absolute shadow-lg fs-6" style="z-index: 3; left: -20px; top: 40px;">
                    <?php echo date('d.m.Y H:i', strtotime($p['created_at'])); ?>
                </span>
                <?php if($p['image_path']): ?>
                    <img src="image.php?id=<?php echo $p['id']; ?>" style="object-fit: cover; width: 300px; height: 100%; border-radius: 0px 30px 30px 0px; filter: brightness(0.8)">
                <?php else: ?>
                    <img src="./assets/pjatk-logo.png" style="object-fit: cover; width: 300px; height: 100%; border-radius: 0px 30px 30px 0px; filter: saturate(20%) brightness(0.8)">
                <?php endif; ?>
            </div>
        </div>

        <?php if(!empty($_SESSION['user_id']) && $_SESSION['user_id']==$p['user_id']): ?>
            <div class="modal fade" id="editPostModal-<?php echo $p['id']; ?>" tabindex="-1" aria-labelledby="editPostModalLabel-<?php echo $p['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="width: 600px;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPostModalLabel-<?php echo $p['id']; ?>">Edytuj wpis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                        </div>
                        <form action="edit_post.php?id=<?php echo $p['id']; ?>" method="post">
                            <div class="modal-body">
                                <input type="text" name="title" class="form-control mb-2" placeholder="Tytuł" value="<?php echo htmlspecialchars($p['title']); ?>" required>
                                <textarea name="content" class="form-control" placeholder="Opis" required><?php echo htmlspecialchars($p['content']); ?></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                                <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('.toggle-content').forEach(function(link){
                link.addEventListener('click', function(e){
                    e.preventDefault();
                    var id = this.dataset.id;
                    var state = this.dataset.state;
                    var preview = document.getElementById('preview-'+id);
                    var full    = document.getElementById('full-'+id);
                    
                    if(state==='preview'){
                        preview.classList.add('d-none');
                        full.classList.remove('d-none');
                    } else {
                        full.classList.add('d-none');
                        preview.classList.remove('d-none');
                    }
                });
            });
        });
    </script>
    </div>
</div>
<?php require 'footer.php'; ?>
