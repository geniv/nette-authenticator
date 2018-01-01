<?php

namespace Authenticator\Drivers;

use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\IIdentity;


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
    /** @var array */
    private $drivers = [];


    /**
     * CombineDriver constructor.
     *
     * @param array       $parameters
     * @param ArrayDriver $arrayDriver
     * @param NeonDriver  $neonDriver
     * @param DibiDriver  $dibiDriver
     */
    public function __construct(array $parameters, ArrayDriver $arrayDriver, NeonDriver $neonDriver, DibiDriver $dibiDriver)
    {
        $this->parameters = $parameters;

        $this->drivers = [
            'Array' => $arrayDriver,
            'Neon'  => $neonDriver,
            'Dibi'  => $dibiDriver,
        ];
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
        $identity = null;
        $lastException = null;
        foreach ($this->parameters['combineOrder'] as $driver) {
            try {
                $identity = $this->drivers[$driver]->authenticate($credentials);
                if ($identity) {
                    $identity->driver = $driver;    // set identity data to key driver
                    return $identity;
                }
            } catch (AuthenticationException $e) {
                $lastException = $e;
            }
        }

        if (!$identity && $lastException) {
            throw $lastException;
        }
    }
}
