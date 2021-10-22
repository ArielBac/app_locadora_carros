<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de carros">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID do carro">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome do carro" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome do carro">
                                    <input type="number" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome do carro" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Relação de carros">
                    <template v-slot:conteudo>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Modelo</th>
                                    <th scope="col">Placa</th>
                                    <th scope="col">Disponibilidade</th>
                                    <th scope="col">Km</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                </tr>
                            </tbody>
                        </table>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="adicionar()">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/carro',
                nomeCarro: '',
                arquivoImagem: [],
                marcas: { data: [] }, // Feito na aula 368 para correção de um erro
                busca: { id: '', nome: '' }, //Aula 371
            }
        },
        methods: {
            adicionar() {

            },
            pesquisar() {

            }
        }
    }
</script>
