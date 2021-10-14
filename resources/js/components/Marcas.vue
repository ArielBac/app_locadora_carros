<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Aula 351 -->
                <!-- Inicio card busca marcas -->
                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                          <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da marca" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- Fim card busca marcas -->

                <!-- Inicio card listagem marcas -->
                <!-- o marcas.data é explicado na aula 367, sobre paginação --> <!-- parte da aula 373 -->
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="marcas.data"

                            :visualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar' }"
                            :atualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaAtualizar' }"
                            :remover="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover' }"

                            :titulos="{
                                id: {titulo: 'ID', tipo: 'texto'},
                                nome: {titulo: 'Nome', tipo: 'texto'},
                                imagem: {titulo: 'Imagem', tipo: 'imagem'},
                                created_at: {titulo: 'Data de criação', tipo: 'data'}
                            }"
                        >
                        </table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in marcas.links" :key="key"
                                        :class="l.active ? 'page-item active' : 'page-item'"
                                        @click="paginacao(l)"
                                    >
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- Fim card listagem marcas -->
                <!-- Fim Aula 351 -->

                <!-- Aula 348 - Card de busca-->
                <!--
                <div class="card mb-3">
                    <div class="card-header">Busca de marcas</div>

                    <div class="card-body">
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da marca">
                                </input-container-component>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </div>
                </div>
                -->
                <!-- Fim Aula 348 -->

                <!-- Aula 349 - Card de listagem de marcas -->
                <!--
                <div class="card">
                    <div class="card-header">Relação de marcas</div>

                    <div class="card-body">
                        <div class="form-row">
                            <table-component></table-component>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary btn-sm float-right">Adicionar</button>
                    </div>
                </div>
                -->
                <!-- Fim Aula 349 -->
            </div>
        </div>

        <!-- Aulas 353 e 354 -->
        <modal-component id="modalMarca" titulo="Adicionar marca">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso!" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca!" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe a marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da marca" v-model="nomeMarca">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagemNome" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file" id="novoImagemNome" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- Fim Aulas 353 e 354 -->

        <!-- Inicio do modal de visualização de marca  -->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar marca">
            <template v-slot:alertas>

            </template>

            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome da Marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
                <input-container-component titulo="Imagem">
                    <img :src="'storage/' + $store.state.item.imagem" v-if="$store.state.item.imagem">
                </input-container-component>
                <input-container-component titulo="Data de criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- Fim do modal de visualização de marca  -->

         <!-- Aulas 378, 379, 380 - Inicio do modal de Remover marca  -->
        <modal-component id="modalMarcaRemover" titulo="Remover marca">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome da Marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- Fim do modal de remover marca  -->

        <!-- Aulas 381, 382, 383 e384 - Inicio modal de atualização de marca -->
        <modal-component id="modalMarcaAtualizar" titulo="Atualizar marca">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="atualizarNome" id-help="atualizarNomeHelp" texto-ajuda="Informe a marca">
                        <input type="text" class="form-control" id="atualizarNome" aria-describedby="atualizarNomeHelp" placeholder="Nome da marca" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="atualizarImagemNome" id-help="atualizarImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file" id="atualizarImagemNome" aria-describedby="atualizarImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!-- Aulas 381, 382, 383 e384 - Fim modal de atualização de marca -->
    </div>
</template>

