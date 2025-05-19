<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Certifique-se que o caminho para seu User model está correto
use Illuminate\Support\Facades\Password; // Usaremos para o reset final
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Mostra o formulário para o usuário inserir o e-mail.
     */
    public function showLinkRequestForm()
    {
        return view('auth.valida-email');
    }

    /**
     * Usuário submeteu o e-mail. Agora, se ele tiver uma palavra-chave,
     * pedimos a palavra-chave.
     */
    public function handleEmailSubmit(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'email.exists' => 'Não encontramos uma conta com este e-mail.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Verifique se o usuário tem uma palavra-chave/resposta secreta configurada
        // Supondo que você tenha uma coluna 'secret_keyword_hash' ou 'security_answer_hash' no seu model User
        if (empty($user->palavra_chave_secreta)) {
            return back()->withErrors(['email' => 'Nenhum método de recuperação simples configurado para esta conta.']);
        }

        // Guardar o e-mail na sessão para o próximo passo e redirecionar para o formulário da palavra-chave
        session(['reset_email' => $user->email]);
        return redirect()->route('password.show_keyword_form');
    }

    /**
     * Mostra o formulário para o usuário inserir a palavra-chave secreta.
     */
    public function showKeywordForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sessão expirada ou e-mail não fornecido.']);
        }
        return view('auth.valida-palavra-chave', ['email' => session('reset_email')]);
    }

    /**
     * Usuário submeteu a palavra-chave. Verificamos e, se correta,
     * redirecionamos para o formulário de nova senha.
     * O campo 'codigo' do seu reset-senha.blade.php será preenchido com um token temporário.
     */
    public function handleKeywordSubmit(Request $request)
    {
        $request->validate([
            // 'email' => 'required|email|exists:users,email', // O e-mail já deve estar na sessão
            'palavra_chave_secreta' => 'required|string',
        ], [
            'palavra_chave_secreta.required' => 'A palavra-chave secreta é obrigatória.',
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sessão expirada. Por favor, comece novamente.']);
        }

        $user = User::where('email', $email)->first();

        if (!$user || empty($user->palavra_chave_secreta) || !Hash::check($request->palavra_chave_secreta, $user->palavra_chave_secreta)) {
            return back()->withInput()->withErrors(['palavra_chave_secreta' => 'Palavra-chave secreta incorreta.']);
        }

        // Palavra-chave correta!
        // Gerar um token simples para o próximo passo (para o campo 'codigo')
        // Este token não precisa ser tão robusto quanto o do PasswordBroker para ESTE fluxo simplificado,
        // mas ainda é bom que seja de uso único e com tempo de expiração curto se armazenado.
        // Para simplificar, podemos usar um token gerado pelo PasswordBroker,
        // que já tem mecanismos de armazenamento e expiração.
        $token = Password::broker()->createToken($user);

        // Limpar o e-mail da sessão, pois o token agora carrega a informação necessária (indiretamente via DB)
        session()->forget('reset_email');

        // Redirecionar para o formulário de redefinição de senha que você já tem,
        // passando o token e o e-mail.
        // O seu 'reset-senha.blade.php' usará 'token' como o 'codigo'.
        return redirect()->route('password.reset.form', ['token' => $token, 'email' => $user->email]);
    }


    // ----- SEU FORMULÁRIO DE RESET DE SENHA FINAL (reset-senha.blade.php) -----
    // Este método será chamado pelo formulário onde o usuário digita a nova senha.

    /**
     * Exibe o formulário final de redefinição de senha (o seu reset-senha.blade.php).
     * Esta rota é chamada após a validação da palavra-chave.
     */
    public function showResetPasswordForm(Request $request, $token) // Recebe o token da URL
    {
        return view('auth.reset-senha', [ // Sua view existente
            'token' => $token,
            'email' => $request->email // O e-mail também é passado na URL/query string
        ]);
    }


    /**
     * Processa o formulário final de redefinição de senha.
     * O campo 'codigo' do seu formulário deve ser o 'token' gerado.
     */
    public function ResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required', // Este é o 'codigo' do seu formulário
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'token.required' => 'O código de redefinição é necessário.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.exists' => 'Este e-mail não corresponde a uma conta válida.',
            'password.required' => 'A nova senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ]);

        // Usar o PasswordBroker para redefinir a senha.
        // Ele verifica o token, e-mail e atualiza a senha.
        $response = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
                // Opcional: $user->setRememberToken(Str::random(60)); $user->save();
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __('passwords.reset'));
        }

        // Se falhou (token inválido, usuário não encontrado, etc.)
        // A mensagem de erro é geralmente baseada na constante $response.
        // Ex: __('passwords.token'), __('passwords.user')
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($response)]); // Usar a resposta do broker diretamente como chave de tradução
    }
}
