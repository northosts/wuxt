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

      return {
        page
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
