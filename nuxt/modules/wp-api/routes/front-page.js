export default wp =>
  (wp.frontPage = wp.registerRoute('wuxt/v1', '/front-page', {
    params: ['embed']
  }))
