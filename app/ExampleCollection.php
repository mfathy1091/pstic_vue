<?php

namespace App;

class ExampleCollection
{
    public function example()
    {
        return collect([
            'value1' => 'first',
            'value2' => 'second',
        ]);
    }
}