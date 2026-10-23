<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Diagnostics\Middleware;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Middleware\AbstractConnectionMiddleware;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;
use Kdyby\Doctrine\Diagnostics\QueryLogger;

/**
 * @internal
 */
final class LoggingConnection extends AbstractConnectionMiddleware
{

	/**
	 * @var QueryLogger
	 */
	private $logger;



	public function __construct(Connection $connection, QueryLogger $logger)
	{
		parent::__construct($connection);
		$this->logger = $logger;
	}



	public function prepare(string $sql): Statement
	{
		return new LoggingStatement(parent::prepare($sql), $this->logger, $sql);
	}



	public function query(string $sql): Result
	{
		$this->logger->startQuery($sql);
		$result = parent::query($sql);
		$this->logger->stopQuery();

		return $result;
	}



	public function exec(string $sql): int
	{
		$this->logger->startQuery($sql);
		$affected = parent::exec($sql);
		$this->logger->stopQuery();

		return $affected;
	}

}
