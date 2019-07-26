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
    exec('docker exec ' + container + ' bash -c \'wp\'', function(error, stdout, stderr) {}).on('exit', function(code) {
      done(127 !== code);
    });

  },

  /**
   * Check if wp cli is inastalled
   */
  installWPCli: function(container, done) {

    var exec = require('child_process').exec;
    exec('docker exec ' + container + ' bash -c \'apt-get update && apt-get install -y less && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp && wp --allow-root cli\'', function(error, stdout, stderr) {}).on('exit', function(code) {
      done(0 === code);
    });

  },

  /**
   * Run a wp command in the given container
   */
  runWPCli: function(container, done) {

    var command = process.argv.slice(2);

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
   * Run docker-compose down
   */
  dockerDown: function(done) {

    var exec = require('child_process').exec;
    exec('docker-compose down', function(error, stdout, stderr) {
      console.log(stdout);
    }).on('exit', function(code) {
      done();
    });


  },

  /**
   * Run docker-compose down
   */
  dockerUp: function(done) {

    var exec = require('child_process').exec;

    exec('cat .env', function(error, stdout, stderr) {
      console.log(stdout);
    }).on('exit', function(code) {

      exec('docker-compose up --force-recreate -d', function(error, stdout, stderr) {
        console.log(stdout);
      }).on('exit', function(code) {
        done();
      });

    });

  },

  /**
   * Run a yarn command in the given container
   */
  runYarn: function(container, done) {

    var command = process.argv.slice(2);

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

  },

  /**
   * Get Arguments
   */
  getArgs: function(prompts, done) {

    if (Array.isArray(prompts)) {
      var result = {};
      var i = 2;
      prompts.forEach(function(prompt) {
        if (process.argv[i] && prompt.pattern.test(process.argv[i])) {
          result[prompt.name] = process.argv[i];
        } else {
          result = false;
        }
        i++;
      });

      if (result) {
        return done(null, result);
      }
    }

    var prompt = require('prompt');
    prompt.start();

    prompt.get(prompts, function(err, result) {
      if (err) {
        return done('Something wrong with your input!', null);
      }

      done(null, result);

    });

  },

  /**
   * Change the environment to be able to run multiple wuxt installations
   */
  changeEnv: function(done) {

    var fs = require('fs');

    this.getArgs([{
      name: 'project',
      description: 'Enter the project name',
      type: 'string',
      default: 'wuxt',
      message: 'Name must be a word'
    },{
      name: 'port-front',
      description: 'Enter the port for nuxt front-end',
      type: 'number',
      default: '3000',
      message: 'Name must be a number'
    },{
      name: 'port-wp',
      description: 'Enter the port for WordPress back-end',
      type: 'number',
      default: '3080',
      message: 'Name must be a number'
    },{
      name: 'port-dist',
      description: 'Enter the port for server with generated html files',
      type: 'number',
      default: '8080',
      message: 'Name must be a number'
    }], function(err, result) {

      if (err) {
        console.log(err);
        return done(err, null, null, null, null);
      }

      var env = "WUXT_PORT_FRONTEND=" + result['port-front'] + "\n" +
          "WUXT_PORT_BACKEND=" + result['port-wp'] + "\n" +
          "WUXT_PORT_DIST=" + result['port-dist'] + "\n" +
          "WUXT_MYSQL_CONTAINER=mysql." + result['project'] + "\n" +
          "WUXT_WP_CONTAINER=wp." + result['project'] + "\n" +
          "WUXT_NUXT_CONTAINER=front." + result['project'] + "\n";

      fs.writeFile('.env', env, function(err) {
        if (err) {
          console.log('ERROR: Could not create environment (' + err + ').')
          return done(err, null, null, null, null);
        }

        console.log('SUCCESS: New environment created.');

        done(null, result['project'], result['port-wp'], result['port-front'], result['port-dist']);

      });

    });
  },

  /**
   * Generate custom post type
   */
  generateWPCPT: function(container, done) {

    var slugify = require('slugify');
    var exec = require('child_process').exec;
    var fs = require('fs');
    var mkdirp = require('mkdirp');

    this.getArgs([{
      name: 'name',
      description: 'Enter your post types name, e.g. Movie',
      type: 'string',
      pattern: /^\w+$/,
      message: 'Name must be a word'
    }], function(err, result) {

      if (err) {
        console.log(err);
        return done();
      }

      var slug = slugify(result.name, { lower: true });

      exec('docker exec ' + container + ' bash -c \'wp --allow-root scaffold post-type ' + slug + ' --label=' + result.name + ' --textdomain=wuxt\'', function(error, stdout, stderr) {
        if (!error) {

          mkdirp('./wp-content/themes/wuxt/cpts', function (err) {
            if (err) {
              console.log('ERROR: Could not create post-type (' + err + ').')
              return;
            }

            stdout = stdout.replace('\'title\', \'editor\'', '\'title\', \'editor\', \'custom-fields\'')

            fs.writeFile('./wp-content/themes/wuxt/cpts/' + slug + '.php', stdout, function(err) {
              if (err) {
                console.log('ERROR: Could not create post-type (' + err + ').')
                return;
              }

              console.log('SUCCESS: Post type ' + result.name + ' created.');
            });
          });
        } else {
          console.log('ERROR: Could not create post-type (' + error + ').')
        }
      }).on('exit', function(code) {
        done();
      });

    });
  },

  /**
   * Generate custom post type
   */
  generateWPTax: function(container, done) {

    var slugify = require('slugify');
    var exec = require('child_process').exec;
    var fs = require('fs');
    var mkdirp = require('mkdirp');

    this.getArgs([{
      name: 'name',
      description: 'Enter your taxonomies name, e.g. Venue',
      type: 'string',
      pattern: /^\w+$/,
      message: 'Name must be a word'
    },{
      name: 'cpts',
      description: 'Enter a commaseparated list of post types for the taxonomy, e.g. event,presentation',
      type: 'string',
      pattern: /^\w+(,\w+)*$/,
      message: 'CPTs must be a list of post type slugs'
    }], function(err, result) {

      if (err) {
        console.log(err);
        return done();
      }

      var slug = slugify(result.name, { lower: true });

      exec('docker exec ' + container + ' bash -c \'wp --allow-root scaffold taxonomy ' + slug + ' --label=' + result.name + ' --post_types=' + result.cpts + ' --textdomain=wuxt\'', function(error, stdout, stderr) {
        if (!error) {

          mkdirp('./wp-content/themes/wuxt/taxonomies', function (err) {
            if (err) {
              console.log('ERROR: Could not create taxonomy (' + err + ').')
              return;
            }

            fs.writeFile('./wp-content/themes/wuxt/taxonomies/' + slug + '.php', stdout, function(err) {
              if (err) {
                console.log('ERROR: Could not create taxonomy (' + err + ').')
                return;
              }

              console.log('SUCCESS: Taxonomy ' + result.name + ' created.');
            });
          });
        } else {
          console.log('ERROR: Could not create taxonomy (' + error + ').')
        }
      }).on('exit', function(code) {
        done();
      });

    });
  }

};
