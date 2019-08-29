const { resolve } = require('path')

const defaults = {}

module.exports = function wp(moduleOptions) {
  const options = Object.assign({}, defaults, this.options.wp, moduleOptions)

  this.addPlugin({
    src: resolve(__dirname, './plugin.js'),
    fileName: 'plugin.js',
    options
  })
}
