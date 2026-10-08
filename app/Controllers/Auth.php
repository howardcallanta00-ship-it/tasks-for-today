<?php
namespace App\Controllers;
use App\Models\UserModel;

final class Auth extends BaseController
{
    public function login()
    {
        if (session('user_id')) return redirect()->to('/tasks');
        if ($this->request->getMethod() === 'POST') {
            if (! $this->validate(['username' => 'required|max_length[50]', 'password' => 'required'])) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            $user = (new UserModel())->findByUsername((string) $this->request->getPost('username'));
            if (! $user || ! password_verify((string) $this->request->getPost('password'), $user['password'])) return redirect()->back()->withInput()->with('error', 'The username or password is incorrect.');
            session()->regenerate(true);
            session()->set(['user_id' => $user['id'], 'username' => $user['username']]);
            $target = (string) session()->get('redirect_after_login'); session()->remove('redirect_after_login');
            return redirect()->to($this->safeTarget($target))->with('success', 'Welcome back, ' . $user['full_name'] . '.');
        }
        return view('auth/login', ['title' => 'Sign in']);
    }
    public function logout()
    {
        session()->remove(['user_id', 'username', 'redirect_after_login']); session()->regenerate(true);
        return redirect()->to('/')->with('success', 'You have been signed out.');
    }
    private function safeTarget(string $target): string
    {
        if ($target === '' || parse_url($target, PHP_URL_HOST) !== parse_url(base_url(), PHP_URL_HOST)) return '/tasks';
        return $target;
    }
}
