<template>
  <div>
    {{ Earning | currency }}
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
    Earning() {
      return this.data.earning;
    },
  },
};
</script>

<style lang="scss" scoped></style>
