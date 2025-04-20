<?php
namespace App\Google;

use Carbon\Carbon;
use App\Google\OAuth2;
use Google_Service_Tasks;
use Google_Service_Tasks_Task;
use App\Google\Exceptions\TaskListNotFound;

class Tasks
{
    use OAuth2;

    /**
     * The task service.
     *
     * @var Google_Service_Tasks
     */
    protected $taskService;

    /**
     * Creates a new NewTaskController instance.
     */
    public function __construct()
    {
        $this->taskService = new Google_Service_Tasks($this->client());
    }

    /**
     * Adds a task.
     *
     * @param string $listTitle The list title.
     * @param string $taskTitle The task title.
     *
     * @return Google_Service_Tasks_Task The task.
     */
    public function addTask($listTitle, $taskTitle)
    {
        $list = $this->list($listTitle);
        $list_id = trim($list->getEtag(), '"');
        $task = new Google_Service_Tasks_Task();
        $task->setTitle($taskTitle);

        // Set the due date.
        $dueDate = $this->taskDueDate();
        if ($dueDate) {
            $task->setDue($dueDate);
        }

        $result = $this->taskService
                       ->tasks
                       ->insert($list->getId(), $task)
                       ->toSimpleObject();

        return [
            'etag' => $result->etag,
            'id' => $result->id,
            'kind' => $result->kind,
            'position' => $result->position,
            'selfLink' => $result->selfLink,
            'status' => $result->status,
            'title' => $result->title,
            'updated' => $result->updated,
            'due' => isset($result->due) ? $result->due : '',
        ];
    }

    /**
     * The task due date.
     *
     * @return string|boolean The date if set and false if not.
     */
    protected function taskDueDate()
    {
        $user = \Auth::user();

        if (!$user->default_due_date_enabled) {
            return false;
        }

        $hour = date('h', strtotime($user->default_due_date_time));
        $minutes = date('i', strtotime($user->default_due_date_time));

        return Carbon::now()->addDays($user->default_due_date_offset)
                                           ->setTime($hour, $minutes)
                                           ->toRfc3339String();
    }

    /**
     * Get a list by title.
     *
     * @param  string $title Title of the task list.
     *
     * @throws TaskListNotFound If a task list can not be found by the title.
     *
     * @return Google_Service_Tasks_TaskList The task list.
     */
    public function list($title)
    {
        foreach ( $this->lists()->items as $item ) {
            if (strtolower($title) === strtolower($item->getTitle())) {
                return $item;
            }
        }

        throw new TaskListNotFound("Task list \"{$title}\" not found!");
    }

    /**
     * Retrieves the authenticated users lists.
     *
     * @return array The lists.
     */
    public function lists()
    {
        $userId = \Auth::id();
        return \Cache::remember("user.{$userId}.task-lists", 10, function () {
            try {
                return $this->taskService
                    ->tasklists
                    ->listTasklists([]);
            } catch (Exception $e) {
                return [];
            }
        });
    }

    /**
     * Retrieves the Google Tasks service.
     *
     * @return Google_Service_Tasks
     */
    protected function taskService()
    {
        return $this->taskService;
    }

    /**
     * Gets the task list options
     *
     * @return array The list options.
     */
    public function listOptions()
    {
        // Make sure the user has already authorized Google tasks access.
        // If not remind them.
        $user = \Auth::user();
        if (is_null($user->google_access_token)) {
            return [ [ 'label' => 'Please Authorize Google Tasks Above', 'value' => '' ] ];
        }

        // Build up the list options.
        $listOptions = [ [ 'label' => 'Select a list', 'value' => '' ] ];
        foreach ($this->lists() as $list) {
            $listOptions[] = [ 'label' => $list->title, 'value' => $list->title ];
        }

        return $listOptions;
    }
}
