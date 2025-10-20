<?php namespace App\Controllers;
class Health extends BaseController {
  public function index() { echo 'OK '.date('Y-m-d H:i:s'); }
}
