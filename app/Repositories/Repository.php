<?php

namespace App\Repositories;

use Exception;

abstract class Repository
{
    protected $model;

    public function getById($id)
    {
        if (isset($this->model)) {
            return $this->model::find($id);
        }
        throw new Exception('Model not set on repository');
    }
}
