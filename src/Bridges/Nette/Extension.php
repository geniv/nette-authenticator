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
        'autowired'   => null,
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
            ->setFactory(DibiAuthenticator::class, [$config]);

        // define form
        $builder->addDefinition($this->prefix('form'))
            ->setFactory(LoginForm::class);

        // if define autowired then set value
        if (isset($config['autowired'])) {
            $builder->getDefinition($this->prefix('default'))
                ->setAutowired($config['autowired']);

            $builder->getDefinition($this->prefix('form'))
                ->setAutowired($config['autowired']);
        }
    }
}
