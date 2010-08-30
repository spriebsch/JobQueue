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

use Countable;

/**
 * A job queue for testing purposes. No persistence is involved.
 *
 * @author Stefan Priebsch <stefan@priebsch.de>
 */
class QueueStub implements Countable
{
    /**
     * @var array
     */
    protected $queue = array();

    /**
     * Returns the number of items in the queue.
     *
     * @return int
     */
    public function count()
    {
        return count($this->queue);
    }
    
    /**
     * Empties the queue. 
     */
    public function purge()
    {
        $this->queue = array();
    } 
    
    /**
     * Adds an object to the end of the queue.
     *
     * @param Object $object
     * @return null
     */
    public function enqueue($object)
    {
        if (!is_object($object)) {
            throw new Exception('Cannot enqueue non-objects');
        }

        $this->queue[] = $object;
    }

    /**
     * Returns first object from the queue.
     *
     * @return Object
     */
    public function dequeue()
    {
        return array_shift($this->queue);
    }
}
?>
