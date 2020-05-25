<template>
    <q-btn
            v-bind="$attrs"
            :disabled="disabled || pending"
            @click="clicked">
        <slot></slot>
    </q-btn>
</template>

<script>
  export default {
    name: "AppButton",
    data() {
      return {
        pending: false
      }
    },
    props: {
      async: {
        type: Function,
      },
      disabled: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      clicked($event) {
        this.$emit('click', $event);

        if (!this.async) {
          return;
        }

        this.pending = true;

        this.async().then(() => {
          this.pending = false;
        }, (error) => {
          this.pending = false;
        });
      }
    }
  }
</script>
