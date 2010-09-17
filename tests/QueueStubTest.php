<?php
/**
 * Copyright (c) 2009-2010 Stefan Priebsch <stefan@priebsch.de>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 *   * Neither the name of Stefan Priebsch nor the names of contributors
 *     may be used to endorse or promote products derived from this software
 *     without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER ORCONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    JobQueue
 * @author     Stefan Priebsch <stefan@priebsch.de>
 * @copyright  Stefan Priebsch <stefan@priebsch.de>. All rights reserved.
 * @license    BSD License
 */

namespace spriebsch\JobQueue;

use PHPUnit_Framework_TestCase;

/**
 * Unit tests for the QueueStub class.
 *
 * @author Stefan Priebsch <stefan@priebsch.de>
 */
class QueueStubTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->queue = new QueueStub();
    }

    protected function tearDown()
    {
        unset($this->queue);
    }

    /**
     * @covers spriebsch\JobQueue\QueueStub
     */
    public function testQueueInitiallyIsEmpty()
    {
        $this->assertEquals(0, count($this->queue));
    }

    /**
     * @covers spriebsch\JobQueue\QueueStub
     */
    public function testEnqueueAddsObjectToQueue()
    {
        $obj = new Object();

        $this->queue->enqueue($obj);

        $this->assertEquals(1, count($this->queue));
        $this->assertEquals($obj, $this->queue->dequeue());
    }

    /**
     * @covers spriebsch\JobQueue\QueueStub
     */
    public function testCountReturnsNumberOfObjectsInQueue()
    {
        $obj = new Object();

        $this->queue->enqueue($obj);
        
        $this->assertEquals(1, count($this->queue));
    }

    /**
     * @covers spriebsch\JobQueue\QueueStub
     */
    public function testEnqueueAddsObjectsToEndOfQueue()
    {
        $obj1 = new Object();
        $obj2 = new Object();

        $this->queue->enqueue($obj1);
        $this->queue->enqueue($obj2);

        $this->assertEquals(2, count($this->queue));
        $this->assertEquals($obj2, $this->queue->dequeue());
        $this->assertEquals($obj1, $this->queue->dequeue());
    }

    /**
     * @covers spriebsch\JobQueue\QueueStub
     */
    public function testDequeueRemovesObjectFromQueue()
    {
        $obj = new Object();

        $this->queue->enqueue($obj);
        $result = $this->queue->dequeue();

        $this->assertEquals(0, count($this->queue));
        $this->assertSame($obj, $result);
    }

    /**
     * @covers spriebsch\JobQueue\QueueStub
     */
    public function testDequeueRemovesObjectsFromTopOfQueue()
    {
        $obj1 = new Object();
        $obj2 = new Object();

        $this->queue->enqueue($obj1);
        $this->queue->enqueue($obj2);

        $result = $this->queue->dequeue();

        $this->assertEquals(1, count($this->queue));
        $this->assertSame($obj1, $result);
    }

    /**
     * @covers spriebsch\JobQueue\QueueStub
     */
    public function testDequeueReturnsNullOnEmptyQueue()
    {
        $this->assertNull($this->queue->dequeue());
    }

    /**
     * @covers spriebsch\JobQueue\QueueStub
     */
    public function testEnqueueWorksOnEmptiedQueue()
    {
        $obj1 = new Object();
        $obj2 = new Object();
        $obj3 = new Object();
        $obj4 = new Object();
        
        $this->queue->enqueue($obj1);
        $this->queue->enqueue($obj2);
        $this->queue->enqueue($obj3);

        $this->queue->dequeue();
        $this->queue->dequeue();

        $this->queue->enqueue($obj4);

        $this->assertEquals(2, count($this->queue));
        $this->assertEquals($obj4, $this->queue->dequeue());
        $this->assertEquals($obj1, $this->queue->dequeue());
    }
}
