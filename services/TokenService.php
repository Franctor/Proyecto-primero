<?php
namespace services;
use repositories\RepoToken;
class TokenService
{
    private $repoToken;
    public function __construct()
    {
        $this->repoToken = new RepoToken();
    }
    public function saveToken($userId, $token)
    {
        $this->deleteToken($userId);
        return $this->repoToken->saveByUserId($token, $userId);
    }
    public function deleteToken($userId)
    {
        return $this->repoToken->deleteByUsuarioId($userId);
    }
}