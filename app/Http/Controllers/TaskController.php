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
            'status_id',
            'priority_id',
            'type_id',
            DB::raw('updated_at AS last_updated')
        ];

        $fields = [
            'id'            => 'ID',
            'title'         => 'Title',
            'owner.name'    => 'Owner',
            'author.name'   => 'Author',
            'status.name'   => 'Status',
            'priority.name' => 'Priority',
            'type.name'     => 'Type',
            'last_updated'  => 'Last Updated'
        ];

        $links = [
            ['key' => 'id', 'path' => '/tasks/']
        ];

        $with = [
            'owner',
            'author',
            'status',
            'priority',
            'type'
        ];

        $builder = Task::select($select)
            ->where('organization_id', $orgID)
            ->with($with);

        if (
            isset(request()->all()['query'])
            && (($query = request()->input('query')) !== '')
        ) {
            $builder->where('id', $query)
                ->orWhere('title', $query)
                ->orWhere('description', $query)
                ->orWhere('title', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%");
        }

        $tasks = ($builder->count() > 0)
            ? $builder->paginate((int) request()->input('resultsPerPage'))
            : [];

        return Inertia::render('Tasks/List', [
            'fields'            => $fields,
            'links'             => $links,
            'resultsRequestURL' => '/tasks',
            'tasks'             => $tasks
        ]);
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
            'relatedTask',
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
            'date'        => 'required|after_or_equal:today',
            'tags'        => 'exists:tags,id|nullable',
            'type'        => 'required|exists:task_types,id'
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
