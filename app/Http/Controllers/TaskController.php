<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Auth;
use App\Models\{
    Tag,
    Task,
    TaskType,
    TaskTag,
    TaskState,
    TaskStatus,
    TaskPriority,
    TaskSubscriber,
    User
};

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\{
    DB,
    Validator
};

use Inertia\{
    Inertia,
    Response as InertiaResponse
};

class TaskController extends Controller
{
    /**
     * render page with list of existing tasks
     *
     * @return InertiaResponse
     */
    public function index(): InertiaResponse
    {
        $orgID = Auth::user()->organization_id;

        $select = [
            'id',
            'title',
            'owner_id',
            'author_id',
            'state_id',
            'status_id',
            'priority_id',
            'type_id',
            DB::raw('updated_at AS last_updated')
        ];

        $links = [
            ['key' => 'id', 'path' => '/tasks/']
        ];

        $with = [
            'owner',
            'author',
            'state',
            'status',
            'priority',
            'type'
        ];

        $builder = Task::select($select)
            ->orderBy('last_updated', 'desc')
            ->where('organization_id', $orgID)
            ->with($with);

        if (
            isset(request()->all()['query'])
            && ($query = request()->input('query')) !== ''
        ) {
            $builder = $this->getTasksMatchingQueryBuilder($builder, $query);
        }

        if (isset(request()->all()['filter'])) {
            $builder = $this->getTasksBasedOnFilter($builder, request()->input('filter'));
        }

        $tasks = ($builder->count() > 0)
            ? $builder->paginate((int) request()->input('resultsPerPage'))
            : [];

        return Inertia::render('Tasks/List', [
            'fields'            => Task::$listFields,
            'filters'           => Task::$filters,
            'links'             => $links,
            'resultsRequestURL' => '/tasks',
            'tasks'             => $tasks
        ]);
    }

