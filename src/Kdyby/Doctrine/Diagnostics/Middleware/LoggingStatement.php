<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Diagnostics\Middleware;

use Doctrine\DBAL\Driver\Middleware\AbstractStatementMiddleware;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;
use Kdyby\Doctrine\Diagnostics\QueryLogger;

/**
 * @internal
 */
final class LoggingStatement extends AbstractStatementMiddleware
{

	/**
	 * @var QueryLogger
	 */
	private $logger;

	/**
	 * @var string
	 */
	private $sql;

	/**
	 * @var array<int|string, mixed>
	 */
	private $params = [];

	/**
	 * @var array<int|string, mixed>
	 */
	private $types = [];



	public function __construct(Statement $statement, QueryLogger $logger, string $sql)
	{
		parent::__construct($statement);
		$this->logger = $logger;
		$this->sql = $sql;
	}



	public function bindValue($param, $value, $type = ParameterType::STRING)
	{
		$this->params[$param] = $value;
		$this->types[$param] = $type;

		return parent::bindValue($param, $value, $type);
	}



	public function execute($params = NULL): Result
	{
		$this->logger->startQuery($this->sql, $params ?? $this->params, $this->types);
		$result = parent::execute($params);
		$this->logger->stopQuery();

		return $result;
	}

}
