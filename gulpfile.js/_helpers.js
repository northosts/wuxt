module.exports = {

  /**
   * Check if wuxt containers are running
   */
  checkContainers: function(names, done) {

    var exec = require('child_process').exec;
    exec('docker ps --format {{.Names}}', function(error, stdout, stderr) {
      done(names.map(function(name) {
        return stdout.split("\n").indexOf(name) >= 0;
      }).reduce(function(running, next) {
        return running && next;
      }, true));
    });

  },

  /**
   * Check if wp cli is inastalled
   */
  checkWPCli: function(container, done) {

    var exec = require('child_process').exec;
    exec('docker exec ' + container + ' bash -c \'wp\'', function(error, stdout, stderr) {
    }).on('exit', function(code) {
      done(127 !== code);
    });

  },

  /**
   * Check if wp cli is inastalled
   */
  installWPCli: function(container, done) {

    var exec = require('child_process').exec;
    exec('docker exec ' + container + ' bash -c \'apt-get update && apt-get install -y less && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp && wp --allow-root cli\'', function(error, stdout, stderr) {
    }).on('exit', function(code) {
      done(0 === code);
    });

  },

  /**
   * Run a wp command in the given container
   */
  runWPCli: function(container, done) {

    var command = process.argv.slice(process.argv.indexOf('-c') + 1);

    if (!command.length) {
      console.log('ERROR: Provide a valid wp-cli command!');
      return done();
    }

    var exec = require('child_process').exec;
    exec('docker exec ' + container + ' bash -c \'wp --allow-root ' + command.join(' ') + '\'', function(error, stdout, stderr) {
      console.log(stdout);
    }).on('exit', function(code) {
      done();
    });

  },

  /**
   * Run a yarn command in the given container
   */
  runYarn: function(container, done) {

    var command = process.argv.slice(process.argv.indexOf('-c') + 1);

    if (!command.length) {
      console.log('ERROR: Provide a valid yarn command!');
      return done();
    }

    var exec = require('child_process').exec;
    exec('docker exec ' + container + ' bash -c \'yarn ' + command.join(' ') + '\'', function(error, stdout, stderr) {
      console.log(stdout);
    }).on('exit', function(code) {
      done();
    });

  }

};
