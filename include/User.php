<?php

enum UserStatus : string
{
    case ACTIVE = 'ACTIVE';
    case DEACTIVE = 'DEACTIVE';
}

class User
{
    private string $uuid;
    private string $name;
    private UserStatus $status;

    public function __construct(string $uuid, string $name, UserStatus $status)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->status = $status;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function setStatus(UserStatus $status): void
    {
        $this->status = $status;
    }
}