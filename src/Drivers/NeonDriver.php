<?php

namespace Authenticator\Drivers;

use Nette\Neon\Neon;


/**
 * Class NeonDriver
 *
 * @author  geniv
 * @package Authenticator\Drivers
 */
class NeonDriver extends ArrayDriver
{

    /**
     * NeonDriver constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $parameters['userlist'] = [];
        if ($parameters['path'] && file_exists($parameters['path'])) {
            $parameters['userlist'] = Neon::decode(file_get_contents($parameters['path']));
        }
        parent::__construct($parameters);
    }
}
