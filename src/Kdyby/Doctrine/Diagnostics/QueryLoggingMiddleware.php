<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Diagnostics;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Middleware;
use Kdyby\Doctrine\Diagnostics\Middleware\LoggingDriver;

/**
 * Loggers can be added after the connection was created, the middleware instance is shared.
 */
final class QueryLoggingMiddleware implements Middleware, QueryLogger
{

	/**
	 * @var QueryLogger[]
	 */
	private $loggers = [];



	public function addLogger(QueryLogger $logger): void
	{
		if (!in_array($logger, $this->loggers, TRUE)) {
			$this->loggers[] = $logger;
		}
	}



	public function wrap(Driver $driver): Driver
	{
		return new LoggingDriver($driver, $this);
	}



	public function startQuery($sql, ?array $params = NULL, ?array $types = NULL)
	{
		foreach ($this->loggers as $logger) {
			$logger->startQuery($sql, $params, $types);
		}
	}



	public function stopQuery()
	{
		foreach ($this->loggers as $logger) {
			$logger->stopQuery();
		}
	}

}
