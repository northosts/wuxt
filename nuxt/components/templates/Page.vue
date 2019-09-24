<template>
  <main>
    <h1 v-html="page.title.rendered" class="wp__title"></h1>
    <div v-html="page.content.rendered" class="wp__content"></div>
  </main>
</template>

<script>
export default {
  props: {
    page: {
      type: Object,
      required: true
    }
  },
  created() {
    this.page.content.rendered = this.page.content.rendered.replace(
      ':' +
        (process.env.WUXT_PORT_BACKEND
          ? process.env.WUXT_PORT_BACKEND
          : '3080'),
      ''
    )
  }
}
</script>