    /**
     * return builder for tasks that match query
     *
     * @param Builder $builder
     * @param string $query
     * @return Builder
     */
    private function getTasksMatchingQueryBuilder(Builder &$builder, string $query): Builder
    {
        $userIds = User::where('name', $query)
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->pluck('id')
            ->toArray();

        $statusIds = TaskStatus::where('name', $query)
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->pluck('id')
            ->toArray();

        $priorityIds = TaskPriority::where('name', $query)
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->pluck('id')
            ->toArray();

        $typeIds = TaskType::where('name', $query)
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->pluck('id')
            ->toArray();

        $stateIds = TaskState::where('name', $query)
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->pluck('id')
            ->toArray();

        return $builder->where('id', $query)
            ->orWhere('title', $query)
            ->orWhereIn('owner_id', $userIds)
            ->orWhereIn('author_id', $userIds)
            ->orWhereIn('status_id', $statusIds)
            ->orWhereIn('priority_id', $priorityIds)
            ->orWhereIn('type_id', $typeIds)
            ->orWhereIn('state_id', $stateIds)
            ->orWhere('description', $query)
            ->orWhere('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%");
    }

    /**
     * return builder for tasks based on filter
     *
     * @param Builder $builder
     * @param string $filter
     * @return Builder
     */
    private function getTasksBasedOnFilter(Builder &$builder, string $filter): Builder
    {
        if (strpos($filter, 'Priority') > -1) {
            $priority = explode(' ', $filter)[0];
            $priority = TaskPriority::where('name', $priority)->first();

            return $builder->where('priority_id', $priority->id);
        }

        if ($state = TaskState::where('name', $filter)->first()) {
            return $builder->where('state_id', $state->id);
        }

        if ($status = TaskStatus::where('name', $filter)->first()) {
            return $builder->where('status_id', $status->id);
        }

        if ($type = TaskType::where('name', $filter)->first()) {
            return $builder->where('type_id', $type->id);
        }

        return $builder;
    }

    /**
     * render task creation page
     *
     * @return InertiaResponse
     */
    public function create(): InertiaResponse
    {
        return Inertia::render(
            'Tasks/Create',
            $this->getRequiredNewTaskProperties()
        );
    }

    /**
     * render the task viewing page
     *
     * @return InertiaResponse
     */
    public function show(string $taskID): InertiaResponse
    {
        $with = [
            'owner',
            'author',
            'status',
            'priority',
            'organization',
            'parentTask',
            'type',
            'state'
        ];
        $task = Task::where('id', $taskID)->with($with)->first();
        $taskStates = TaskState::all();
        $taskStatus = TaskStatus::all();
        $orgUsers = User::where('organization_id', Auth::user()->organization_id)->get();

        return Inertia::render('Tasks/Task', [
            'task' => $task,
            'users' => $orgUsers,
            'states' => $taskStates,
            'statuses' => $taskStatus
        ]);
    }

    /**
     * submit new task payload to database
     *
     * @param Request $request
     * @return InertiaResponse
     */
    public function store(Request $request): InertiaResponse
    {
        try {
            $payload = $this->getValidatedTaskPayload($request->json()->all());
            $this->submitNewTaskPayload($payload);
        } catch (Exception $e) {
            $properties = array_merge(
                ['error' => $e->getMessage()],
                $this->getRequiredNewTaskProperties()
            );

            return Inertia::render(
                'Tasks/Create',
                $properties
            );
        }

        $properties = array_merge(
            ['result' => 'New task created successfully.'],
            $this->getRequiredNewTaskProperties()
        );

        return Inertia::render(
            'Tasks/Create',
            $properties
        );
    }

    /**
     * submit new task, task subscribers, and task tags to database
     *
     * @param array $payload
     * @return void
     */
    private function submitNewTaskPayload(array $payload): void
    {
        DB::transaction(function () use ($payload): void {
            $task = Task::create([
                'title'           => $payload['title'],
                'owner_id'        => $payload['owner'],
                'target_date'     => $payload['date'],
                'description'     => $payload['description'],
                'author_id'       => Auth::id(),
                'status_id'       => 1,
                'state_id'        => 1,
                'priority_id'     => $payload['priority'],
                'type_id'         => $payload['type'],
                'organization_id' => Auth::user()->organization_id,
                'parent_id'       => $payload['parent'] ?? NULL,
                'link'            => $payload['link'] ?? NULL
            ]);

            TaskTag::create([
                'task_id' => $task->id,
                'tag_id'  => $payload['tags']
            ]);

            TaskSubscriber::create([
                'task_id' => $task->id,
                'user_id' => $payload['subscribers']
            ]);
        });
    }

    /**
     * return validated payload or throw exception
     *
     * @param array $task
     * @return array
     */
    private function getValidatedTaskPayload(array $task): array
    {
        $errorMessages = [
            'exists'         => 'The :attribute value does not exist.',
            'required'       => 'The :attribute field is required.',
            'string'         => 'The :attribute field must be a string.',
            'after_or_equal' => 'The :attribute value is not valid.'
        ];

        $validator = Validator::make($task, [
            'owner'       => 'required|exists:users,id',
            'title'       => 'required|string',
            'description' => 'required|string',
            'link'        => 'string|nullable',
            'parent'      => 'exists:tasks,id|nullable',
            'priority'    => 'required|exists:task_priorities,id',
            'subscribers' => 'exists:users,id|nullable',
            'date'        => 'after_or_equal:today|nullable',
            'tags'        => 'exists:tags,id|nullable',
            'type'        => 'exists:task_types,id|nullable'
        ], $errorMessages);

        if ($validator->fails()) {
            $exceptionMsgs = [];

            foreach ($validator->errors()->getMessages() as $msgs) {
                foreach ($msgs as $msg) {
                    $exceptionMsgs[] = $msg;
                }
            }

            throw new Exception(json_encode($exceptionMsgs));
        }

        return $task;
    }

    /**
     * get properties required for the new task page
     *
     * @return array
     */
    private function getRequiredNewTaskProperties(): array
    {
        return [
            'tasks'      => Task::getAllAsOptions(),
            'users'      => User::getAllAsOptions(),
            'tags'       => Tag::getAllAsOptions(),
            'types'      => TaskType::getAllAsOptions(),
            'priorities' => TaskPriority::getAllAsOptions()
        ];
    }
}
