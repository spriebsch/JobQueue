<?php

  require 'PHPUnit/Framework.php';
  require '../src/Queue.php';

  class QueueTest extends PHPUnit_Framework_TestCase
  {
    public function testReadFromQueue()
    {
      $data = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10');

      $q = new Queue($data);

      $result = array();

      while (($item = $q->getNext()) !== null)
      {
        $result[] = $item;
      }

      sort($result, SORT_NUMERIC);

      $this->assertEquals($data, $result);
    }
  }

?>
