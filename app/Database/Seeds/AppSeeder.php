<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

final class AppSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s'); $today = date('Y-m-d');
        $tasks = $this->db->table('tasks');
        if ($tasks->countAllResults() === 0) {
            $tasks->insertBatch([
            ['title' => 'Review priority support tickets', 'status' => 'completed', 'task_date' => $today, 'is_archived' => 0, 'created_at' => $now],
            ['title' => 'Finalize assessment presentation', 'status' => 'in_progress', 'task_date' => $today, 'is_archived' => 0, 'created_at' => $now],
            ['title' => 'Confirm tomorrow’s delivery plan', 'status' => 'pending', 'task_date' => $today, 'is_archived' => 0, 'created_at' => $now],
            ['title' => 'Reconcile weekly project notes', 'status' => 'completed', 'task_date' => date('Y-m-d', strtotime('-2 days')), 'is_archived' => 0, 'created_at' => $now],
            ['title' => 'Prepare sprint planning agenda', 'status' => 'pending', 'task_date' => date('Y-m-d', strtotime('+1 day')), 'is_archived' => 0, 'created_at' => $now],
            ['title' => 'Update deployment checklist', 'status' => 'in_progress', 'task_date' => date('Y-m-d', strtotime('+2 days')), 'is_archived' => 0, 'created_at' => $now],
            ['title' => 'Share monthly progress summary', 'status' => 'pending', 'task_date' => date('Y-m-d', strtotime('+5 days')), 'is_archived' => 0, 'created_at' => $now],
            ['title' => 'Archive resolved planning notes', 'status' => 'completed', 'task_date' => date('Y-m-d', strtotime('-7 days')), 'is_archived' => 1, 'created_at' => $now],
            ]);
        }

        $users = $this->db->table('users');
        $demo = $users->where('username', 'demo')->get()->getRowArray();
        $userData = ['full_name' => 'Howard Mac Callanta', 'email' => 'howard.callanta@example.com', 'password' => password_hash('DemoPass123!', PASSWORD_DEFAULT)];
        if ($demo) {
            $users->where('id', $demo['id'])->update($userData);
        } else {
            $users->insert(['username' => 'demo', 'created_at' => $now] + $userData);
        }
    }
}
