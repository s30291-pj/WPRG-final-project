<?php

enum Role: string
{
    case User = 'USER';
    case Admin = 'ADMIN';
}

enum AccountStatus: string
{
    case UNVERIFIED = 'UNVERIFIED';
    case VERIFIED = 'VERIFIED';
    case DISABLED = 'DISABLED';
}

class Account
{
    private string $login;
    private string $email;
    private Role $role;
    private string $passwordHash;
    private AccountStatus $status;

    public function __construct(
        string $login,
        string $email,
        Role $role,
        string $passwordHash,
        AccountStatus $status
    ) {
        $this->login = $login;
        $this->email = $email;
        $this->role = $role;
        $this->passwordHash = $passwordHash;
        $this->status = $status;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getStatus(): AccountStatus
    {
        return $this->status;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function setStatus(AccountStatus $status): void
    {
        $this->status = $status;
    }
}