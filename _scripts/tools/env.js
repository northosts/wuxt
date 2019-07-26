require('dotenv').config();
var spawn = require('child_process').spawn;
var helpers = require('../_helpers');

helpers.changeEnv(function(err, project, portBackend, portFrontend, portDist) {

  console.log('Shutting down old containers ...');
  helpers.dockerDown(function() {

    console.log('');
    console.log('Run docker-compose up -d to start your new environment!');
    console.log('');
    console.log('  [project] ' + project);
    console.log('  [wp]      http://localhost:' + portBackend);
    console.log('  [front]   http://localhost:' + portFrontend);
    console.log('  [dist]    http://localhost:' + portDist + ' (lookup static generation in the docs)');

  });

});
