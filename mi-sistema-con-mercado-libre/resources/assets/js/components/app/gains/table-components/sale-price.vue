<template>
  <div>
    {{ SalePrice | currency }}
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
    SalePrice() {
      return this.data.sale_price;
    },
  },
};
</script>

<style lang="scss" scoped></style>
