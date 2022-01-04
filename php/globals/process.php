<?php
$process = new ObjectClass();

// the type of interface between web server and PHP
$process->set('sapi_name', php_sapi_name());

$process->set('exit', new Func(function($code = 0) {
  $code = intval($code);
  exit($code);
}));

$process->set('binding', new Func(function($name) {
  $module = Module::get($name);
  if ($module === null) {
    throw new Ex(Err::create("Binding `$name` not found."));
  }
  return $module;
}));

$process->set('cwd', new Func(function() {
  return getcwd();
}));

$env = new ObjectClass();
$env->setProps(getenv());
$process->set('env', $env);
unset($env);

//command line arguments
$process->argv = isset(GlobalObject::$OLD_GLOBALS['argv']) ? GlobalObject::$OLD_GLOBALS['argv'] : array();
//first argument is path to script
array_unshift($process->argv, 'php');
$process->set('argv', Arr::fromArray($process->argv));

$process->set('stdout', new ObjectClass('write', new Func(function($data) {
  echo $data;
})));

$process->set('stderr', new ObjectClass('write', new Func(function($data) {
  fwrite(STDERR, $data);
})));