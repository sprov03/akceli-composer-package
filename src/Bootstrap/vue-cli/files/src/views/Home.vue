<template>
  <div class="home">
    <img alt="Vue logo" src="../assets/logo.png">
    <h2>Login</h2>
    <login-form></login-form>
      <h2>Register</h2>
      <registration-form></registration-form>
    <HelloWorld msg="Welcome to Your Vue.js App"/>
  </div>
</template>

<script>
  // @ is an alias to /src
  import HelloWorld from '@/components/HelloWorld.vue'
  import LoginForm from "../components/loginForm";
  import RegistrationForm from "../components/registrationForm";

  export default {
    name: 'Home',
    components: {
      RegistrationForm,
      LoginForm,
      HelloWorld
    }
  }
</script>
