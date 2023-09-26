<template>
  <div>
    {{ Cost | currency }}
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
    Cost() {
      return this.data.cost;
    },
  },
};
</script>

<style lang="scss" scoped></style>
