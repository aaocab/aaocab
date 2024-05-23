<?php

namespace components\AsyncPool;
use Spatie\Async;
use Spatie\Async\Process\Runnable;

class Pool extends Async\Pool
{

	/**
     * @return static
     */
    public static function create()
    {
        return new static();
    }
	
	/**
	 * @param \Spatie\Async\Process\Runnable|callable $process
	 * @param int|null $outputLength
	 *
	 * @return \Spatie\Async\Process\Runnable
	 */
	public function add($process, ?int $outputLength = null): Runnable
	{
		if (!is_callable($process) && !$process instanceof Runnable)
		{
			throw new InvalidArgumentException('The process passed to Pool::add should be callable.');
		}

		if (!$process instanceof Runnable)
		{
			$process = Runtime\ParentRuntime::createProcess(
							$process,
							$outputLength,
							$this->binary
			);
		}

		$this->putInQueue($process);

		return $process;
	}

	
	public function autoload(string $autoloader): self
    {
        Runtime\ParentRuntime::init($autoloader);

        return $this;
    }
}
