<template>
  <div>
    {{ Quantity }}
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
    Quantity() {
      if (this.data.isCHP) {
        return `${this.data.quantity} metros`;
      }

      return this.data.quantity;
    },
  },
};
</script>

<style lang="scss" scoped></style>
