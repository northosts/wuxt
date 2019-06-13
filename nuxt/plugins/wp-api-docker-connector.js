export default function(context) {
  if (process.env.NODE_ENV === 'development') {
    const { app } = context
    app.$wp._options.endpoint = 'http://localhost:3080/wp-json/'
  }
  /*if (process.static) {
    const { app } = context
    app.$wp._options.endpoint = 'http://localhost:8080/wp-json/'
  }*/
}
