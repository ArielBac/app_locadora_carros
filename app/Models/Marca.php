<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    //Aula 299 - refatoração das regras de validação
    public function rules() {
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg'
        ];
    }
    /**
     * Aula 300 - probela com a validação unique no update
     * A unique possuí 3 params
     * 1) tabela
     * 2) nome da coluna que será pesquisada na tabela, por padrão é o nome do input
     * 3) id do registro que será desconsiderado na pesquisa
     */

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da marca já existe',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG ou JPEG'
        ];
    }

    public function modelos() {
        //Uma marca tem muitos modelos
        return $this->hasMany('App\Models\Modelo');
    }
}
