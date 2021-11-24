<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de Modelos">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID do modelo">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Modelo" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o modelo">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Modelo" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Relação de modelos">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="modelos.data"

                            :visualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalModeloVisualizar' }"
                            :atualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalModeloAtualizar' }"
                            :remover="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalModeloRemover' }"

                            :titulos="{
                                id: {titulo: 'ID', tipo: 'texto'},
                                nome: {titulo: 'Nome', tipo: 'texto'},
                                marca: {titulo: 'Marca', tipo: 'marca'},
                                created_at: {titulo: 'Data de criação', tipo: 'data'}
                            }"
                        >
                        </table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in modelos.links" :key="key"
                                        :class="l.active ? 'page-item active' : 'page-item'"
                                        @click="paginacao(l)"
                                    >
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalModelo">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- Modal registro de um novo modelo -->
        <modal-component id="modalModelo" titulo="Adicionar modelo">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso!" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca!" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Modelo" id="novoNomeModelo" id-help="novoNomeModeloHelp" texto-ajuda="Informe o modelo">
                        <input type="text" class="form-control" id="novoNomeModelo" aria-describedby="novoNomeModeloHelp" placeholder="Modelo" v-model="nomeModelo">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="ID da marca" id="idMarca" id-help="idMarcaHelp" texto-ajuda="Informe o ID da marca">
                        <input type="text" class="form-control" id="idMarca" aria-describedby="idMarcaHelp" placeholder="ID" v-model="idMarca">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Número de portas" id="novoNumeroPortasModelo" id-help="novoNumeroPortasModeloHelp" texto-ajuda="Informe o número de portas">
                        <input type="number" class="form-control" id="novoNumeroPortasModelo" aria-describedby="novoNumeroPortasModeloHelp" placeholder="Nº portas" v-model="numeroPortasModelo">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Quantidade de lugares" id="novoQuantidadeLugaresModelo" id-help="novoQuantidadeLugaresModeloHelp" texto-ajuda="Informe a quantidade de lugares">
                        <input type="number" class="form-control" id="novoQuantidadeLugaresModelo" aria-describedby="novoQuantidadeLugaresModeloHelp" placeholder="Qtd. lugares" v-model="quantidadeLugaresModelo">
                    </input-container-component>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="novoAbsModelo" aria-describedby="novoAbsModeloHelp" v-model="absModelo">
                    <label class="form-check-label" for="novoAbsModelo" >Possui freios ABS</label>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="novoAirBagModelo" aria-describedby="novoAirBagModeloHelp" v-model="airBagModelo">
                    <label class="form-check-label" for="novoAirBagModelo" >Possui air bag</label>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagemModelo" id-help="novoImagemModeloHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file" id="novoImagemModelo" aria-describedby="novoImagemModeloHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>

        <!-- Modal de visualização de um modelo -->
        <modal-component id="modalModeloVisualizar" titulo="Visualizar modelo">
            <template v-slot:alertas>

            </template>

            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Modelo">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
                <input-container-component titulo="Nº de portas">
                    <input type="text" class="form-control" :value="$store.state.item.numero_portas" disabled>
                </input-container-component>
                <input-container-component titulo="Qtd. de lugares">
                    <input type="text" class="form-control" :value="$store.state.item.lugares" disabled>
                </input-container-component>
                <input-container-component titulo="ABS">
                    <input type="text" class="form-control" :value="$store.state.item.abs" disabled>
                </input-container-component>
                <input-container-component titulo="Air Bag">
                    <input type="text" class="form-control" :value="$store.state.item.air_bag" disabled>
                </input-container-component>
                <input-container-component titulo="Imagem">
                    <img :src="'storage/' + $store.state.item.imagem" v-if="$store.state.item.imagem">
                </input-container-component>
                <input-container-component titulo="Data de criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at | formataDataHoraGlobal" disabled>
                </input-container-component>

                <!-- Caso não haja uma marca relacionada ao modelo, apresenta essas mensagens -->
                <template v-if="!$store.state.item.marca || $store.state.item.marca == ''">
                    <input-container-component titulo="Marca">
                        <input type="text" class="form-control" :value="'Nenhuma marca associada'" disabled>
                    </input-container-component>
                </template>

                <!-- Verifica se existe uma marca associada ao modelo -->
                <template v-else>
                    <input-container-component titulo="ID da Marca">
                        <input type="text" class="form-control" :value="$store.state.item.marca_id" disabled>
                    </input-container-component>
                    <input-container-component titulo="Marca">
                        <input type="text" class="form-control" :value="$store.state.item.marca.nome" disabled>
                    </input-container-component>
                </template>


            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

        <!-- Modal de atualização de um modelo -->
        <modal-component id="modalModeloAtualizar" titulo="Atualizar modelo">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="ID">
                        <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                    </input-container-component>
                </div>
                <div class="form-group">
                    <input-container-component titulo="Modelo" id="atualizarModelo" id-help="atualizarModeloHelp" texto-ajuda="Informe o modelo">
                        <input type="text" class="form-control" id="atualizarModelo" aria-describedby="atualizarModeloHelp" placeholder="Modelo" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Número de portas" id="atualizarNumeroPortasModelo" id-help="atualizarNumeroPortasModeloHelp" texto-ajuda="Informe o número de portas">
                        <input type="number" class="form-control" id="atualizarNumeroPortasModelo" aria-describedby="atualizarNumeroPortasModeloHelp" placeholder="Nº portas" v-model="$store.state.item.numero_portas">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Quantidade de lugares" id="atualizarQuantidadeLugaresModelo" id-help="atualizarQuantidadeLugaresModeloHelp" texto-ajuda="Informe a quantidade de lugares">
                        <input type="number" class="form-control" id="atualizarQuantidadeLugaresModelo" aria-describedby="atualizarQuantidadeLugaresModeloHelp" placeholder="Qtd. lugares" v-model="$store.state.item.lugares">
                    </input-container-component>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="atualizarAbsModelo" aria-describedby="atualizarAbsModeloHelp" v-model="$store.state.item.abs">
                    <label class="form-check-label" for="atualizarAbsModelo" >Possui freios ABS</label>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="atualizarAirBagModelo" aria-describedby="atualizarAirBagModeloHelp" v-model="$store.state.item.air_bag">
                    <label class="form-check-label" for="atualizarAirBagModelo" >Possui air bag</label>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="atualizarImagemModelo" id-help="atualizarImagemModeloHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file" id="atualizarImagemModelo" aria-describedby="atualizarImagemModeloHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Salvar</button>
            </template>
        </modal-component>

        <!-- Modal de remover de um modelo -->
        <modal-component id="modalModeloRemover" titulo="Remover modelo">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Modelo">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
                <input-container-component titulo="Imagem">
                    <img :src="'storage/' + $store.state.item.imagem" v-if="$store.state.item.imagem">
                </input-container-component>
                <input-container-component titulo="Data de criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at | formataDataHoraGlobal" disabled>
                </input-container-component>

                <!-- Caso não haja uma marca relacionada ao modelo, apresenta essas mensagens -->
                <template v-if="!$store.state.item.marca || $store.state.item.marca == ''">
                    <input-container-component titulo="Marca">
                        <input type="text" class="form-control" :value="'Nenhuma marca associada'" disabled>
                    </input-container-component>
                </template>

                <!-- Verifica se existe uma marca associada ao modelo -->
                <template v-else>
                    <input-container-component titulo="ID da Marca">
                        <input type="text" class="form-control" :value="$store.state.item.marca_id" disabled>
                    </input-container-component>
                    <input-container-component titulo="Marca">
                        <input type="text" class="form-control" :value="$store.state.item.marca.nome" disabled>
                    </input-container-component>
                </template>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" @click="remover()">Remover</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/modelo',
                urlPaginacao: '',
                urlFiltro: '',
                nomeModelo: '',
                idMarca: '',
                numeroPortasModelo: '',
                quantidadeLugaresModelo: '',
                airBagModelo: false,
                absModelo: false,
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                modelos: { data: [] }, // Feito na aula 368 para correção de um erro
                busca: { id: '', nome: '' }, //Aula 371
            }
        },
        methods: {
            remover() {
                let confirmacao = confirm('Tem certeza que deseja remover este registro?')

                if (!confirmacao) return false

                let url = this.urlBase + '/' + this.$store.state.item.id
                // console.log(url)

                axios.delete(url)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'Resgistro removido com sucesso'
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                    })
            },
            atualizar() {
                let confirmacao = confirm('Tem certeza que deseja atualizar este registro?')

                if (!confirmacao) return false

                let url = this.urlBase + '/' + this.$store.state.item.id

                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('nome', this.$store.state.item.nome)
                formData.append('numero_portas', this.$store.state.item.numero_portas)
                formData.append('lugares', this.$store.state.item.lugares)
                //Air bag e abs precisam ser 0 ou 1
                this.$store.state.item.air_bag == true ? this.$store.state.item.air_bag = 1 : this.$store.state.item.air_bag = 0
                this.$store.state.item.abs == true ? this.$store.state.item.abs = 1 : this.$store.state.item.abs = 0
                formData.append('abs', this.$store.state.item.abs)
                formData.append('air_bag', this.$store.state.item.air_bag)

                if (this.arquivoImagem[0]) {
                    formData.append('imagem', this.arquivoImagem[0])
                }

                let config = {
                    headers: {
                        'Content-Type': 'multpart/form-data',
                    }
                }

                // console.log(formData
                //     // .get('_method')
                //     // .get('nome')
                //     // .get('numero_portas')
                //     // .get('lugares')
                //     // .get('abs')
                //     // .get('air_bag')
                //     // .get('imagem')
                // )

                axios.post(url, formData, config)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'Resgistro de marca atualizado com sucesso'

                        //limpar campo de seleção de arquivos
                        atualizarImagemModelo.value = ''

                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                    })
            },
            salvar() {
                let config = {
                    headers: {
                        'Content-Type': 'multpart/form-data',
                    }
                }

                let formData = new FormData()
                formData.append('marca_id', this.idMarca)
                formData.append('nome', this.nomeModelo)
                formData.append('imagem', this.arquivoImagem[0])
                formData.append('numero_portas', this.numeroPortasModelo)
                formData.append('lugares', this.quantidadeLugaresModelo)
                //Air bag e abs precisam ser 0 ou 1
                this.airBagModelo == true ? this.airBagModelo = 1 : this.airBagModelo = 0
                this.absModelo == true ? this.absModelo = 1 : this.absModelo = 0
                formData.append('air_bag', this.airBagModelo)
                formData.append('abs', this.absModelo)

                // console.log(formData.get('air_bag'))

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
            },
            pesquisar() {
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
            paginacao(l) {
                if (l.url) {
                    //this.urlBase = l.url //Ajusatando a url com o parametro de pagina

                    this.urlPaginacao = l.url.split('?')[1] // Aula 372

                    this.carregarLista() //requisitando novamente os dados para a nossa API
                }
            },
            carregarImagem(e) {
                this.arquivoImagem = e.target.files
            },
            carregarLista() {
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro // Aula 372

                axios.get(url)
                    .then(response => {
                        this.modelos = response.data
                        // console.log(this.modelos)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            }
        },
        mounted() { // quando o componente for carregado
            this.carregarLista() //executa esse método
        }
    }
</script>
