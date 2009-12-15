<?php

 /**
  * Queue of runtest file names
  *
  * A queue (of runtest file names to run) that is stored in a temporary file.
  * Can be accessed by multiple processes, guarantees (?) by file locking
  * that each queue element is only read by exactly one process.
  *
  * @author Stefan Priebsch <stefan.priebsch@e-novative.de>
  */
  class Queue
  {
    protected $queueFile;

   /**
    * Constructor 
    *
    * Since multiple queue objects can be used, we only create the 
    * queue file when an array of values is passed to the constructor.
    *
    * @param array $aElements array of elements to queue 
    */
    public function __construct($aElements = null)
    {   
      $this->queueFile = sys_get_temp_dir() . '/' . 'runtests.queue';

      if (!is_null($aElements))
      {
        $this->storeQueue($aElements);
      }
    }

   /**
    * Load the queue
    *
    * Since multiple processes access the queue, we must read it from 
    * the filesystem every time we want to access it.
    * Trailing whitespace of queue elements (i.e. filenames) will be stripped!
    * For test files, this should not be an issue (Windows silently strips
    * trailing whitespace in filenames anyway).
    *
    * @returns array the queue elements 
    */
    protected function loadQueue()
    {
      $elements = file($this->queueFile);
      $elements = array_map('rtrim', $elements);

      return $elements;
    }
 
   /**
    * Store the queue
    *
    * Stores the queue to a file, one item per line. 
    *
    * @param array $aElements 
    * @returns void
    */
    protected function storeQueue($aElements)
    {
      file_put_contents($this->queueFile, implode(PHP_EOL, $aElements));
    }

   /**
    * Get next item from queue
    *
    * Returns next item in queue. This is an atomic
    * operation (if all filesystems support locking properly)
    * to make sure that no two processes can read the
    * same element from the queue.
    * Returns NULL if queue is empty.
    *
    * @returns string next queue element
    */
    public function getNext()
    {
      $fp = fopen($this->queueFile, 'r');
      flock($fp, LOCK_EX);

      $elements = $this->loadQueue();
      $element = array_pop($elements);
      $this->storeQueue($elements);

      flock($fp, LOCK_UN);
      fclose($fp);

      return $element;
    }
  }

?>
