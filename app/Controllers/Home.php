<?php
namespace App\Controllers;
use App\Models\TaskModel;

final class Home extends BaseController
{
    public function index()
    {
        $tasks = (new TaskModel())->today();
        return view('home', ['title' => 'Today', 'tasks' => $tasks, 'completed' => count(array_filter($tasks, static fn(array $t): bool => $t['status'] === 'completed')), 'today' => date('l, F j, Y')]);
    }
    public function about() { return view('about', ['title' => 'About']); }
}
