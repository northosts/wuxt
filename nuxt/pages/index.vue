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

      return {
        page,
        menu
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
