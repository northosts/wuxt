<template>
  <section></section>
</template>

<script>
export default {
  async asyncData(context) {
    const {
      app: { $wp },
      //   $axios,
      error
    } = context

    try {
      // get blog title and slogan with $wp
      const settings = await $wp.root('/')
      // get posts with $wp
      const posts = await $wp.posts().embed()
      // get pages with $wp
      const pages = await $wp.pages().embed()
      return {
        settings,
        posts,
        pages
      }
    } catch (e) {
      /* eslint-disable no-console */
      console.log(e)
      /* eslint-enable no-console */
      error(e)
    }
  }
}
</script>
