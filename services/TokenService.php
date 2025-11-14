<?php
namespace services;
use repositories\RepoToken;
class TokenService
{
    public function saveToken($userId, $token)
    {
        $repoToken = new RepoToken();
        $this->deleteToken($userId);
        return $repoToken->saveByUserId($token, $userId);
    }
    public function deleteToken($userId)
    {
        $repoToken = new RepoToken();
        return $repoToken->deleteByUsuarioId($userId);
    }
}