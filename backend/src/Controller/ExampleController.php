<?php

namespace App\Controller;

class ExampleController
{
    public static string $executadoCom = '';

    public function show($id, $query = [])
    {
        self::$executadoCom = "show: $id / query: " . json_encode($query);
    }

    public function store($data)
    {
        self::$executadoCom = "store: " . json_encode($data);
    }

    public function update($id, $data)
    {
        self::$executadoCom = "update: $id / data: " . json_encode($data);
    }

    public function destroy($id)
    {
        self::$executadoCom = "destroy: $id";
    }
}
