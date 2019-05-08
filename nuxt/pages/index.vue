<template>
  <main class="wp__content">
    <p>
      <span class="bold">Wuxt</span> combines
      <span class="bold italic">WordPress</span>, the worlds biggest CMS with
      <span class="bold italic">nuxt.js</span>, the most awesome front-end application framework yet.
    </p>
    <p>The goal is to provide a ready to use development environment, which makes the full power of WordPress easily available to your front-and app. Included in Wuxt are:</p>

    <ul>
      <li>
        Fully dockerized
        <span class="bold italic">WordPress</span> and
        <span class="bold italic">nuxt.js</span> container configuration,
        <code>docker-compose up -d</code> sets up everything needed in one command and you can start working
      </li>
      <li>Extended Rest API to give the front-end easy access to meta-fields, featured media menus or the front-page configuration.</li>
      <li>
        The newest nuxt.js version, extended with a WordPress
        <code>$wp</code> object, to connect to the extended
        <span class="bold italic">WordPress</span> Rest API.
      </li>
    </ul>

    <p>
      All together the
      <span class="bold italic">Wuxt</span> features get you started with your front-end with just one command, you just need to work with the intuitive
      <span
        class="bold italic"
      >WordPress</span> admin interface and can skip all back-end coding. But if you know your way around WordPress you are able to easily extend the back-end as well.
    </p>
  </main>
</template>

<script>
</script>

<style lang="scss" scoped>
p {
  &:nth-child(1n + 2) {
    margin-top: 1rem;
  }
}

ul {
  margin-top: 1rem;

  li {
    &:nth-child(1n + 2) {
      margin-top: 1rem;
    }
  }
}

.bold {
  font-weight: 700;
}

.italic {
  font-style: italic;
}

code {
  padding: 3px 10px;

  background-color: #37495c;
  border-radius: 4px;

  color: #fff;
}
</style>
