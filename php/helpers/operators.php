<?php

function x_void() {
  return null;
}

/**
 * @param Func $fn
 * @return Object
 * @throws Exception
 */
function x_new($fn) {
  if (!($fn instanceof Func)) {
    throw new Ex(Error::create(x_typeof($fn) . " is not a function"));
  }
  $args = array_slice(func_get_args(), 1);
  return call_user_func_array(array($fn, 'construct'), $args);
}

/**
 * @param Object $obj
 * @param Func $fn
 * @return bool
 */
function x_instanceof($obj, $fn) {
  if (!($obj instanceof Object)) {
    return false;
  }
  if (!($fn instanceof Func)) {
    throw new Ex(Error::create('Expecting a function in instanceof check'));
  }
  $proto = $obj->proto;
  $prototype = get($fn, 'prototype');
  while ($proto !== Null::$null) {
    if ($proto === $prototype) {
      return true;
    }
    $proto = $proto->proto;
  }
  return false;
}

function x_plus($a, $b) {
  //todo: to_primitive() [object -> string]
  if (gettype($a) === 'string' || gettype($a) === 'string') {
    return to_string($a) . to_string($b);
  }
  //todo: to_number()
  return $a + $b;
}

function x_negate($val) {
  return (float)(0 - $val);
}

function x_and($a, $b) {
  return $a ? $b : $a;
}

function x_or($a, $b) {
  return $a ? $a : $b;
}

/**
 * @param $obj
 * @param null $key
 * @return bool
 * @throws Exception
 */
function x_delete($obj, $key = null) {
  //don't allow deleting of variables, only properties
  if (func_num_args() === 1) {
    return false;
  }
  if ($obj === null || $obj === Null::$null) {
    throw new Ex(Error::create("Cannot convert undefined or null to object"));
  }
  $obj = objectify($obj);
  $obj->remove($key);
  return true;
}

/**
 * @param string $key
 * @param Object $obj
 * @return bool
 * @throws Exception
 */
function x_in($key, $obj) {
  if (!($obj instanceof Object)) {
    throw new Ex(Error::create("Cannot use 'in' operator to search for '" . $key . "' in " . to_string($obj)));
  }
  return $obj->hasProperty($key);
}

function x_typeof($value) {
  if ($value === null) {
    return 'undefined';
  }
  //js weirdness
  if ($value === Null::$null) {
    return 'object';
  }
  $type = gettype($value);
  if ($type === 'integer' || $type === 'double') {
    return 'number';
  }
  if ($type === 'string' || $type === 'boolean') {
    return $type;
  }
  if ($value instanceof Func) {
    return 'function';
  }
  if ($value instanceof Object) {
    return 'object';
  }
  return 'unknown';
}

function x_seq() {
  $args = func_get_args();
  return array_pop($args);
}
