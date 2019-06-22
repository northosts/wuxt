var helpers = require('../_helpers');

helpers.checkContainers(['wp.wuxt'], function(containerRunning) {

  if (!containerRunning) {
    console.log('ERROR: wp.wuxt container is not running. Try "docker-compose up -d"')
    return;
  }

  helpers.checkWPCli('wp.wuxt', function(wpCliRunning) {
    if (!wpCliRunning) {

      console.log('WARNING: wp cli not installed, trying auto install ...');

      helpers.installWPCli('wp.wuxt', function(wpCliRunning) {

        console.log('SUCCESS: wp cli installed!');
        helpers.generateWPTax('wp.wuxt', function() {
          console.log('done!');
        });

      });
    } else {

      helpers.generateWPTax('wp.wuxt', function() {
        console.log('done!');
      });

    }
  });
});
