<?php

  require 'PHPUnit/Framework.php';
  require '../src/Queue.php';

  class ConcurrentQueueTest extends PHPUnit_Framework_TestCase
  {
    protected $queue;

    protected function assertQueuesMatch($aExpected, $aResult)
    {
      sort($aResult, SORT_NUMERIC);
      $aResult = array_map('trim', $aResult);

      $this->assertEquals($aExpected, $aResult);
    }

    protected function initResultFile()
    {
      $this->file = sys_get_temp_dir() . '/test.' . md5(uniqid());
    }

    protected function readResultFile()
    {
      $result = file($this->file);
      unlink($this->file);

      return $result;
    }

    protected function runConcurrently($aMethodName, $aNumberOfProcesses)
    {
      $pids = array();

      for ($i = 0; $i < $aNumberOfProcesses; $i++)
      {
        $pid = pcntl_fork();

        if ($pid == -1)
        {
          throw new RuntimeException('Could not fork process');
        }

        if ($pid != 0)
        {
          $pids[] = $pid;
        } else {
          $this->$aMethodName();
          exit;
        }
      }

      foreach ($pids as $pid)
      {
        pcntl_waitpid($pid, $status);
      }
    }

    protected function readFromQueue()
    {
      while (($item = $this->queue->getNext()) !== null)
      {
        file_put_contents($this->file, $item . PHP_EOL, FILE_APPEND);
      }
    }

    public function testSmallQueue()
    {
      $data = range(0, 10, 1);

      $this->queue = new Queue($data);
      $this->initResultFile();

      $this->runConcurrently('readFromQueue', 3);

      $this->assertQueuesMatch($data, $this->readResultFile());
    }

    public function testBigQueue()
    {
      $data = range(0, 100, 1);

      $this->queue = new Queue($data);
      $this->initResultFile();

      $this->runConcurrently('readFromQueue', 10);
 
      $this->assertQueuesMatch($data, $this->readResultFile());
    }

    public function testVeryBigQueue()
    {
      $data = range(0, 1000, 1);

      $this->queue = new Queue($data);
      $this->initResultFile();

      $this->runConcurrently('readFromQueue', 25);
 
      $this->assertQueuesMatch($data, $this->readResultFile());
    }
  }

?>
