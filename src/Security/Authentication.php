<?php

namespace App\Security;

use App\ApiResource\Security\FailureAuth;
use App\ApiResource\Security\SuccessAuth;
use App\Repository\TeamManagerRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Authentication
{
    public function __construct(private JWTTokenManagerInterface $tokenManager, private UserPasswordHasherInterface $hasher, private TeamManagerRepository $repository)
    {
    }

    public function auth(string $password, string $email): FailureAuth|SuccessAuth
    {
        $isValidEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$isValidEmail) {
            $response = new FailureAuth();
            $response->error = 'invalid email';
            return $response;
        }

        $foundUser = $this->repository->findOneByEmail($email);
        if (!$foundUser) {
            $response = new FailureAuth();
            $response->error = 'user not found';
            return $response;
        }

        if (!$this->hasher->isPasswordValid($foundUser, $password)) {
            $response = new FailureAuth();
            $response->error = 'invalid credential';
            return $response;
        }

        $token = $this->tokenManager->create($foundUser);

        $response = new SuccessAuth();
        $response->token = $token;
        return $response;
    }
}
