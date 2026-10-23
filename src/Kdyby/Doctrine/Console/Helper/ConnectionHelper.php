<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Console\Helper;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Helper\Helper;

/**
 * Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper was removed from doctrine/dbal,
 * this is a drop-in replacement kept for BC with commands that fetch the connection
 * via $this->getHelper('db')->getConnection().
 */
class ConnectionHelper extends Helper
{

	/**
	 * @var \Doctrine\DBAL\Connection
	 */
	private $connection;



	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}



	/**
	 * @return \Doctrine\DBAL\Connection
	 */
	public function getConnection()
	{
		return $this->connection;
	}



	public function getName(): string
	{
		return 'connection';
	}

}
