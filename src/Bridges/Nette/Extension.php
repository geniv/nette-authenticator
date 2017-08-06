<?php

namespace Authenticator\Bridges\Nette;

use Authenticator\DibiAuthenticator;
use Authenticator\LoginForm;
use Nette\DI\CompilerExtension;


/**
 * Class Extension
 *
 * @author  geniv
 * @package Authenticator\Bridges\Nette
 */
class Extension extends CompilerExtension
{
    /** @var array default values */
    private $defaults = [
        'tablePrefix' => null,
    ];


    /**
     * Load configuration.
     */
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        // define authenticator
        $builder->addDefinition($this->prefix('default'))
            ->setClass(DibiAuthenticator::class, [$config]);

        // define form
        $builder->addDefinition($this->prefix('form'))
            ->setClass(LoginForm::class);
    }
}
