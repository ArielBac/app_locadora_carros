<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    /**
     * Aula 295, refaotrando código de acordo com uma terceira forma de manipular os models através do controlador
     * As que vimos até aqui foram manipulando de forma estática e de sugestão de tipo, veremos a
     * Injeção do Model
     *
     * Assim todos os métodos lidam da mesma forma com o modelo
     *
     * Não quer dizer que é a melhor forma
     */
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Inicio Aula 318
        $marcaRepository = new MarcaRepository($this->marca);

        if ($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,' . $request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if ($request->has('filtro')) {
            $marcaRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultadoPaginado(3), 200);
        //Fim Aula 318

        //------------------------------------------------------------------

        //Inicio aula 316
        /*
        $marcas = array();

        if ($request->has('atributos_modelos')) {
            $atributos_modelos = $request->atributos_modelos;
            $marcas = $this->marca->with('modelos:id,'.$atributos_modelos);
        } else {
            $marcas = $this->marca->with('modelos');
        }

        if ($request->has('filtro')) {
            $filtros = explode(';', $request->filtro);
            foreach ($filtros as $key => $condicao) {
                $c = explode(':', $condicao);
                $marcas = $marcas->where($c[0], $c[1], $c[2]);
            }
        }

        if ($request->has('atributos')) {
            $atributos = $request->atributos;
            //Com o método with, a coluna que contém o relacionamento precisa estar no contexto, no caso marca_id
            $marcas = $marcas->selectRaw($atributos)->get();
            // dd($request->atributos);
            //id,nome,imagem
        } else {
            $marcas = $marcas->get();
        }
        //Fim aula 316

        // $marcas = Marca::all();
        // $marcas = $this->marca->with('modelos')->get();

        //status de sucesso, o laravel retorna alguns desses status automaticamente, mas para fins didaticos, estamos explicitando aqui
        return response()->json($marcas, 200);
        */
    }

    /**
     * Método para retornar todas as marcas para serem utilizadas no select do modelos
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);
        return response()->json($marcaRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Aula 298 - validação de parâmetros - na aula 299 isso passou para o model
        // $regras = [
        //     'nome' => 'required|unique:marcas',
        //     'imagem' => 'required'
        // ];

        // $feedback = [
        //     'required' => 'O campo :attribute é obrigatório',
        //     'nome.unique' => 'O nome da marca já existe'
        // ];

        $request->validate($this->marca->rules(), $this->marca->feedback());
        //stateless - por padrão o validate redireciona o usuário pra requisição anterior, porém com o stateless, não
        //há informação sobre a requisição enterior, por isso o laravel manda o usuário para o rota principal,
        //para corrigir, é preciso ajustar a requisição com o Accept no cabeçalho, o client precisa indicar que aceita requisições do tipo json
        //Dessa forma o laravel vai retornar um json com o feedback no response

        // $marca = Marca::create($request->all());

        $imagem = $request->imagem;
        // $imagem->store('path', 'local');
        $imagem_urn = $imagem->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        //Aula 305, criando link simbólico para acesso as imagens via pasta public
        //basta executar php artisan storage:link


        //o status 201 equivale a criação com sucesso
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);

        if ($marca === null) {
            // Aula 297 - mostra como retornar um status de erro coerente
            return response()->json(['erro' => 'Recurso pesquisado não existe!'], 404); //json
        }

        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            // Aula 297 - mostra como retornar um status de erro coerente
            return response()->json(['erro' => 'Impossível realizar a atualização, o recurso solicitado não existe!'], 404);
        }

        //Aula 301 - verificação do método (put ou patch), para saber se a atualização é completa ou parcial
        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no model
            foreach ($marca->rules() as $input => $regra) {
                //coletar apenas as regras aplicaveis aos parametros parciais da requisição PATCH
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        //preenchendo o objeto marca com todos os dados do request
        $marca->fill($request->all());

        //se a imagem foi enchaminhada na requisição
        if ($request->file('imagem')) {
            //remove um arquivo atigo caso um novo arquivo tenha sido enviado no request
            Storage::disk('public')->delete($marca->imagem);

            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens', 'public');
            $marca->imagem = $imagem_urn;
        }

        $marca->save();
        return response()->json($marca, 200);

        /* Esse formato funciona bem se for uma atualização do tipo put, porém com o patch não, por isso há uma correção deste trecho, acima, na aula 383
        $imagem = $request->imagem;
        // $imagem->store('path', 'local');
        $imagem_urn = $imagem->store('imagens', 'public');

        //Aula 311 - preencher o objeto marca com os dados so request
        $marca->fill($request->all()); //o fill recebe um array, pega os parametros desse array e sobrescreve o objeto, no caso, $marca
        $marca->imagem = $imagem_urn;
        $marca->save();
        /*
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        */

        //Aula 306, o tipo form-data, utilizado para requisições com imagens, só é reconhecido através do método post
        //é preciso passar um parâmetro adicional na requisição (_method) utilizando o método post

        // return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            // Aula 297 - mostra como retornar um status de erro coerente
            return response()->json(['erro' => 'Impossível efetuar a remoção, o recurso solicitado não existe!'], 404);
        }

        //Aula 307 - remove um arquivo
        Storage::disk('public')->delete($marca->imagem);


        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}
