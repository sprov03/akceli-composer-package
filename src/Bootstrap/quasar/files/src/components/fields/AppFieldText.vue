<template>
    <q-input
            v-bind="$attrs"
            bottom-slots
            :error="!!validationErrors.length"
            :value="value"
            @input="$emit('input', $event)"
    >
        <template v-slot:error>
            <template v-for="error in validationErrors">{{error}}</template>
        </template>
    </q-input>
</template>

<script>
  export default {
    name: "AppFieldText",
    inheritAttrs: false,
    props: {
      value: {},
      validationErrors: {
        default: function () {
          return [];
        }
      },
    },
  }
</script>

<style scoped>

</style>