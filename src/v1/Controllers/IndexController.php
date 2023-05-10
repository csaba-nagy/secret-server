<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Controllers;

use SecretServer\Api\v1\Abstracts\BaseController;

class IndexController extends BaseController
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index(): string
  {
    return 'Secret Server Main Page';
  }
}
