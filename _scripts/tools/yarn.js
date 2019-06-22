var spawn = require('child_process').spawn;
var helpers = require('../_helpers');

helpers.checkContainers(['front.wuxt'], function(containerRunning) {

  if (!containerRunning) {
    console.log('ERROR: front.wuxt container is not running. Try "docker-compose up -d"')
    return;
  }

  helpers.runYarn('front.wuxt', function() {});

});
