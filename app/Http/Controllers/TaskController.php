<?php

namespace App\Http\Controllers;

use App\Services\ProcessJobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
	public function index(Request $request): ?JsonResponse
	{
		$processJob = new ProcessJobService(['data' => ['job' => $request->all()]]);

		return new JsonResponse($processJob->handleWebServer());
	}
}
