export default wp =>
  (wp.slug = wp.registerRoute('wuxt/v1', '/slug/(?P<name>)', {
    params: ['embed']
  }))
