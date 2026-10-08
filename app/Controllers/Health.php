<?php
namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
final class Health extends BaseController { use ResponseTrait; public function index() { return $this->respond(['status' => 'ok', 'time' => date(DATE_ATOM)]); } }
