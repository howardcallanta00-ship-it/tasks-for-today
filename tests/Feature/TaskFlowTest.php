<?php
namespace Tests\Feature;
use App\Database\Seeds\AppSeeder;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

final class TaskFlowTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;
    protected $migrate = true;
    protected $seed = AppSeeder::class;

    public function testPublicPagesAreAvailable(): void
    {
        foreach (['/', '/tasks', '/profile', '/about', '/login', '/health'] as $path) $this->get($path)->assertStatus(200);
    }
    public function testWelcomeShowsOnlyTodayActiveTasks(): void
    {
        $result = $this->get('/');
        $result->assertSee('Finalize assessment presentation');
        $result->assertDontSee('Prepare sprint planning agenda');
        $result->assertDontSee('Archive resolved planning notes');
    }
    public function testTaskListOrdersAndExcludesArchivedTasks(): void
    {
        $result = $this->get('/tasks'); $result->assertDontSee('Archive resolved planning notes');
        $this->assertLessThan(strpos($result->getBody(), 'Share monthly progress summary'), strpos($result->getBody(), 'Prepare sprint planning agenda'));
    }
    public function testProfileDoesNotExposePassword(): void
    {
        $result = $this->get('/profile'); $result->assertSee('Howard Mac Callanta'); $result->assertDontSee('DemoPass123!');
    }
    public function testProtectedPageRedirectsToLogin(): void { $this->get('/tasks/new')->assertRedirectTo('/login'); }
    public function testInvalidAndValidLogin(): void
    {
        $this->post('/login', ['username' => 'demo', 'password' => 'wrong'])->assertRedirect();
        $this->post('/login', ['username' => 'demo', 'password' => 'DemoPass123!'])->assertRedirect();
    }
    public function testStateChangingRequestRequiresCsrfToken(): void
    {
        $this->withFilters()->withSession(['user_id' => 1, 'username' => 'demo'])
            ->post('/tasks/create', ['title' => 'Blocked request', 'task_date' => date('Y-m-d'), 'status' => 'pending'])
            ->assertStatus(403);
    }
    public function testAuthenticatedTaskLifecycleUsesArchive(): void
    {
        $session = ['user_id' => 1, 'username' => 'demo'];
        $this->withSession($session)->get('/tasks/new')->assertStatus(200);
        $this->withSession($session)->post('/tasks/create', ['title' => '', 'task_date' => '', 'status' => 'pending'])->assertRedirect();
        $this->withSession($session)->post('/tasks/create', ['title' => 'Verify feature tests', 'task_date' => date('Y-m-d'), 'status' => 'pending'])->assertRedirectTo('/tasks');
        $task = db_connect()->table('tasks')->where('title', 'Verify feature tests')->get()->getRowArray();
        $this->withSession($session)->post('/tasks/' . $task['id'] . '/update', ['title' => 'Verify all feature tests', 'task_date' => date('Y-m-d'), 'status' => 'completed'])->assertRedirectTo('/tasks');
        $this->withSession($session)->post('/tasks/' . $task['id'] . '/archive')->assertRedirectTo('/tasks');
        $archived = db_connect()->table('tasks')->where('id', $task['id'])->get()->getRowArray();
        $this->assertSame('1', (string) $archived['is_archived']);
        $this->get('/tasks')->assertDontSee('Verify all feature tests');
    }
    public function testMissingTaskReturns404(): void { $this->withSession(['user_id' => 1])->get('/tasks/999999/edit')->assertStatus(404); }
}
