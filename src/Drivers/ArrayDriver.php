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
     * @return IIdentity
     * @throws AuthenticationException
     */
    function authenticate(array $credentials)
    {
        list($login, $password) = $credentials;

        $resultId = array_filter($this->userlist, function ($row) use ($login, $password) {
            return ($row['login'] === $login && Passwords::verify($password, $row['hash']));
        });

        if ($resultId) {
            if (isset($this->userlist[$resultId])) {
                $arr = $this->userlist[$resultId];
                unset($arr['hash']);

                return new Identity($resultId, (isset($arr['role']) ? $arr['role'] : null), $arr);
            }
        } else {
            throw new AuthenticationException('The credentials is incorrect.', self::IDENTITY_NOT_FOUND);
        }
    }
}
