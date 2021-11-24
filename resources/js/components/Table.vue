<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in titulos" :key="key">{{ t.titulo }}</th>
                    <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'texto'">{{ valor }}</span>

                        <span v-if="titulos[chaveValor].tipo == 'marca' && valor !== null">{{ valor.nome }}</span><!-- A segunda condição do v-if verifica se existe uma marca associada a o determinado modelo-->
                        <span v-if="titulos[chaveValor].tipo == 'modelo' && valor !== null">{{ valor.nome }}</span><!-- A segunda condição do v-if verifica se existe um modelo associada a o determinado modelo-->

                        <span v-if="titulos[chaveValor].tipo == 'data'">{{ valor | formataDataHoraGlobal }}</span> <!-- lógica de formatação feita na aula 385 -->
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/' + valor" width="30" height="30">
                        </span>
                    </td>
                    <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel">
                        <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget" @click="setStore(dados[chave])">Visualizar</button>
                        <button v-if="atualizar.visivel" class="btn btn-outline-primary btn-sm" :data-toggle="atualizar.dataToggle" :data-target="atualizar.dataTarget" @click="setStore(dados[chave])">Atualizar</button>
                        <button v-if="remover.visivel" class="btn btn-outline-danger btn-sm" :data-toggle="remover.dataToggle" :data-target="remover.dataTarget" @click="setStore(dados[chave])">Remover</button>
                    </td>
                </tr>

                <!-- <tr v-for="obj in dados" :key="obj.id"> -->
                    <!-- Aula 365
                    <td v-if="titulos.includes(chave)" v-for="valor, chave in obj" :key="chave">
                        <span v-if="chave == 'imagem' ">
                            <img :src="'/storage/' + valor" width="30" height="30">
                        </span>
                        <span v-else>
                            {{ valor }}
                        </span>
                    </td>
                    Fim aula 365 -->

                    <!-- <th scope="row">{{ item.id }}</th>
                    <td>{{ item.nome }}</td>
                    <td><img :src="'/storage/' + item.imagem" width="30" height="30"></td> -->
                <!-- </tr> -->
            </tbody>
        </table>
    </div>
    <!-- <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Handle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td>@fat</td>
            </tr>
            <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td>@twitter</td>
            </tr>
        </tbody>
    </table> -->
</template>

<script>
    export default {
        // filters: { //Aula 385 - filtro para a data e hora
        //     formataDataTempo(d) {
        //         if (!d) return ''

        //         d = d.split('T')

        //         let data = d[0]
        //         let hora = d[1]

        //         //formatando a data
        //         data = data.split('-')
        //         data = data[2] + '/' + data[1] + '/' + data[0]

        //         //formatando a hora
        //         hora = hora.split('.')
        //         hora = hora[0]

        //         return data + ' ' +  hora
        //     }
        // },
        props: ['dados', 'titulos', 'atualizar', 'visualizar', 'remover'],
        methods: {
            setStore(obj) {
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.mensagem = ''
                this.$store.state.transacao.dados = ''
                this.$store.state.item = obj
                // console.log(this.$store.state.item)
            },
        },
        computed: {
            dadosFiltrados() {
                let campos = Object.keys(this.titulos) //recupera todas as chaves desse objeto
                // console.log(campos)
                let dadosFiltrados = []

                this.dados.map((item, chave) => {
                    // console.log(chave, item)

                    let intemFiltrado = {}
                    campos.forEach(campo => {
                        intemFiltrado[campo] = item[campo] // utilizar sintaxe de array para atribuir valores a objetos
                        // console.log(campo)
                    })
                    dadosFiltrados.push(intemFiltrado)
                    // console.log(intemFiltrado)
                })

                // console.log(dadosFiltrados)

                // console.log(this.dados)
                return dadosFiltrados //retorn um array de objetos
            },
        },
    }
</script>



