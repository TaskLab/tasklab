<script>
  export default {
    name: 'Header'
  }
</script>

<template>
  <header id='app-header'>
    <h1 class='text-center'>tasklab</h1>
  </header>
</template>

<style scoped>
  #app-header {
    width: 100%;
    height: 60px;
    background: rgb(48, 63, 109);
  }

  #app-header h1 {
    color: #fff;
    margin: 0;
    line-height: 60px;
  }
</style>