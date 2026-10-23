<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Console\Command;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Task for executing arbitrary SQL that can come from a file.
 *
 * Doctrine\DBAL\Tools\Console\Command\ImportCommand was removed from doctrine/dbal without
 * a replacement, this is a driver-agnostic reimplementation kept for BC.
 */
class ImportCommand extends Command
{

	protected function configure(): void
	{
		$this
			->setName('dbal:import')
			->setDescription('Import SQL file(s) directly to Database.')
			->setDefinition([
				new InputArgument('file', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'File path(s) of SQL to be executed.'),
			])
			->setHelp('Import SQL file(s) directly to Database.');
	}



	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$dbHelper = $this->getHelper('db');
		if (!$dbHelper instanceof \Kdyby\Doctrine\Console\Helper\ConnectionHelper) {
			throw new RuntimeException(sprintf('The "db" helper must be an instance of %s.', \Kdyby\Doctrine\Console\Helper\ConnectionHelper::class));
		}
		$conn = $dbHelper->getConnection();

		foreach ((array) $input->getArgument('file') as $fileName) {
			$filePath = realpath($fileName) ?: $fileName;

			if (!file_exists($filePath)) {
				throw new InvalidArgumentException(sprintf("SQL file '<info>%s</info>' does not exist.", $filePath));
			}

			if (!is_readable($filePath)) {
				throw new InvalidArgumentException(sprintf("SQL file '<info>%s</info>' does not have read permissions.", $filePath));
			}

			$output->write(sprintf("Processing file '<info>%s</info>'... ", $filePath));
			$sql = @file_get_contents($filePath);

			if ($sql === FALSE) {
				$error = error_get_last();
				throw new RuntimeException(sprintf("Unable to read SQL file '<info>%s</info>'", $filePath) . ($error !== NULL ? ': ' . $error['message'] : ''));
			}

			// PDO drivers don't support running multiple statements at once,
			// so they're executed one by one, inside a single transaction.
			$queries = explode(';', $sql);
			array_pop($queries); // the part after the last semicolon is empty (or whitespace)

			$conn->beginTransaction();
			try {
				$statements = 0;
				foreach ($queries as $query) {
					if (trim($query) === '') {
						continue;
					}
					$conn->executeStatement($query);
					$statements++;
				}
				$conn->commit();

			} catch (\Exception $e) {
				$conn->rollBack();
				$output->write('error!' . PHP_EOL);
				throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
			}

			$output->write(sprintf('%d statements executed!', $statements) . PHP_EOL);
		}

		return 0;
	}

}
