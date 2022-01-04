#!/usr/bin/env node
var processStartInMs = Date.now();
var tests = [
  'core',
  'number',
  'boolean',
  'string',
  'date',
  'regex',
  'array',
  'buffer',
  'json',
  'module-path'
]; //, 'module-fs'];
require('./test/helpers');
for (var i in tests) {
  var test = tests[i];
  var path = './test/' + test;
  var startTimeInMs = Date.now();
  var numOfLoops = 1;
  for (var j = 0; j < numOfLoops; j++) {
    var resolved = require.resolve(path);
    delete require.cache[resolved];
    require(path);
  }
  var endTimeInMs = Date.now();
  var duration = endTimeInMs - startTimeInMs;
  console.log(
    test +
      ', total: ' +
      duration +
      ' ms, each run: ' +
      duration / numOfLoops +
      ' ms'
  );
}
var processEndInMs = Date.now();
var processDuration = processEndInMs - processStartInMs;
console.log(
  'process total: ' +
    processDuration +
    ' ms, each run: ' +
    processDuration / numOfLoops +
    ' ms'
);
