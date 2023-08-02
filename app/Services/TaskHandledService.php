<?php

namespace App\Services;

use App\Tasks\Reports\SendCustomReportTask;
use App\Traits\TaskStubTrait;

class TaskHandledService
{
    use TaskStubTrait;

    private const TASK = [
        'REPORT_relatorio_personalizado'    => SendCustomReportTask::class,
    ];
}