<script>
    export default {
        // computed: { //lógica modificada na aula 387
        //     token() {
        //         //Separa o que vem de document.cookie por ';', procura no array rersultante do split o indice o qual seu conteudo começa com 'token='
        //         let token = document.cookie.split(';').find(indice => {
        //             return indice.includes('token=')
        //         })

        //         //para pegar apenas o valor do token
        //         token = token.split('=')[1]

        //         token = 'Bearer ' + token

        //         return token
        //     }
        // },
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/marca',
                urlPaginacao: '', //Aula 372
                urlFiltro: '', //Aula 372
                nomeMarca: '',
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                marcas: { data: [] }, // Feito na aula 368 para correção de um erro
                busca: { id: '', nome: '' }, //Aula 371
            }
        },
        methods: {
            //Aulas 381, 382, 383 e 384
            atualizar() {
                let confirmacao = confirm('Tem certeza que deseja atualizar este registro?')

                if (!confirmacao) return false

                let url = this.urlBase + '/' + this.$store.state.item.id

                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('nome', this.$store.state.item.nome)

                if (this.arquivoImagem[0]) {
                    formData.append('imagem', this.arquivoImagem[0])
                }

                let config = {
                    headers: {
                        'Content-Type': 'multpart/form-data',
                        //na aula 387, essa config passa a ser feita de mofo global, para evitar repetição de código
                        // 'Accept': 'application/json',
                        // 'Authorization': this.token
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'Resgistro de marca atualizado com sucesso'

                        //limpar campo de seleção de arquivos
                        atualizarImagemNome.value = ''

                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                    })
            },
            //Aulas 381, 382, 383 e 384
            remover() {
                let confirmacao = confirm('Tem certeza que deseja remover este registro?')

                if (!confirmacao) return false

                let url = this.urlBase + '/' + this.$store.state.item.id

                let formData = new FormData()
                formData.append('_method', 'delete')

                //na aula 387, essa config passa a ser feita de mofo global, para evitar repetição de código
                // let config = {
                //     headers: {
                //         'Accept': 'application/json',
                //         'Authorization': this.token
                //     }
                // }

                axios.post(url, formData)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg

                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.erro
                    })
            },
            pesquisar() { //Aula 371 e 372
                // console.log(this.busca)

                let filtro = ''

                for (let chave in this.busca) {
                    // console.log(chave, this.busca[chave])

                    if (this.busca[chave]) {
                        if (filtro != '') {
                            filtro += ';'
                        }

                        filtro += chave + ':like:' + this.busca[chave]
                    }
                }

                if (filtro != '') {
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro=' + filtro
                } else {
                    this.urlFiltro = ''
                }

                this.carregarLista()
            },
            //Aula 370
            paginacao(l) {
                if (l.url) {
                    //this.urlBase = l.url //Ajusatando a url com o parametro de pagina

                    this.urlPaginacao = l.url.split('?')[1] // Aula 372

                    this.carregarLista() //requisitando novamente os dados para a nossa API
                }
            },
            //Fim aula 370

            // Aula 362 - listagem de marcas
            carregarLista() {

                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro // Aula 372
                // console.log(url)

                //Aula 363
                //na aula 387, essa config passa a ser feita de mofo global, para evitar repetição de código
                // let config = {
                //     headers: {
                //         'Accept': 'application/json',
                //         'Authorization': this.token
                //     }
                // }
                //Fima aula 363

                axios.get(url)
                    .then(response => {
                        this.marcas = response.data
                        // console.log(this.marcas)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            //Fim aula 362
            carregarImagem(e) {
                this.arquivoImagem = e.target.files
            },
            salvar() {
                console.log(this.nomeMarca, this.arquivoImagem[0])

                let formData = new FormData(); //formulaŕio para que seja possível definir seus atributos
                formData.append('nome', this.nomeMarca)
                formData.append('imagem', this.arquivoImagem[0]) //posição 0 pois estamos trabalhando com apenas um imagem

                let config = {
                    headers: {
                        'Content-Type': 'multpart/form-data',
                        //na aula 387, essa config passa a ser feita de mofo global, para evitar repetição de código
                        // 'Accept': 'application/json',
                        // Aula 357, para garantir que não haverá problema de aurotização, relacionados ao token que deve ser encaminhado para o back-end
                        // 'Authorization': this.token
                    }
                }

                // axios.post(<url>, <conteudo>, <config>)
                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado'
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registro: ' + response.data.id,
                        }
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message,
                            dados: errors.response.data.errors
                        }
                        // errors.response.data.message
                    })
            }
        },
        mounted() { // quando o componente for carregado
            this.carregarLista() //executa esse método
        }
    }
</script>
