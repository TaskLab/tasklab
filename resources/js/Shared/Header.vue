<script>
  export default {
    name: 'Header'
  }
</script>

<template>
  <header id='app-header'>
    <h3 class='text-center font-weight-bold'>
      <inertia-link href='/' class='text-light'>tasklab</inertia-link>
    </h3>
  </header>
</template>

<style lang='scss' scoped>
  #app-header {
    width: 100%;
    height: 60px;
    background: #00203FFF;

    h3 {
      color: #fff;
      margin: 0;
      line-height: 60px;

      a {
        text-decoration: none;
      }
    }
  }
</style>