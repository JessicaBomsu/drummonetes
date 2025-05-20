<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        return view('home-logada');
    }
    
    public function introducao()
    {
        return view('content/conteudo-introducao');
    }
    
    public function conceitos()
    {
        return view('content/conteudo-conceitos');
    }
    
    public function historia()
    {
        return view('content/conteudo-historia');
    }

    public function conceitosAvancados()
    {
        return view('content/conteudo-conceitos-avancados');
    }

    public function aplicacoes()
    {
        return view('content/conteudo-aplicacoes');
    }

    public function empregabilidade()
    {
        return view('content/conteudo-empregabilidade');
    }

    public function conclusao()
    {
        return view('content/conteudo-conclusao');
    }
    
    public function content()
    {
        $topics = [
            'Introdução à Computação Gráfica',
            'Sistemas de Coordenadas',
            'Transformações Geométricas',
            'Projeções 3D',
            'Iluminação e Sombreamento',
            'Texturização',
            'Ray Tracing',
            'OpenGL/WebGL'
        ];
        
        return view('conteudo', compact('topics'));
    }
}