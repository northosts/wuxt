<template>
  <div>
    <header class="header">
      <Logo/>

      <nav class="header__nav">
        <nuxt-link to="/hello-world">Hello World</nuxt-link>
        <nuxt-link to="/sample-page">Sample Page</nuxt-link>
      </nav>
    </header>
    <nuxt class="container"/>
  </div>
</template>

<script>
import Logo from '~/components/Logo'

export default {
  components: {
    Logo
  }
}
</script>


<style lang="scss">
@import url('https://fonts.googleapis.com/css?family=Nunito:700,600,400|Open+Sans:400,700');

$primary: #37495c;
$secondary: #48b884;

html,
body {
  margin: 0;
  padding: 0;
}

html {
  font-family: 'Open Sans', Arial, sans-serif;
  font-size: 16px;
  word-spacing: 1px;
  -ms-text-size-adjust: 100%;
  -webkit-text-size-adjust: 100%;
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  box-sizing: border-box;
}

*,
*:before,
*:after {
  box-sizing: border-box;
  margin: 0;
}

a {
  color: #48b884;
  text-decoration: none;

  &:hover {
    color: darken($color: #48b884, $amount: 10);
  }
}

h1,
h2,
h3,
h4,
h5,
h6 {
  font-family: 'Nunito';
  font-weight: 700;
}

.container {
  width: 100%;
  max-width: 1000px;
  margin: 0 auto;
  padding-right: 15px;
  padding-left: 15px;
}

.wp {
  &__title {
    margin-bottom: 30px;
  }

  &__content {
    * {
      &:nth-child(1n + 2) {
        margin-top: 1rem;
      }
    }

    p {
      line-height: 1.8rem;
    }
  }
}

.header {
  position: relative;

  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;

  margin-top: 100px;
  margin-bottom: 50px;
  padding-bottom: 50px;

  border-bottom: 2px solid #ececec;

  &__nav {
    margin-top: 10px;

    a {
      margin: 5px;
    }
  }
}
</style>
