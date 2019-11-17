<?php

use \hannespries\services\Container;

class DummyService {
    private $val = null;
    
    public function __construct($val) {
        $this->val = $val;
    }

    public function test() {
        var_dump($this->val);
        return $this->val;
    }
}

class OuterDummyService {
    private $val = null;
    
    public function __construct(DummyService $val) {
        $this->val = $val;
    }

    public function test() {
        return $this->val->test();
    }
}

class Test extends \PHPUnit\Framework\TestCase{
    public function test_simplePrimitive() {
        $sd = [
            'test' => [
                'class' => 'DummyService',
                'arguments' => [
                    [
                        'id' => 'blubb23',
                        'type' => 'primitive',
                    ]
                ]
            ]
        ];

        $cont = Container::instance();
        $cont->addServiceDescriptor($sd);
        $test = $cont->get('test');

        $this->assertEquals('blubb23', $test->test());
    }

    public function test_simpleServices() {
        $sd = [
            'test' => [
                'class' => 'DummyService',
                'arguments' => [
                    [
                        'id' => 'blubb23',
                        'type' => 'primitive',
                    ]
                ]
            ],
            'outer' => [
                'class' => 'OuterDummyService',
                'arguments' => [
                    [
                        'id' => 'test',
                        'type' => 'service',
                    ]
                ]
            ]
        ];

        $cont = Container::instance();
        $cont->addServiceDescriptor($sd);
        $test = $cont->get('outer');

        $this->assertEquals('blubb23', $test->test());
    }
}    