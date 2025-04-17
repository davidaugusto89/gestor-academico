<?php

namespace Tests\Core;

class FakeController
{
    public function hello()
    {
        return ['message' => 'Hello World'];
    }

    public function created()
    {
        return ['status' => 'created'];
    }

    public function updated()
    {
        return ['status' => 'updated'];
    }

    public function deleted()
    {
        return ['status' => 'deleted'];
    }
}
