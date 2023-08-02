<?php

namespace App\Tasks;

use App\Repositories\QueuesRepository;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\App;
use Throwable;
use Exception;

abstract class Task
{
	public const STATUS_PENDING = 0;
	public const STATUS_ERROR = 1;
	public const STATUS_PROCESSING = 2;
	public const STATUS_COMPLETE = 3;
	public const STATUS_EXPIRED = 4;
	public const ERROR_MESSAGE_TIMEOUT = 'timeout::many time processing';

	private QueuesRepository $queuesRepository;

	public function __construct()
	{
		$this->queuesRepository = App::make(QueuesRepository::class);
	}

	public function changeStatus(array $message, string $status, string $errorMessage = null, string $response = ''): Task
	{
		$this->queuesRepository->updateQueueStatus($message, $status, $errorMessage, $response);
		return $this;
	}

	/**
	 * @throws Throwable
	 */
	public function handleJob(array $data, string $idMessagem): Task
	{
		try {
			$this->changeStatus($data, self::STATUS_PROCESSING);
			$response = $this->handle($data);
			$this->changeStatus($data, self::STATUS_COMPLETE, null, json_encode($response));
		} catch (Throwable $throwable) {
			$this->changeStatus($data, self::STATUS_ERROR, $throwable->getMessage() . $throwable->getTraceAsString());
			throw $throwable;
		}
		return $this;
	}

	/**
	 * @throws Throwable
	 */
	public function handleJobWebServer(array $data, ?string $idMessagem): array
	{
		try {
			$idMessagem ? $this->changeStatus($data, self::STATUS_PROCESSING) : null;
			$response = $this->handle($data);
			$idMessagem ? $this->changeStatus($data, self::STATUS_COMPLETE, null, json_encode($response)) : null;
		} catch (ClientException $exception) {
			$idMessagem ? $this->changeStatus($data, self::STATUS_ERROR) : null;
			throw new Exception($exception->getResponse()->getBody()->getContents(), 500, $exception);
		} catch (Throwable $throwable) {
			$idMessagem ? $this->changeStatus($data, self::STATUS_ERROR) : null;
			throw $throwable;
		}

		return $response;
	}

	public abstract function handle(array $data): array;
}
