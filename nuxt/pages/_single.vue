<template>
  <Page v-if="single.type === 'page'" :page="single"/>
  <Post v-else :post="single"/>
</template>

<script>
import Page from '~/components/templates/Page'
import Post from '~/components/templates/Post'

export default {
  async asyncData(context) {
    const { route, app, error } = context

    try {
      const single = await app.$wp.slug().name(route.params.single+"/")
      return { single }
    } catch (e) {
      error(e)
    }
  },

  components: {
    Page,
    Post
  }
}
</script>
