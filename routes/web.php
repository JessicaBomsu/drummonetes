<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PianoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Página Home - não logada
Route::get('/', [HomeController::class, 'index'])->name('home');

// Quem somos
Route::get('/quem-somos', [HomeController::class, 'quemSomos'])->name('quem-somos');

// Autenticação
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::get('/reset-senha', [AuthController::class, 'showResetSenhaForm'])->name('reset-senha');
// Route::post('/reset-senha', [AuthController::class, 'resetSenha']);


// Rotas para o fluxo de reset de senha simplificado
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password/email', [ForgotPasswordController::class, 'handleEmailSubmit'])->name('password.handle_email');
Route::get('forgot-password/keyword', [ForgotPasswordController::class, 'showKeywordForm'])->name('password.show_keyword_form');
Route::post('forgot-password/keyword', [ForgotPasswordController::class, 'handleKeywordSubmit'])->name('password.handle_keyword');

// Rota para exibir o formulário final de reset (o seu reset-senha.blade.php)
// O {token} aqui é o token gerado após a validação da palavra-chave
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');

// Rota para processar o formulário final de reset
// O action do seu reset-senha.blade.php deve apontar para esta rota.
Route::post('reset-password', [ForgotPasswordController::class, 'ResetPassword'])->name('password.update');


// Conteúdo
Route::get('/home', [ContentController::class, 'index'])->name('index');
Route::get('/content', [ContentController::class, 'introducao'])->name('content.introducao');
Route::get('/content-conceitos', [ContentController::class, 'conceitos'])->name('content.conceitos');
Route::get('/content-historia', [ContentController::class, 'historia'])->name('content.historia');
Route::get('/content-conceitos-avancados', [ContentController::class, 'conceitosAvancados'])->name('content.conceitos-avancados');
Route::get('/content-aplicacoes', [ContentController::class, 'aplicacoes'])->name('content.aplicacoes');
Route::get('/content-empregabilidade', [ContentController::class, 'empregabilidade'])->name('content.empregabilidade');
Route::get('/content-conclusao', [ContentController::class, 'conclusao'])->name('content.conclusao');

// Projeto Piano
Route::get('/piano', [PianoController::class, 'index'])->name('piano');

// Quiz
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');
Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');

// Ranking
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');

// Criação de Questões (apenas para administradores)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
});