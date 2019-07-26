export default function(context) {
  console.log('ENVS', process.env);
  if (process.env.NODE_ENV === 'development') {
    const { app } = context
    app.$wp._options.endpoint = 'http://localhost:' + (process.env.WUXT_PORT_BACKEND ? process.env.WUXT_PORT_BACKEND : '3080') + '/wp-json/'
  }
}
