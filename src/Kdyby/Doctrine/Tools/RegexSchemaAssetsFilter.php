<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Tools;

use Doctrine\DBAL\Schema\AbstractAsset;

/**
 * Replacement for the removed Doctrine\DBAL\Configuration::setFilterSchemaAssetsExpression().
 */
final class RegexSchemaAssetsFilter
{

	/**
	 * @var string
	 */
	private $regex;



	public function __construct(string $regex)
	{
		$this->regex = $regex;
	}



	/**
	 * @param string|AbstractAsset $asset
	 */
	public function __invoke($asset): bool
	{
		if ($asset instanceof AbstractAsset) {
			$asset = $asset->getName();
		}

		return preg_match($this->regex, $asset) === 1;
	}

}
