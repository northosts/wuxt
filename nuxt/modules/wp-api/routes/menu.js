export default wp =>
  (wp.menu = wp.registerRoute('wuxt/v1', '/menu', {
    params: ['location']
  }))
