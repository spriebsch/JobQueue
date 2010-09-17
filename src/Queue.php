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
 * A persistent job queue stored in a file.
 * Ensures queue consistency by exclusive file locking.
 *
 * @author Stefan Priebsch <stefan@priebsch.de>
 */
class Queue extends QueueStub implements Countable
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var Resource
     */
    protected $fileHandle = false;
    
    /**
     * Constructs the object.
     *
     * @param string $filename Filename to store the queue
     */
    public function __construct($filename)
    {   
        $this->filename = $filename;
    }
    
    /**
     * Locks the queue file for exclusive access.
     *
     * @return null
     * 
     * @throws Exception Could not open queue file
     * @throws Exception Could not lock queue file
     */
    protected function lock()
    {
        $this->fileHandle = fopen($this->filename, 'r+');
        
        if ($this->fileHandle === false) {
            throw new Exception('Could not open queue file');
        }

        if (flock($this->fileHandle, LOCK_EX) === false) {
            throw new Exception('Could not lock queue file');
        }
    }

    /**
     * Unlocks and closes the queue file.
     *
     * @return null
     *
     * @throws Exception Could not close and unlock queue file
     */
    protected function unlock()
    {
        if (fclose($this->fileHandle) === false) {
            throw new Exception('Could not close and unlock queue file');
        }
    }

    /**
     * Loads and unserializes the queue from disk.  
     *
     * @return null
     */
    protected function load()
    {
        $this->lock();

        // Non-existing or empty queue file means empty queue.
        if (!file_exists($this->filename)) {
            $this->queue = array();
            return;
        }

        $this->queue = unserialize(stream_get_contents($this->fileHandle));
        
        // Unreadable queue file means empty queue.
        if ($this->queue === false) {
            $this->queue = array();
        }
    }

    /**
     * Saves the queue to disk. 
     *
     * @return null
     *
     * @throws Exception Could not save the queue (fseek failed)
     * @throws Exception Could not save the queue (fwrite failed)
     */
    protected function save()
    {
        // Seek to beginning of file to overwrite existing data.
        $result = fseek($this->fileHandle, 0);

        if ($result != 0) {
            throw new Exception('Could not save the queue (fseek failed)');
        }

        $data = serialize($this->queue);

        $length = fwrite($this->fileHandle, $data);
        
        // Set file length to length of new data.
        ftruncate($this->fileHandle, strlen($data));
        
        // Make sure all data was written to the queue file.
        if ($length === false || $length != strlen($data)) {
            throw new Exception('Could not save the queue (fwrite failed)');
        }
        
        $this->unlock();
    }

    /**
     * Returns the number of items in the queue.
     *
     * @return int
     */
    public function count()
    {
        $this->load();
        $size = count($this->queue);
        $this->unlock();
        
        return $size;
    }
    
    /**
     * Empties the queue. 
     */
    public function purge()
    {
        $this->load();
        $this->queue = array();
        $this->save();
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

        $this->load();
        $this->queue[] = $object;
        $this->save();
    }

    /**
     * Returns first object from the queue.
     *
     * @return Object
     */
    public function dequeue()
    {
        $this->load();
        $object = array_shift($this->queue);
        $this->save();

        return $object;
    }
}
