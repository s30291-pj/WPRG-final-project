<?php
require_once 'User.php';
require_once 'Comment.php';

enum PostStatus : string
{
    case ACTIVE = 'ACTIVE';
    case DEACTIVE = 'DEACTIVE';
}

class Post
{
    private string $title;
    private string $imageUrl;
    private string $content;
    private User $user;
    private PostStatus $status = PostStatus::ACTIVE;

    /** @var Comment[] */
    private array $comments = [];

    public function __construct(string $title = '', string $imageUrl = '', string $content = '', User $user = null, array $comments = [])
    {
        $this->title = $title;
        $this->imageUrl = $imageUrl;
        $this->content = $content;
        $this->user = $user;
        $this->comments = $comments;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param Comment[] $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }

    public function addComment(Comment $comment): void
    {
        $this->comments[] = $comment;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
    
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
    
    public function getContent(): string
    {
        return $this->content;
    }
    
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
    
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getStatus(): PostStatus
    {
        return $this->status;
    }
    
    public function setStatus(PostStatus $status): void
    {
        $this->status = $status;
    }
}
