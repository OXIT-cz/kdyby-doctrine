<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Diagnostics;

/**
 * Replacement for the removed Doctrine\DBAL\Logging\SQLLogger, fed by QueryLoggingMiddleware.
 */
interface QueryLogger
{

	/**
	 * @param string $sql
	 * @param array<int|string, mixed>|null $params
	 * @param array<int|string, mixed>|null $types
	 * @return void
	 */
	public function startQuery($sql, ?array $params = NULL, ?array $types = NULL);

	/**
	 * @return mixed
	 */
	public function stopQuery();

}
