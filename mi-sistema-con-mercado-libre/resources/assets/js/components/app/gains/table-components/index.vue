<template>
  <div>
    {{ Index }}
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
    Index() {
      return this.index;
    },
  },
};
</script>

<style lang="scss" scoped></style>
