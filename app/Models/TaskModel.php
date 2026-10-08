<?php
namespace App\Models;
use CodeIgniter\Model;

final class TaskModel extends Model
{
    protected $table = 'tasks'; protected $primaryKey = 'id'; protected $returnType = 'array'; protected $allowedFields = ['title', 'status', 'task_date', 'is_archived']; protected $useTimestamps = true;
    protected $validationRules = ['title' => 'required|max_length[150]', 'status' => 'required|in_list[pending,in_progress,completed]', 'task_date' => 'required|valid_date[Y-m-d]'];
    public function today(): array { return $this->where(['task_date' => date('Y-m-d'), 'is_archived' => 0])->orderBy('id', 'ASC')->findAll(); }
    public function active(): array { return $this->where('is_archived', 0)->orderBy('task_date', 'ASC')->orderBy('id', 'ASC')->findAll(); }
    public function findActive(int $id): ?array { return $this->where(['id' => $id, 'is_archived' => 0])->first(); }
    public function archive(int $id): bool { return (bool) $this->update($id, ['is_archived' => 1]); }
}
