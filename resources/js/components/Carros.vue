<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- Inicio card busca carros -->
        <card-component titulo="Busca de carros">
          <template v-slot:conteudo>
            <div class="form-row">
              <div class="col mb-3">
                <input-container-component
                  titulo="ID"
                  id="inputIdCarro"
                  id-help="inputIdHelpCarro"
                  texto-ajuda="Opcional. Informe o ID do carro"
                >
                  <input
                    type="number"
                    class="form-control"
                    id="inputIdCarro"
                    aria-describedby="inputIdHelpCarro"
                    placeholder="ID"
                    v-model="busca.id"
                  />
                </input-container-component>
              </div>
              <div class="col mb-3">
                <input-container-component
                  titulo="Placa"
                  id="inputPlacaCarro"
                  id-help="inputPlacaHelpCarro"
                  texto-ajuda="Opcional. Informe o Placa do carro"
                >
                  <input
                    type="text"
                    class="form-control"
                    id="inputPlacaCarro"
                    aria-describedby="inputPlacaHelpCarro"
                    placeholder="Placa"
                    v-model="busca.placa"
                  />
                </input-container-component>
              </div>
            </div>
          </template>

          <template v-slot:rodape>
            <button
              type="submit"
              class="btn btn-primary btn-sm float-right"
              @click="pesquisar()"
            >
              Pesquisar
            </button>
          </template>
        </card-component>
        <!-- Fim card busca carros -->

        <!-- Inicio card listagem carros -->
        <card-component titulo="Relação de carros">
          <template v-slot:conteudo>
            <table-component
              :dados="carros.data"
              :visualizar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalVisualizarCarro',
              }"
              :atualizar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalAtualizarCarro',
              }"
              :remover="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalRemoverCarro',
              }"
              :titulos="{
                id: { titulo: 'ID', tipo: 'texto' },
                modelo: { titulo: 'Modelo', tipo: 'modelo' },
                placa: { titulo: 'Placa', tipo: 'texto' },
                disponivel: {
                  titulo: 'Disponibilidade',
                  tipo: 'checkBoxDisponivel',
                },
                km: { titulo: 'Km', tipo: 'texto' },
              }"
            >
            </table-component>
          </template>

          <template v-slot:rodape>
            <div class="row">
              <div class="col-10">
                <paginate-component>
                  <li
                    v-for="(l, key) in carros.links"
                    :key="key"
                    :class="l.active ? 'page-item active' : 'page-item'"
                    @click="paginacao(l)"
                  >
                    <a class="page-link" v-html="l.label"></a>
                  </li>
                </paginate-component>
              </div>
              <div class="col">
                <button
                  type="button"
                  class="btn btn-primary btn-sm float-right"
                  data-toggle="modal"
                  data-target="#modalAdicionarCarro"
                >
                  Adicionar
                </button>
              </div>
            </div>
          </template>
        </card-component>
        <!-- Fim card listagem carros -->
      </div>
    </div>

    <!-- Iício do modal de adiconar carro -->
    <modal-component id="modalAdicionarCarro" titulo="Adicionar carro">
      <template v-slot:alertas>
        <alert-component
          tipo="success"
          :detalhes="transacaoDetalhes"
          titulo="Cadastro realizado com sucesso!"
          v-if="transacaoStatus == 'adicionado'"
        ></alert-component>
        <alert-component
          tipo="danger"
          :detalhes="transacaoDetalhes"
          titulo="Erro ao tentar cadastrar o carro!"
          v-if="transacaoStatus == 'erro'"
        ></alert-component>
      </template>

      <template v-slot:conteudo>
        <div class="form-group">
          <input-container-component
            titulo="ID do modelo"
            id="inputIdModeloCarro"
            id-help="inputIdHelpModeloCarro"
            texto-ajuda="Informe o modelo"
          >
            <select
              name="Modelo"
              class="form-control"
              id="inputIdModeloCarro"
              aria-describedby="inputIdHelpModeloCarro"
              placeholder="Modelo"
              v-model="idModeloSelected"
            >
              <option disabled value="">Selecione um modelo</option>
              <option
                v-for="modelo in modelos"
                :value="modelo.id"
                :key="modelo.id"
              >
                {{ modelo.nome }}
              </option>
            </select>
          </input-container-component>
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Placa"
            id="inputIdPlacaCarro"
            id-help="inputIdHelpPlacaCarro"
            texto-ajuda="Informe a placa do carro"
          >
            <input
              type="text"
              class="form-control"
              id="inputIdPlacaCarro"
              aria-describedby="inputIdHelpPlacaCarro"
              placeholder="Placa"
              v-model="placa"
            />
          </input-container-component>
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Km"
            id="inputIdkmCarro"
            id-help="inputIdHelpkmCarro"
            texto-ajuda="Informe a quilometragem do carro"
          >
            <input
              type="number"
              class="form-control"
              id="inputIdkmCarro"
              aria-describedby="inputIdHelpkmCarro"
              placeholder="Km"
              v-model="km"
            />
          </input-container-component>
        </div>

        <div class="form-group form-check">
          <input
            type="checkbox"
            class="form-check-input"
            id="inputIdDisponivelCarro"
            aria-describedby="inputIdHelpDisponivelCarro"
            v-model="disponivel"
          />
          <label class="form-check-label" for="inputIdDisponivelCarro"
            >Disponibilidade</label
          >
        </div>
      </template>

      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Fechar
        </button>
        <button type="button" class="btn btn-primary" @click="salvar()">
          Salvar
        </button>
      </template>
    </modal-component>
    <!-- Fim do modal de adicionar carro -->

    <!-- Inicio do modal de visualização de carro  -->
    <modal-component id="modalVisualizarCarro" titulo="Visualizar carro">
      <template v-slot:alertas> </template>

      <template v-slot:conteudo>
        <input-container-component titulo="ID">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.id"
            disabled
          />
        </input-container-component>

        <input-container-component titulo="Placa">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.placa"
            disabled
          />
        </input-container-component>

        <!-- Se a disponibilidade for 1, está disponível, caso contrário, está indisponível -->
        <input-container-component titulo="Disponibilidade">
          <input
            type="text"
            class="form-control"
            disabled
            v-if="$store.state.item.disponivel"
            value="Disponível"
          />
          <input
            type="text"
            class="form-control"
            disabled
            v-else
            value="Indisponível"
          />
        </input-container-component>

        <!-- Caso não haja um modelo relacionada ao carro, apresenta essas mensagens -->
        <template
          v-if="!$store.state.item.modelo || $store.state.item.modelo == ''"
        >
          <input-container-component titulo="Modelo">
            <input
              type="text"
              class="form-control"
              :value="'Nenhum modelo associado'"
              disabled
            />
          </input-container-component>
        </template>

        <!-- Verifica se existe um modelo associada ao carro -->
        <template v-else>
          <input-container-component titulo="Modelo">
            <input
              type="text"
              class="form-control"
              :value="$store.state.item.modelo.nome"
              disabled
            />
          </input-container-component>
          <input-container-component titulo="Nº de portas">
            <input
              type="text"
              class="form-control"
              :value="$store.state.item.modelo.numero_portas"
              disabled
            />
          </input-container-component>
          <input-container-component titulo="Qtd. de lugares">
            <input
              type="text"
              class="form-control"
              :value="$store.state.item.modelo.lugares"
              disabled
            />
          </input-container-component>
          <!-- Se a abs for 1, possui freios abs, caso contrário, não possui -->
          <input-container-component titulo="ABS">
            <input
              type="text"
              class="form-control"
              disabled
              v-if="$store.state.item.modelo.abs"
              value="Sim"
            />
            <input
              type="text"
              class="form-control"
              disabled
              v-else
              value="Não"
            />
          </input-container-component>
          <!-- Se o air_bag for 1, possui  airbag, caso contrário, não possui -->
          <input-container-component titulo="Air Bag">
            <input
              type="text"
              class="form-control"
              disabled
              v-if="$store.state.item.modelo.air_bag"
              value="Sim"
            />
            <input
              type="text"
              class="form-control"
              disabled
              v-else
              value="Não"
            />
          </input-container-component>
          <input-container-component titulo="Imagem">
            <img
              :src="'storage/' + $store.state.item.modelo.imagem"
              v-if="$store.state.item.modelo.imagem"
            />
          </input-container-component>
        </template>
      </template>

      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Fechar
        </button>
      </template>
    </modal-component>
    <!-- Fim do modal de visualização de carro  -->

    <!-- Modal para atualizar um carros -->
    <modal-component id="modalAtualizarCarro" titulo="Atualizar carro">
      <template v-slot:alertas>
        <alert-component
          tipo="success"
          titulo="Transação realizada com sucesso"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'sucesso'"
        ></alert-component>
        <alert-component
          tipo="danger"
          titulo="Erro na transação"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'erro'"
        ></alert-component>
      </template>

      <template v-slot:conteudo>
        <div class="form-group">
          <input-container-component titulo="ID">
            <input
              type="text"
              class="form-control"
              :value="$store.state.item.id"
              disabled
            />
          </input-container-component>
        </div>

        <!-- Caso não haja um modelo relacionada ao carro, apresenta essas mensagens -->
        <template
          v-if="!$store.state.item.modelo || $store.state.item.modelo == ''"
        >
          <input-container-component titulo="Modelo">
            <input
              type="text"
              class="form-control"
              :value="'Nenhum modelo associado'"
              disabled
            />
          </input-container-component>
        </template>

        <!-- Verifica se existe um modelo associada ao carro -->
        <template v-else>
          <input-container-component titulo="Modelo">
            <input
              type="text"
              class="form-control"
              :value="$store.state.item.modelo.nome"
              disabled
            />
          </input-container-component>
        </template>

        <div class="form-group">
          <input-container-component
            titulo="Placa"
            id="InpuIdAtualizarPlacaCarro"
            id-help="inputIdHelpAtualizarPlacaCarro"
            texto-ajuda="Informe a nova placa do carro"
          >
            <input
              type="text"
              class="form-control"
              id="InpuIdAtualizarPlacaCarro"
              aria-describedby="inputIdHelpAtualizarPlacaCarro"
              placeholder="Placa"
              v-model="$store.state.item.placa"
            />
          </input-container-component>
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Km"
            id="inputIdAtualizarKmCarro"
            id-help="inputIdHelpAtualizaKmCarro"
            texto-ajuda="Informe a nova quilometragem do carro"
          >
            <input
              type="number"
              class="form-control"
              id="inputIdAtualizarKmCarro"
              aria-describedby="inputIdHelpAtualizaKmCarro"
              placeholder="Km"
              v-model="$store.state.item.km"
            />
          </input-container-component>
        </div>

        <div class="form-group form-check">
          <input
            type="checkbox"
            class="form-check-input"
            id="inputIdAtualizarDicponivelCarro"
            aria-describedby="inputIdHelpAtualizarDisponivelCarro"
            v-model="$store.state.item.disponivel"
          />
          <label class="form-check-label" for="inputIdAtualizarDicponivelCarro"
            >Disponibilidade</label
          >
        </div>
      </template>

      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Fechar
        </button>
        <button type="button" class="btn btn-primary" @click="atualizar()">
          Salvar
        </button>
      </template>
    </modal-component>
    <!-- Fim do modal de atualização de carro -->

    <!-- Inicio do modal de remover carro  -->
    <modal-component id="modalRemoverCarro" titulo="Remover carro">
      <template v-slot:alertas>
        <alert-component
          tipo="success"
          titulo="Transação realizada com sucesso"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'sucesso'"
        ></alert-component>
        <alert-component
          tipo="danger"
          titulo="Erro na transação"
          :detalhes="$store.state.transacao"
          v-if="$store.state.transacao.status == 'erro'"
        ></alert-component>
      </template>

      <template v-slot:conteudo>
        <input-container-component titulo="ID">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.id"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Placa">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.placa"
            disabled
          />
        </input-container-component>

        <!-- Caso não haja um modelo relacionada ao carro, apresenta essas mensagens -->
        <template
          v-if="!$store.state.item.modelo || $store.state.item.modelo == ''"
        >
          <input-container-component titulo="Modelo">
            <input
              type="text"
              class="form-control"
              :value="'Nenhum modelo associado'"
              disabled
            />
          </input-container-component>
        </template>

        <!-- Verifica se existe um modelo associada ao carro -->
        <template v-else>
          <input-container-component titulo="Modelo">
            <input
              type="text"
              class="form-control"
              :value="$store.state.item.modelo.nome"
              disabled
            />
          </input-container-component>
        </template>

        <input-container-component titulo="Data de criação">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.created_at | formataDataHoraGlobal"
            disabled
          />
        </input-container-component>
      </template>

      <template v-slot:rodape>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Fechar
        </button>
        <button
          type="button"
          class="btn btn-primary"
          data-dismiss="modal"
          @click="remover()"
        >
          Remover
        </button>
      </template>
    </modal-component>
    <!-- Fim do modal de remover carro  -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      urlBase: "http://localhost:8000/api/v1/carro",
      urlModelos: "http://localhost:8000/api/v1/modelos",
      urlPaginacao: "",
      urlFiltro: "",
      placa: "",
      idModelo: "",
      disponivel: false,
      km: "",
      arquivoImagem: [],
      transacaoStatus: "",
      transacaoDetalhes: {},
      carros: { data: [] }, // Feito na aula 368 para correção de um erro
      busca: { id: "", placa: "" }, //Aula 371

      idModeloSelected: "",
      modelos: { data: [] },
    };
  },
  methods: {
    paginacao(l) {
      if (l.url) {
        //this.urlBase = l.url //Ajusatando a url com o parametro de pagina

        this.urlPaginacao = l.url.split("?")[1]; // Aula 372

        this.carregarLista(); //requisitando novamente os dados para a nossa API
      }
    },
    carregarLista() {
      let url = this.urlBase + "?" + this.urlPaginacao + this.urlFiltro; // Aula 372

      axios
        .get(url)
        .then((response) => {
          this.carros = response.data;
          // console.log(this.carros)
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    //Método para buscar os modelos no bd
    carregarListaModelos() {
      let url = this.urlModelos;

      axios
        .get(url)
        .then((response) => {
          this.modelos = response.data;
          // console.log(this.modelos)
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    carregarImagem(e) {
      this.arquivoImagem = e.target.files;
    },
    pesquisar() {
      let filtro = "";

      for (let chave in this.busca) {
        // console.log(chave, this.busca[chave])

        if (this.busca[chave]) {
          if (filtro != "") {
            filtro += ";";
          }

          filtro += chave + ":like:" + this.busca[chave];
        }
      }

      if (filtro != "") {
        this.urlPaginacao = "page=1";
        this.urlFiltro = "&filtro=" + filtro;
      } else {
        this.urlFiltro = "";
      }

      this.carregarLista();
    },
    salvar() {
      let formData = new FormData();
      formData.append("modelo_id", this.idModeloSelected);
      formData.append("placa", this.placa);
      formData.append("km", this.km);
      //Disponível precisa ser 0 ou 1
      this.disponivel == true ? (this.disponivel = 1) : (this.disponivel = 0);
      formData.append("disponivel", this.disponivel);

      axios
        .post(this.urlBase, formData)
        .then((response) => {
          this.transacaoStatus = "adicionado";
          this.transacaoDetalhes = {
            mensagem: "ID do registro: " + response.data.id,
          };
          this.carregarLista();
        })
        .catch((errors) => {
          this.transacaoStatus = "erro";
          this.transacaoDetalhes = {
            mensagem: errors.response.data.message,
            dados: errors.response.data.errors,
          };
          // errors.response.data.message
        });
    },
    atualizar() {
      let confirmacao = confirm(
        "Tem certeza que deseja atualizar este registro?"
      );

      if (!confirmacao) return false;

      let url = this.urlBase + "/" + this.$store.state.item.id;

      let formData = new FormData();
      formData.append("_method", "patch");
      formData.append("placa", this.$store.state.item.placa);
      formData.append("km", this.$store.state.item.km);
      //Disponivel precisa ser 0 ou 1
      this.$store.state.item.disponivel == true
        ? (this.$store.state.item.disponivel = 1)
        : (this.$store.state.item.disponivel = 0);
      formData.append("disponivel", this.$store.state.item.disponivel);

      axios
        .post(url, formData)
        .then((response) => {
          this.$store.state.transacao.status = "sucesso";
          this.$store.state.transacao.mensagem =
            "Resgistro do carro atualizado com sucesso";

          this.carregarLista();
        })
        .catch((errors) => {
          this.$store.state.transacao.status = "erro";
          this.$store.state.transacao.mensagem = errors.response.data.message;
          this.$store.state.transacao.dados = errors.response.data.errors;
        });
    },
    remover() {
      let confirmacao = confirm(
        "Tem certeza que deseja remover este registro?"
      );

      if (!confirmacao) return false;

      let url = this.urlBase + "/" + this.$store.state.item.id;

      axios
        .delete(url)
        .then((response) => {
          this.$store.state.transacao.status = "sucesso";
          this.$store.state.transacao.mensagem =
            "Resgistro removido com sucesso";
          this.carregarLista();
        })
        .catch((errors) => {
          this.$store.state.transacao.status = "erro";
          this.$store.state.transacao.mensagem = errors.response.data.message;
          this.$store.state.transacao.dados = errors.response.data.errors;
        });
    },
  },
  mounted() {
    // quando o componente for carregado
    this.carregarLista(); //executa esse método
    this.carregarListaModelos();
  },
};
</script>
