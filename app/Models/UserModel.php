<?php
namespace App\Models;
use CodeIgniter\Model;
final class UserModel extends Model
{
    protected $table = 'users'; protected $primaryKey = 'id'; protected $returnType = 'array'; protected $allowedFields = ['username', 'full_name', 'email', 'password']; protected $useTimestamps = true;
    protected $validationRules = ['username' => 'required|max_length[50]|is_unique[users.username,id,{id}]', 'full_name' => 'required|max_length[100]', 'email' => 'required|valid_email|max_length[100]', 'password' => 'required|max_length[255]'];
    public function findByUsername(string $username): ?array { return $this->where('username', $username)->first(); }
}
