<?php
namespace App\Controllers;
use App\Models\TaskModel;
use CodeIgniter\Exceptions\PageNotFoundException;

final class Tasks extends BaseController
{
    private TaskModel $tasks;
    public function __construct() { $this->tasks = new TaskModel(); }
    public function index()
    {
        $tasks = $this->tasks->active(); $counts = ['pending' => 0, 'in_progress' => 0, 'completed' => 0];
        foreach ($tasks as $task) ++$counts[$task['status']];
        return view('tasks/index', ['title' => 'All tasks', 'tasks' => $tasks, 'counts' => $counts]);
    }
    public function new() { return view('tasks/form', ['title' => 'New task', 'task' => null, 'action' => '/tasks/create']); }
    public function create()
    {
        if (! $this->tasks->insert($this->input())) return redirect()->back()->withInput()->with('errors', $this->tasks->errors());
        return redirect()->to('/tasks')->with('success', 'Task created.');
    }
    public function edit(int $id) { return view('tasks/form', ['title' => 'Edit task', 'task' => $this->findTask($id), 'action' => "/tasks/{$id}/update"]); }
    public function update(int $id)
    {
        $this->findTask($id);
        if (! $this->tasks->update($id, $this->input())) return redirect()->back()->withInput()->with('errors', $this->tasks->errors());
        return redirect()->to('/tasks')->with('success', 'Task updated.');
    }
    public function archive(int $id)
    {
        $this->findTask($id);
        return $this->tasks->archive($id) ? redirect()->to('/tasks')->with('success', 'Task archived.') : redirect()->to('/tasks')->with('error', 'The task could not be archived.');
    }
    private function input(): array { return ['title' => trim((string) $this->request->getPost('title')), 'task_date' => (string) $this->request->getPost('task_date'), 'status' => (string) $this->request->getPost('status')]; }
    private function findTask(int $id): array
    {
        $task = $this->tasks->findActive($id); if (! $task) throw PageNotFoundException::forPageNotFound('Task not found.'); return $task;
    }
}
