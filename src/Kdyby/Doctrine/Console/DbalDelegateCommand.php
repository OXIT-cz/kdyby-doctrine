<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\Doctrine\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command Delegate.
 *
 * @author Fabio B. Silva <fabio.bat.silva@gmail.com>
 * @author Filip Procházka <filip@prochazka.su>
 */
abstract class DbalDelegateCommand extends Command
{

	use \Kdyby\StrictObjects\Scream;

	/**
	 * @var \Symfony\Component\Console\Command\Command
	 */
	protected $command;

	/**
	 * @var \Kdyby\Doctrine\Console\MutableConnectionProvider|NULL
	 */
	private $connectionProvider;

	/**
	 * @return \Symfony\Component\Console\Command\Command
	 */
	abstract protected function createCommand();

	/**
	 * Some doctrine/dbal console commands need a ConnectionProvider
	 * passed to their constructor, at a time (createCommand(), called from configure()) when the
	 * connection to use isn't known yet. This provider is filled in later by wrapCommand().
	 *
	 * @return \Kdyby\Doctrine\Console\MutableConnectionProvider
	 */
	protected function getConnectionProvider()
	{
		if ($this->connectionProvider === NULL) {
			$this->connectionProvider = new MutableConnectionProvider();
		}

		return $this->connectionProvider;
	}

	/**
	 * @param string[]|bool|string|null $connectionName
	 * @return \Symfony\Component\Console\Command\Command
	 */
	protected function wrapCommand($connectionName)
	{
		$connection = CommandHelper::setApplicationConnection($this->getHelper('container'), $connectionName);
		$this->getConnectionProvider()->setConnection($connection);
		$this->command->setApplication($this->getApplication());
		return $this->command;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function configure(): void
	{
		$this->command = $this->createCommand();

		$this->setName($this->command->getName());
		$this->setHelp($this->command->getHelp());
		$this->setDefinition($this->command->getDefinition());
		$this->setDescription($this->command->getDescription());
		if (!$this->getDefinition()->hasOption('connection')) {
			$this->addOption('connection', NULL, InputOption::VALUE_OPTIONAL, 'The connection to use for this command');
		}
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		return $this->wrapCommand($input->getOption('connection'))->execute($input, $output);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function interact(InputInterface $input, OutputInterface $output): void
	{
		$this->wrapCommand($input->getOption('connection'))->interact($input, $output);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function initialize(InputInterface $input, OutputInterface $output): void
	{
		$this->wrapCommand($input->getOption('connection'))->initialize($input, $output);
	}
}
