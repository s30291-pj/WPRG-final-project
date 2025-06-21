<?php
require_once 'User.php';
enum CommentStatus: string
{
    case ACTIVE = 'ACTIVE';
    case DEACTIVE = 'DEACTIVE';
}

class Comment
{
    private string $uuid;
    private User $user;
    private string $content;
    private CommentStatus $status;

    public function __construct(string $uuid, User $user, string $content, CommentStatus $status)
    {
        $this->uuid = $uuid;
        $this->user = $user;
        $this->content = $content;
        $this->status = $status;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getStatus(): CommentStatus
    {
        return $this->status;
    }

    public function setStatus(CommentStatus $status): void
    {
        $this->status = $status;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
?>