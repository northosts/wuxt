var gulp = require('gulp');
var spawn = require('child_process').spawn;
var helpers = require('../_helpers');

gulp.task( 'wuxt-yarn', function(done) {

  helpers.checkContainers(['front.wuxt'], function(containerRunning) {

    if (!containerRunning) {
      console.log('ERROR: front.wuxt container is not running. Try "docker-compose up -d"')
      return done();
    }

    helpers.runYarn('front.wuxt', done);

  });

});
