<?php

namespace Tests\Unit;

use App\Services\ModelCreateService;
use PHPUnit\Framework\TestCase;

class ModelCreateServiceTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_that_false_is_false(): void
    {
        $service = new ModelCreateService('test', [
            'id' => [
                'is_key' => true,
                'value' => 1
            ],
            'data' => [
                'value' => [],
            ]
        ]);
        $object = $service->create();
        $this->assertSame($object->getTable(), 'test');
        $this->assertSame($object->usesTimestamps(), true);
        $this->assertEquals('id',$object->getKeyName());
    }
}
