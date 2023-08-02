<?php

namespace App\Traits;

use App\Tasks\Task;
use Illuminate\Support\Facades\App;

trait TaskStubTrait
{

	public function execute(string $method): Task
	{
		return App::make($this::TASK[$method]);
	}
}
