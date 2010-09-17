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

/**
 * Unit tests for the Queue class. 
 *
 * @author Stefan Priebsch <stefan@priebsch.de>
 */
class QueueTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->filename = tempnam(sys_get_temp_dir(), 'jobqueue_');
        $this->queue = new Queue($this->filename);
    }

    protected function tearDown()
    {
        unlink($this->filename);
        unset($this->queue);
    }

    protected function readQueueFile()
    {
        if (!file_exists($this->filename)) {
            $this->fail('No queue file "' . $this->filename . '" found');
        }

        $contents = file_get_contents($this->filename);
        $data = unserialize($contents);

        if ($data === false) {
            $this->fail('Could not unserialize SplQueue from "' . $contents . '"');
        }

        return $data;
    }

    /**
     * @covers spriebsch\JobQueue\Queue::__construct
     */
    public function testConstruct()
    {
        $this->assertType('spriebsch\\JobQueue\\Queue', $this->queue);
    }

    /**
     * @covers spriebsch\JobQueue\Queue
     */
    public function testQueueInitiallyIsEmpty()
    {
        $this->assertEquals(0, count($this->queue));
    }

    /**
     * @covers spriebsch\JobQueue\Queue
     */
    public function testEnqueueAddsObjectToQueue()
    {
        $obj = new Object();

        $this->queue->enqueue($obj);

        $queue = $this->readQueueFile();

        $this->assertEquals(1, count($queue));
        $this->assertEquals($obj, $queue[0]);
    }

    /**
     * @covers spriebsch\JobQueue\Queue
     */
    public function testCountReturnsNumberOfObjectsInQueue()
    {
        $obj = new Object();

        $this->queue->enqueue($obj);
        
        $this->assertEquals(1, count($this->queue));
    }

    /**
     * @covers spriebsch\JobQueue\Queue
     */
    public function testEnqueueAddsObjectsToEndOfQueue()
    {
        $obj1 = new Object();
        $obj2 = new Object();

        $this->queue->enqueue($obj1);
        $this->queue->enqueue($obj2);

        $queue = $this->readQueueFile();

        $this->assertEquals(2, count($queue));
        $this->assertEquals($obj1, $queue[0]);
        $this->assertEquals($obj2, $queue[1]);
    }

    /**
     * @covers spriebsch\JobQueue\Queue
     */
    public function testDequeueRemovesObjectFromQueue()
    {
        $obj = new Object();

        $this->queue->enqueue($obj);
        $result = $this->queue->dequeue();

        $queue = $this->readQueueFile();

        $this->assertEquals(0, count($queue));
        $this->assertEquals($obj, $result);
    }

    /**
     * @covers spriebsch\JobQueue\Queue
     */
    public function testDequeueRemovesObjectsFromTopOfQueue()
    {
        $obj1 = new Object();
        $obj2 = new Object();

        $this->queue->enqueue($obj1);
        $this->queue->enqueue($obj2);

        $result = $this->queue->dequeue();

        $queue = $this->readQueueFile();

        $this->assertEquals(1, count($queue));
        $this->assertEquals($obj1, $result);
    }

    /**
     * @covers spriebsch\JobQueue\Queue
     */
    public function testDequeueReturnsNullOnEmptyQueue()
    {
        $this->assertNull($this->queue->dequeue());
    }

    /**
     * @covers spriebsch\JobQueue\Queue
     */
    public function testEnqueueAlsoWorksOnEmptiedQueue()
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
        
        $queue = $this->readQueueFile();

        $this->assertEquals(2, count($queue));
        $this->assertEquals($obj1, $queue[0]);
        $this->assertEquals($obj4, $queue[1]);
    }
}
?>
