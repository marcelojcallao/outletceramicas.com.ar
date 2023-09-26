<template>
  <div>
    {{ Neto | currency }}
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
    },

    index: {
      type: Number,
    },
  },

  computed: {
    Neto() {
      return this.data.neto;
    },
  },
};
</script>

<style lang="scss" scoped></style>
