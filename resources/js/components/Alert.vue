<template>
  <div :class="estilo" role="alert">
    {{ titulo }}
    <hr />
    <!-- Aula 361 - evitando dependencias nos componentes vue -->
    <p>{{ detalhes.mensagem }}</p>
    <!-- Fim aula 361 -->

    <!-- <span v-if="detalhes.data.message">{{detalhes.data.message}}</span>
        <span v-if="detalhes.data.id">{{}}</span> -->
    <br />
    <ul v-if="detalhes.dados">
      <li v-for="(e, key) in detalhes.dados" :key="key">{{ e[0] }}</li>
    </ul>
  </div>
</template>

<script>
export default {
  props: ["tipo", "titulo", "detalhes"],
  computed: {
    estilo() {
      return "alert alert-" + this.tipo;
    },
  },
};
</script>
