require('dotenv').config();
var spawn = require('child_process').spawn;
var helpers = require('../_helpers');

var container = process.env.WUXT_NUXT_CONTAINER || 'front.wuxt';

helpers.checkContainers([container], function(containerRunning) {

  if (!containerRunning) {
    console.log('ERROR: ' + container + ' container is not running. Try "docker-compose up -d"')
    return;
  }

  helpers.runYarn(container, function() {});

});
