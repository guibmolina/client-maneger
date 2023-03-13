<template>
  <v-container class="fill-height">
    <v-responsive class="d-flex align-center">
      <MyTitle/>
      <Table></Table>
    </v-responsive>
  </v-container>
</template>

<script setup>
  import Table from '@/components/Table.vue'
  import MyTitle from '@/components/Title.vue'
</script>
