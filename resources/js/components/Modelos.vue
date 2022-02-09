<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- Inicio card busca modelols -->
        <card-component titulo="Busca de Modelos">
          <template v-slot:conteudo>
            <div class="form-row">
              <div class="col mb-3">
                <input-container-component
                  titulo="ID"
                  id="inputIdModelo"
                  id-help="inputIdHelpModelo"
                  texto-ajuda="Informe o ID do modelo (opcional)"
                >
                  <input
                    type="number"
                    class="form-control"
                    id="inputIdModelo"
                    aria-describedby="inputIdHelpModelo"
                    placeholder="ID"
                    v-model="busca.id"
                  />
                </input-container-component>
              </div>
              <div class="col mb-3">
                <input-container-component
                  titulo="Modelo"
                  id="inputNomeModelo"
                  id-help="inputNomeHelpModelo"
                  texto-ajuda="Informe o modelo (opcional)"
                >
                  <input
                    type="text"
                    class="form-control"
                    id="inputNomeModelo"
                    aria-describedby="inputNomeHelpModelo"
                    placeholder="Modelo"
                    v-model="busca.nome"
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
        <!-- Fim card busca modelos -->

        <!-- Inicio card listagem modelos -->
        <card-component titulo="Relação de modelos">
          <template v-slot:conteudo>
            <table-component
              :dados="modelos.data"
              :visualizar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalVisualizarModelo',
              }"
              :atualizar="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalAtualizarModelo',
              }"
              :remover="{
                visivel: true,
                dataToggle: 'modal',
                dataTarget: '#modalRemoverModelo',
              }"
              :titulos="{
                id: { titulo: 'ID', tipo: 'texto' },
                nome: { titulo: 'Nome', tipo: 'texto' },
                marca: { titulo: 'Marca', tipo: 'marca' },
                created_at: { titulo: 'Data de criação', tipo: 'data' },
              }"
            >
            </table-component>
          </template>

          <template v-slot:rodape>
            <div class="row">
              <div class="col-10">
                <paginate-component>
                  <li
                    v-for="(l, key) in modelos.links"
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
                  data-target="#modalAdicionarModelo"
                >
                  Adicionar
                </button>
              </div>
            </div>
          </template>
        </card-component>
        <!-- Fim card listagem modelos -->
      </div>
    </div>

    <!-- Iício do modal de adiconar modelo -->
    <modal-component id="modalAdicionarModelo" titulo="Adicionar modelo">
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
          titulo="Erro ao tentar cadastrar o modelo!"
          v-if="transacaoStatus == 'erro'"
        ></alert-component>
      </template>

      <template v-slot:conteudo>
        <div class="form-group">
          <input-container-component
            titulo="Modelo"
            id="inputIdNovoModelo"
            id-help="inputIdHelpNovoModelo"
            texto-ajuda="Informe o modelo"
          >
            <input
              type="text"
              class="form-control"
              id="inputIdNovoModelo"
              aria-describedby="inputIdHelpNovoModelo"
              placeholder="Modelo"
              v-model="nomeModelo"
            />
          </input-container-component>
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Marca"
            id="idMarca"
            id-help="idMarcaHelp"
            texto-ajuda="Informe a marca"
          >
            <select
              name="Marca"
              class="form-control"
              id="idMarca"
              aria-describedby="idMarcaHelp"
              placeholder="Marca"
              v-model="idMarcaSelected"
            >
              <option disabled value="">Selecione uma marca</option>
              <option v-for="marca in marcas" :value="marca.id" :key="marca.id">
                {{ marca.nome }}
              </option>
            </select>
          </input-container-component>
        </div>
        <div class="form-group">
          <input-container-component
            titulo="Número de portas"
            id="inputIdNumeroPortasNovoModelo"
            id-help="inputIdHelpNumeroPortasNovoModelo"
            texto-ajuda="Informe o número de portas"
          >
            <input
              type="number"
              class="form-control"
              id="inputIdNumeroPortasNovoModelo"
              aria-describedby="inputIdHelpNumeroPortasNovoModelo"
              placeholder="Nº de portas"
              v-model="numeroPortasModelo"
            />
          </input-container-component>
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Quantidade de lugares"
            id="inputIdQuantidadeLugaresNovoModelo"
            id-help="inputIdHelpQuantidadeLugaresNovoModelo"
            texto-ajuda="Informe a quantidade de lugares"
          >
            <input
              type="number"
              class="form-control"
              id="inputIdQuantidadeLugaresNovoModelo"
              aria-describedby="inputIdHelpQuantidadeLugaresNovoModelo"
              placeholder="Qtd. de lugares"
              v-model="quantidadeLugaresModelo"
            />
          </input-container-component>
        </div>

        <div class="form-group form-check">
          <input
            type="checkbox"
            class="form-check-input"
            id="inputIdAbsNovoModelo"
            v-model="absModelo"
          />
          <label class="form-check-label" for="inputIdAbsNovoModelo"
            >Possui freios ABS</label
          >
        </div>

        <div class="form-group form-check">
          <input
            type="checkbox"
            class="form-check-input"
            id="inputIdAirBagNovoModelo"
            v-model="airBagModelo"
          />
          <label class="form-check-label" for="inputIdAirBagNovoModelo"
            >Possui air bag</label
          >
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Imagem"
            id="inputIdImagemNovoModelo"
            id-help="inputIdHelpImagemNovoModelo"
            texto-ajuda="Selecione uma imagem no formato PNG"
          >
            <input
              type="file"
              class="form-control-file"
              id="inputIdImagemNovoModelo"
              aria-describedby="inputIdHelpImagemNovoModelo"
              placeholder="Selecione uma imagem"
              @change="carregarImagem($event)"
            />
          </input-container-component>
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
    <!-- Fim do modal de adicionar modelo -->

    <!-- Inicio do modal de visualização de modelo  -->
    <modal-component id="modalVisualizarModelo" titulo="Visualizar modelo">
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
        <input-container-component titulo="Modelo">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.nome"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Nº de portas">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.numero_portas"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Qtd. de lugares">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.lugares"
            disabled
          />
        </input-container-component>
        <!-- Se a abs for 1, possui freios abs, caso contrário, não possui -->
        <input-container-component titulo="ABS">
          <input
            type="text"
            class="form-control"
            disabled
            v-if="$store.state.item.abs"
            value="Sim"
          />
          <input type="text" class="form-control" disabled v-else value="Não" />
        </input-container-component>
        <!-- Se o air_bag for 1, possui  airbag, caso contrário, não possui -->
        <input-container-component titulo="Air Bag">
          <input
            type="text"
            class="form-control"
            disabled
            v-if="$store.state.item.air_bag"
            value="Sim"
          />
          <input type="text" class="form-control" disabled v-else value="Não" />
        </input-container-component>
        <input-container-component titulo="Imagem">
          <img
            :src="'storage/' + $store.state.item.imagem"
            v-if="$store.state.item.imagem"
          />
        </input-container-component>
        <input-container-component titulo="Data de criação">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.created_at | formataDataHoraGlobal"
            disabled
          />
        </input-container-component>

        <!-- Caso não haja uma marca relacionada ao modelo, apresenta essas mensagens -->
        <template
          v-if="!$store.state.item.marca || $store.state.item.marca == ''"
        >
          <input-container-component titulo="Marca">
            <input
              type="text"
              class="form-control"
              :value="'Nenhuma marca associada'"
              disabled
            />
          </input-container-component>
        </template>

        <!-- Verifica se existe uma marca associada ao modelo -->
        <template v-else>
          <input-container-component titulo="ID da Marca">
            <input
              type="text"
              class="form-control"
              :value="$store.state.item.marca_id"
              disabled
            />
          </input-container-component>
          <input-container-component titulo="Marca">
            <input
              type="text"
              class="form-control"
              :value="$store.state.item.marca.nome"
              disabled
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
    <!-- Fim do modal de visualização de modelo  -->

    <!-- Inicio do modal de atualização de modelo -->
    <modal-component id="modalAtualizarModelo" titulo="Atualizar modelo">
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
        <div class="form-group">
          <input-container-component
            titulo="Modelo"
            id="inputIdAtualizarModelo"
            id-help="inputIdHelpAtualizarModelo"
            texto-ajuda="Informe o modelo"
          >
            <input
              type="text"
              class="form-control"
              id="inputIdAtualizarModelo"
              aria-describedby="inputIdHelpAtualizarModelo"
              placeholder="Modelo"
              v-model="$store.state.item.nome"
            />
          </input-container-component>
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Número de portas"
            id="inputIdNumeroPortasAtualizarModelo"
            id-help="inputIdHelpNumeroPortasAtualizarModelo"
            texto-ajuda="Informe o número de portas"
          >
            <input
              type="number"
              class="form-control"
              id="inputIdNumeroPortasAtualizarModelo"
              aria-describedby="inputIdHelpNumeroPortasAtualizarModelo"
              placeholder="Nº de portas"
              v-model="$store.state.item.numero_portas"
            />
          </input-container-component>
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Quantidade de lugares"
            id="inputIdQuantidadeLugaresAtualizarModelo"
            id-help="inputIdHelpQuantidadeLugaresAtualizarModelo"
            texto-ajuda="Informe a quantidade de lugares"
          >
            <input
              type="number"
              class="form-control"
              id="inputIdQuantidadeLugaresAtualizarModelo"
              aria-describedby="inputIdHelpQuantidadeLugaresAtualizarModelo"
              placeholder="Qtd. de lugares"
              v-model="$store.state.item.lugares"
            />
          </input-container-component>
        </div>

        <div class="form-group form-check">
          <input
            type="checkbox"
            class="form-check-input"
            id="inputIdAbsAtualizarModelo"
            v-model="$store.state.item.abs"
          />
          <label class="form-check-label" for="inputIdAbsAtualizarModelo"
            >Possui freios ABS</label
          >
        </div>

        <div class="form-group form-check">
          <input
            type="checkbox"
            class="form-check-input"
            id="inputIdAirBagAtualizarModelo"
            v-model="$store.state.item.air_bag"
          />
          <label class="form-check-label" for="inputIdAirBagAtualizarModelo"
            >Possui air bag</label
          >
        </div>

        <div class="form-group">
          <input-container-component
            titulo="Imagem"
            id="inputIdImagemAtualizarModelo"
            id-help="inputIdHelpImagemAtualizarModelo"
            texto-ajuda="Selecione uma imagem no formato PNG"
          >
            <input
              type="file"
              class="form-control-file"
              id="inputIdImagemAtualizarModelo"
              aria-describedby="inputIdHelpImagemAtualizarModelo"
              placeholder="Selecione uma imagem"
              @change="carregarImagem($event)"
            />
          </input-container-component>
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
    <!-- Fim do modal de atualização de modelo -->

    <!-- Inicio do modal de remover marca  -->
    <modal-component id="modalRemoverModelo" titulo="Remover modelo">
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
        <input-container-component titulo="Modelo">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.nome"
            disabled
          />
        </input-container-component>
        <input-container-component titulo="Imagem">
          <img
            :src="'storage/' + $store.state.item.imagem"
            v-if="$store.state.item.imagem"
          />
        </input-container-component>
        <input-container-component titulo="Data de criação">
          <input
            type="text"
            class="form-control"
            :value="$store.state.item.created_at | formataDataHoraGlobal"
            disabled
          />
        </input-container-component>

        <!-- Caso não haja uma marca relacionada ao modelo, apresenta essa mensagem -->
        <template
          v-if="!$store.state.item.marca || $store.state.item.marca == ''"
        >
          <input-container-component titulo="Marca">
            <input
              type="text"
              class="form-control"
              :value="'Nenhuma marca associada'"
              disabled
            />
          </input-container-component>
        </template>

        <!-- Verifica se existe uma marca associada ao modelo -->
        <template v-else>
          <input-container-component titulo="ID da Marca">
            <input
              type="text"
              class="form-control"
              :value="$store.state.item.marca_id"
              disabled
            />
          </input-container-component>
          <input-container-component titulo="Marca">
            <input
              type="text"
              class="form-control"
              :value="$store.state.item.marca.nome"
              disabled
            />
          </input-container-component>
        </template>
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
    <!-- Fim do modal de remover modelo  -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      urlBase: "http://localhost:8000/api/v1/modelo",
      urlMarcas: "http://localhost:8000/api/v1/marcas",
      urlPaginacao: "",
      urlFiltro: "",
      nomeModelo: "",
      idMarca: "",
      numeroPortasModelo: "",
      quantidadeLugaresModelo: "",
      airBagModelo: false,
      absModelo: false,
      arquivoImagem: [],
      transacaoStatus: "",
      transacaoDetalhes: {},
      modelos: { data: [] },
      busca: { id: "", nome: "" },

      idMarcaSelected: "",
      marcas: { data: [] },
    };
  },
  methods: {
    paginacao(l) {
      if (l.url) {
        this.urlPaginacao = l.url.split("?")[1];

        this.carregarLista();
      }
    },
    carregarLista() {
      let url = this.urlBase + "?" + this.urlPaginacao + this.urlFiltro;

      axios
        .get(url)
        .then((response) => {
          this.modelos = response.data;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    //Método para buscar as marcas no bd
    carregarListaMarcas() {
      let url = this.urlMarcas;

      axios
        .get(url)
        .then((response) => {
          this.marcas = response.data;
          // console.log(this.marcas)
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
      let config = {
        headers: {
          "Content-Type": "multpart/form-data",
        },
      };

      let formData = new FormData();
      formData.append("marca_id", this.idMarcaSelected);
      formData.append("nome", this.nomeModelo);
      formData.append("imagem", this.arquivoImagem[0]);
      formData.append("numero_portas", this.numeroPortasModelo);
      formData.append("lugares", this.quantidadeLugaresModelo);
      //Air bag e abs precisam ser 0 ou 1
      this.airBagModelo == true
        ? (this.airBagModelo = 1)
        : (this.airBagModelo = 0);
      this.absModelo == true ? (this.absModelo = 1) : (this.absModelo = 0);
      formData.append("air_bag", this.airBagModelo);
      formData.append("abs", this.absModelo);

      axios
        .post(this.urlBase, formData, config)
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
      formData.append("nome", this.$store.state.item.nome);
      formData.append("numero_portas", this.$store.state.item.numero_portas);
      formData.append("lugares", this.$store.state.item.lugares);
      //Air bag e abs precisam ser 0 ou 1
      this.$store.state.item.air_bag == true
        ? (this.$store.state.item.air_bag = 1)
        : (this.$store.state.item.air_bag = 0);
      this.$store.state.item.abs == true
        ? (this.$store.state.item.abs = 1)
        : (this.$store.state.item.abs = 0);
      formData.append("abs", this.$store.state.item.abs);
      formData.append("air_bag", this.$store.state.item.air_bag);

      if (this.arquivoImagem[0]) {
        formData.append("imagem", this.arquivoImagem[0]);
      }

      let config = {
        headers: {
          "Content-Type": "multpart/form-data",
        },
      };

      axios
        .post(url, formData, config)
        .then((response) => {
          this.$store.state.transacao.status = "sucesso";
          this.$store.state.transacao.mensagem =
            "Resgistro de modelo atualizado com sucesso";

          //Limpa o campo de seleção de arquivos
          inputIdImagemAtualizarModelo.value = "";

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
    this.carregarLista();
    this.carregarListaMarcas();
  },
};
</script>
