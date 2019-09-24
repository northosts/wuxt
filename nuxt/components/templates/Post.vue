<template>
  <main>
    <h1 v-html="post.title.rendered" class="wp__title"></h1>
    <div v-html="post.content.rendered" class="wp__content"></div>
  </main>
</template>

<script>
export default {
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  created() {
    this.post.content.rendered = this.post.content.rendered.replace(
      ':' +
        (process.env.WUXT_PORT_BACKEND
          ? process.env.WUXT_PORT_BACKEND
          : '3080'),
      ''
    )
  }
}
</script>
