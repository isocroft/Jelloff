<?php

namespace Jollof\Tests;

/**
 * Jollof (c) Copyright 2016
 *
 *
 * @package    Jollof\Tests\
 * @version    0.0.4-beta.3
 * @author     Ifeora Okechukwu.
 * @license    MIT License
 * @copyright   Mobicent, Ltd.
 * @link htps://github.com/isocroft/Jollof
 */


use \Mockery as m;
use \PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase {

    /**
     * @var array -
     */

    protected $mockObjects;

    /**
     * @var array -
     */

    protected $mockClassNames;

    /**
     * @var array -
     */

    protected $config;

    /**
     * Constructor
     *
     * Gets all config and mocks ready
     *
     * @param array $mockClassNames
     * @param array $testingConfig
     *
     */

    public function __construct(array $mockClassNames = array(), array $testingConfig = array()){

         $this->config = $testingConfig; #unitTesting = true

         $this->mockClassNames = $mockClassNames;

         $this->mockObjects = array();

         parent::__construct();

    }

    /**
     * Setup test dependencies.
     *
     * @param void
     * @return void
     */
    public function setUp(){

        foreach ($this->mockClasses as $index => $className) {

             $this->mockObjects[$className] = m::mock($className);

        }

    }

    /**
     * Close mockery.
     *
     * @param void
     * @return void
     */
    public function tearDown(){

        m::close();

        $this->mockObjects = array();
    }

}


?>