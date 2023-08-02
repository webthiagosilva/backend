<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessJobService
{
	private array $payload;
	private TaskHandledService $taskHandled;
	private ?string $idMessage;
	private string $method;
	private array $body;

	public function __construct($payload)
	{
		$this->payload = $payload;

		$this->idMessage = $payload['data']['job']['_id'] ?? null;
		$this->method = $payload['data']['job']['metodo'];
		$this->body = $this->payload['data']['job'];

		$this->taskHandled = App::make(TaskHandledService::class);

		$GLOBALS['CURRENT_JOB'] = $payload;
		Config::set('CURRENT_JOB', json_encode($payload));

		$this->setupLog();
	}

	/**
	 * @throws Throwable
	 */
	private function handleLaravelJobsWebServer(): array
	{
		return $this->taskHandled
			->execute($this->method)
			->handleJobWebServer($this->payload['data']['job'], $this->idMessage);
	}

	private function handleLaravelJobs()
	{
		return $this->taskHandled
			->execute($this->method)
			->handleJob($this->payload['data']['job'], $this->idMessage);
	}

	public function handle()
	{
		return $this->handleLaravelJobs();
	}

	public function handleWebServer()
	{
		return $this->handleLaravelJobsWebServer();
	}

	private function setupLog()
	{
		Log::withContext([
			'message:id' => $this->idMessage,
			'message:method' => $this->method,
			'message:body' => json_encode($this->body)
		])->info('Message received');
	}
}
