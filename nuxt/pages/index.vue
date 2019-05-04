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
      const posts = await $wp.posts().embed()
      return {
        posts
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
