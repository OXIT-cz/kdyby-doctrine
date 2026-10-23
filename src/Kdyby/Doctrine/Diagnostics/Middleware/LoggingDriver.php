<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Diagnostics\Middleware;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;
use Kdyby\Doctrine\Diagnostics\QueryLogger;
use SensitiveParameter;

/**
 * @internal
 */
final class LoggingDriver extends AbstractDriverMiddleware
{

	/**
	 * @var QueryLogger
	 */
	private $logger;



	public function __construct(Driver $driver, QueryLogger $logger)
	{
		parent::__construct($driver);
		$this->logger = $logger;
	}



	public function connect(
		#[SensitiveParameter]
		array $params
	) {
		return new LoggingConnection(parent::connect($params), $this->logger);
	}

}
