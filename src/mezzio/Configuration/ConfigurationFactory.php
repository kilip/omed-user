<?php

/*
 * This file is part of the Omed User project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Omed\User\Mezzio\Configuration;

use Omed\User\Contracts\Model\UserInterface;
use Omed\User\Mezzio\Contracts\ConfigurationInterface;
use Omed\User\Mezzio\Exception\ObjectManagerException;
use Omed\User\Mezzio\Exception\UserClassException;
use Psr\Container\ContainerInterface;

class ConfigurationFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @throws UserClassException     on invalid User class configuration
     * @throws ObjectManagerException when object manager is not found
     *
     * @return Configuration
     */
    public function __invoke(ContainerInterface $container): Configuration
    {
        $config = $container->get('config')[ConfigurationInterface::CONFIG_KEY];
        $ob     = new Configuration($config);

        $this->ensureUserClass($ob);
        $this->configureObjectManager($container, $ob);

        return $ob;
    }

    /**
     * @param ContainerInterface $container
     * @param Configuration      $ob
     *
     * @throws ObjectManagerException
     */
    private function configureObjectManager(ContainerInterface $container, Configuration $ob): void
    {
        if ($container->has($class ='Doctrine\\ORM\\EntityManager')) {
            $ob->setObjectManagerClass($class);
        } elseif ($container->has($class = 'Doctrine\\ODM\\MongoDB\\DocumentManager')) {
            $ob->setObjectManagerClass($class);
        } else {
            throw ObjectManagerException::unknownClass();
        }
    }

    /**
     * @param Configuration $ob
     *
     * @throws UserClassException
     */
    private function ensureUserClass(Configuration $ob)
    {
        $class = $ob->getUserClass();
        if (null === $class) {
            throw UserClassException::undefined();
        }

        if ( ! class_exists($class)) {
            throw UserClassException::classNotExists($class);
        }

        $implements = class_implements($class);
        \assert(\is_array($implements));
        if ( ! \in_array(UserInterface::class, $implements, true)) {
            throw UserClassException::incompatible($class);
        }
    }
}
