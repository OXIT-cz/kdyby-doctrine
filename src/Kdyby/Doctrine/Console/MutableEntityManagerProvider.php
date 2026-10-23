<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Console;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\UnknownManagerException;

/**
 * Filled in by OrmDelegateCommand once the --em option is known, right before execution.
 */
final class MutableEntityManagerProvider implements EntityManagerProvider
{

	/**
	 * @var EntityManagerInterface|NULL
	 */
	private $entityManager;



	public function setEntityManager(EntityManagerInterface $entityManager): void
	{
		$this->entityManager = $entityManager;
	}



	public function getDefaultManager(): EntityManagerInterface
	{
		if ($this->entityManager === NULL) {
			throw UnknownManagerException::unknownManager('default', []);
		}

		return $this->entityManager;
	}



	public function getManager(string $name): EntityManagerInterface
	{
		return $this->getDefaultManager();
	}

}
