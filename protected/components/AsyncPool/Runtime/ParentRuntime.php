<?php

namespace components\AsyncPool\Runtime;

use Spatie\Async\Process\Runnable;
use Spatie\Async\Process\SynchronousProcess;
use Symfony\Component\Process\Process;

class ParentRuntime extends \Spatie\Async\Runtime\ParentRuntime
{

	public static function init(string $autoloader = null)
	{
		parent::init($autoloader);
		self::$childProcessScript = __DIR__ . '/ChildRuntime.php';
	}

	/**
	 * @param \Spatie\Async\Task|callable $task
	 * @param int|null $outputLength
	 *
	 * @return \Spatie\Async\Process\Runnable
	 */
	public static function createProcess($task, ?int $outputLength = null, ?string $binary = 'php'): Runnable
	{
		if (!self::$isInitialised)
		{
			self::init();
		}

		if (!\components\AsyncPool\Pool::isSupported())
		{
			return SynchronousProcess::create($task, self::getId());
		}
		$taskString	 = self::encodeTask($task);
		$process	 = new Process([
			$binary,
			self::$childProcessScript,
			self::$autoloader,
			$taskString,
			$outputLength,
		]);

		$parallelProcess			 = \components\AsyncPool\Process\ParallelProcess::create($process, self::getId());
		$parallelProcess->identifier = md5($taskString);
		$parallelProcess->processId = $process->getPid();
		return $parallelProcess;
	}

}
