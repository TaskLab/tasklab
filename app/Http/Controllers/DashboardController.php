<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Auth;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Http\{
    Request,
    JsonResponse
};

class DashboardController extends Controller
{
    /**
     * get organization and user-related task stats
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrgUserStats(Request $request): JsonResponse
    {
        $tasks = Task::where('organization_id', Auth::user()->organization_id)->get();
        $taskStats = $this->getPriorityBasedTaskStats($tasks);

        return response()->json([
            'stats' => $taskStats
        ]);
    }

    /**
     * get array of tasks organized by priority
     *
     * @param Collection $tasks
     * @return array
     */
    private function getPriorityBasedTaskStats(Collection $tasks): array
    {
        $distTasks = [
            'assigned'     => [0, 0, 0, 0],
            'assignedToMe' => [0, 0, 0, 0],
            'unassigned'   => [0, 0, 0, 0]
        ];
        $priorityMap = [4 => 0, 3 => 1, 2 => 2, 1 => 3];

        foreach ($tasks as $task) {
            $priorityIndex = $priorityMap[$task->priority_id];

            if ($task->owner_id === Auth::id()) {
                $distTasks['assignedToMe'][$priorityIndex] += 1;
                continue;
            }

            if ($task->owner_id === null) {
                $distTasks['unassigned'][$priorityIndex] += 1;
                continue;
            }

            $distTasks['assigned'][$priorityIndex] += 1;
        }

        return $distTasks;
    }
}
