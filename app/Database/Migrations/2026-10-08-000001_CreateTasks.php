<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

final class CreateTasks extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'task_date' => ['type' => 'DATE'],
            'is_archived' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true); $this->forge->addKey('task_date'); $this->forge->addKey('status'); $this->forge->addKey('is_archived');
        $this->forge->createTable('tasks');
    }
    public function down() { $this->forge->dropTable('tasks'); }
}
