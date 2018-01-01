<?php

namespace Authenticator\Drivers;

use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;


/**
 * Class ArrayDriver
 *
 * @author  geniv
 * @package Authenticator\Drivers
 */
class ArrayDriver implements IAuthenticator
{
    /** @var array */
    private $userlist;


    /**
     * ArrayDriver constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->userlist = $parameters['userlist'];
    }


    /**
     * Performs an authentication against e.g. database.
     * and returns IIdentity on success or throws AuthenticationException
     *
     * @param array $credentials
     * @return IIdentity
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($login, $password) = $credentials;

        if (isset($this->userlist[$login])) {
            $user = $this->userlist[$login];
            if ($user && Passwords::verify($password, $user['hash'])) {
                unset($user['hash']);

                return new Identity($user['id'], (isset($user['role']) ? $user['role'] : null), $user);
            } else {
                throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
            }
        } else {
            throw new AuthenticationException('The credentials is incorrect.', self::IDENTITY_NOT_FOUND);
        }
    }
}
