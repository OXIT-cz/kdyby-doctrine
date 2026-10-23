<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Console;

use Kdyby\Doctrine\Console\Helper\ConnectionHelper;
use Kdyby\Console\ContainerHelper;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * @author Tomáš Jacík <tomas@jacik.cz>
 */
final class CommandHelper
{

	/**
	 * Private constructor. This class is not meant to be instantiated.
	 */
	private function __construct()
	{
	}



	/**
	 * @return \Kdyby\Doctrine\EntityManager
	 */
	public static function setApplicationEntityManager(HelperInterface $containerHelper, $emName)
	{
		if (!$containerHelper instanceof ContainerHelper) {
			throw new \LogicException(sprintf('The "container" helper must be an instance of %s.', ContainerHelper::class));
		}

		/** @var \Kdyby\Doctrine\Registry $registry */
		$registry = $containerHelper->getByType(\Kdyby\Doctrine\Registry::class);
		$em = $registry->getManager($emName);
		/** @var HelperSet|null $helperSet */
		$helperSet = $containerHelper->getHelperSet();
		if ($helperSet !== NULL) {
			$helperSet->set(new ConnectionHelper($em->getConnection()), 'db');
		}

		return $em;
	}

	/**
	 * @return \Doctrine\DBAL\Connection
	 */
	public static function setApplicationConnection(HelperInterface $containerHelper, $connName)
	{
		if (!$containerHelper instanceof ContainerHelper) {
			throw new \LogicException(sprintf('The "container" helper must be an instance of %s.', ContainerHelper::class));
		}

		/** @var \Kdyby\Doctrine\Registry $registry */
		$registry = $containerHelper->getByType(\Kdyby\Doctrine\Registry::class);
		$connection = $registry->getConnection($connName);
		/** @var HelperSet|null $helperSet */
		$helperSet = $containerHelper->getHelperSet();
		if ($helperSet !== NULL) {
			$helperSet->set(new ConnectionHelper($connection), 'db');
		}

		return $connection;
	}

}
