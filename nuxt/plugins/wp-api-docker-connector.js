export default function(context) {
  const { app } = context
  app.$wp._options.endpoint = 'http://localhost:3080/wp-json/'
}
