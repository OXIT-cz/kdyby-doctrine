<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Console;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Tools\Console\ConnectionNotFound;
use Doctrine\DBAL\Tools\Console\ConnectionProvider;

/**
 * Doctrine\DBAL console commands now require a ConnectionProvider
 * to be passed to their constructor, at a point where the delegate command doesn't know yet
 * which connection (--connection option) will be used. This provider is mutable, so the
 * delegate command can fill in the connection once it is known, right before execution.
 */
final class MutableConnectionProvider implements ConnectionProvider
{

	/**
	 * @var \Doctrine\DBAL\Connection|NULL
	 */
	private $connection;



	public function setConnection(Connection $connection)
	{
		$this->connection = $connection;
	}



	public function getDefaultConnection(): Connection
	{
		if ($this->connection === NULL) {
			throw new ConnectionNotFound('No connection has been set yet.');
		}

		return $this->connection;
	}



	public function getConnection(string $name): Connection
	{
		return $this->getDefaultConnection();
	}

}
