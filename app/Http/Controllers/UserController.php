<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at')->get();
        return view('user.index', compact('users'));
    }

    public function store(Request $request)
    {
        // Validação do e-mail
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'superuser' => 'nullable|boolean'
        ]);

        // Gera uma senha aleatória de 8 dígitos
        $password = Str::random(8);

        if (isset($validated['superuser']) && $validated['superuser'] == 1) {
            $superuser = 1;
        } else {
            $superuser = 0;
        }

        // Cria o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'superuser' => $superuser
        ]);

        return redirect()->back()->with([
            'success' => 'Usuário criado com sucesso!',
            'email' => $user->email,
            'password' => $password,
            'user_id' => $user->id
        ]);
    }

    public function toggleRole(User $user)
    {
        // Impede alteração no primeiro usuário cadastrado
        $firstUser = User::orderBy('created_at')->first();
        if ($user->id === $firstUser->id) {
            return redirect()->back()->with(['error' => 'Não é permitido alterar o primeiro usuário.']);
        }

        // Alterna o status de admin/supervisor
        $user->update([
            'superuser' => !$user->superuser
        ]);

        return redirect()->back()->with([
            'success' => 'Permissão alterada com sucesso!',
            'user_id' => $user->id
        ]);
    }


    // Gera uma nova senha para um usuário existente
    public function generateNewPassword(User $user)
    {
        // Obtém o primeiro usuário cadastrado no sistema
        $firstUser = User::orderBy('created_at')->select('id', 'created_at')->first();

        // Se o usuário selecionado for o primeiro, retorna erro
        if ($user->id === $firstUser->id) {
            return redirect()->back()->with(['error' => 'Escolha um usuário diferente do padrão.']);
        }
        // Gera nova senha
        $newPassword = Str::random(8);

        // Atualiza a senha no banco
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()->back()->with([
            'success' => 'Nova senha gerada com sucesso!',
            'email' => $user->email,
            'password' => $newPassword,
            'user_id' => $user->id
        ]);
    }
}
