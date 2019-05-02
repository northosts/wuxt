<template>
  <section></section>
</template>

<script>
export default {
  async asyncData(context) {
    const {
      app: { $wp },
      error
    } = context

    try {
      const page = await $wp.frontPage().embed()
      const menu = await $wp.menu().location('main')
      const slug = await $wp
        .slug()
        .name('sample-page')
        .embed()

      return {
        page,
        menu,
        slug
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
