<?php

namespace Authenticator\Drivers;

use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;


/**
 * Class CombineDriver
 *
 * @author  geniv
 * @package Authenticator\Drivers
 */
class CombineDriver implements IAuthenticator
{
    /** @var array */
    private $parameters;


    /**
     * ArrayDriver constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
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

        // switch driver by order

//        $resultId = array_filter($this->userlist, function ($row) use ($login, $password) {
//            return ($row['login'] === $login && Passwords::verify($password, $row['hash']));
//        });
//
//        if ($resultId) {
//            if (isset($this->userlist[$resultId])) {
//                $arr = $this->userlist[$resultId];
//                unset($arr['hash']);
//
//                return new Identity($resultId, $arr['role'], $arr);
//            }
//        } else {
//            throw new AuthenticationException('The credentials is incorrect.', self::IDENTITY_NOT_FOUND);
//        }
    }
}