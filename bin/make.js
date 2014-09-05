#!/usr/bin/env node
/* global cd, config, cp, echo, exec, target */

/**
 * Build system.
 * @module bin/make
 */
'use strict';

// Module dependencies.
require('shelljs/make');

/**
 * Provides tasks for [ShellJS](http://shelljs.org) make tool.
 * @class cli.Makefile
 * @static
 */
cd(__dirname+'/..');

/**
 * The application settings.
 * @property config
 * @type Object
 */
config.fatal=true;

/**
 * Runs the default tasks.
 * @method all
 */
target.all=function() {
  echo('Please specify a target. Available targets:');
  for(var task in target) {
    if(task!='all') echo(' ', task);
  }
};

/**
 * Builds the documentation.
 * @method doc
 */
target.doc=function() {
  echo('Build the documentation...');
  exec('yuidoc-bs --extension ".php" --theme spacelab');
  cp('-f', [ 'doc/apple-touch-icon.png', 'doc/favicon.ico' ], 'doc/api/assets');
};

/**
 * Performs static analysis of source code.
 * @method lint
 */
target.lint=function() {
  config.fatal=false;

  echo('Static analysis of JavaScript sources...');
  exec('jshint --verbose bin');

  echo('Static analysis of documentation comments...');
  exec('yuidoc-bs --extension ".php" --lint');

  config.fatal=true;
};
