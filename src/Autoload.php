<?php // this is an autogenerated file - do not edit (created 2009-12-15 23:11:42)
spl_autoload_register(
   function($class) {
      static $classes = array(
         'spriebsch\\jobqueue\\exception' => 'Exception.php',
         'spriebsch\\jobqueue\\queue' => 'Queue.php'
      );
      $cn = strtolower($class);
      if (isset($classes[$cn])) {
         require __DIR__ . '/' .  $classes[$cn];
      }
   }
);
?>
