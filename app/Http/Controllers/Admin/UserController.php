<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('can:edit-users');
    }
    public function index()
    {

        $users = User::paginate(5);#pagina a página
        $loggedId = intval(Auth::id());


        return view('admin.users.index',[
            'users' => $users,
            'loggedId' => $loggedId
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if($user){
            return view('admin.users.edit',[
                'user' => $user
            ]);
        }
        return redirect()->route('users.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if($user){
            $data = $request->only([
                'name',
                'email',
                'password',
                'password_confirmation'

            ]);
            $validator = Validator::make([
                'name' => $data['name'],
                'email' => $data['email']
            ],[
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:100']
                ]
            );

            // 1. Alteração do nome
            $user->name = $data['name'];
            // 2. Alteração do email
            // 2.1 Primeiro, verificamos se o email foi alterado
            if($user->email != $data['email']){
                // 2.2. Verificamos se o novo email já existe
                $hasEmail = User::where('email', $data['email'])->get();
                // 2.3 Se não existir, nós alteramos
                if(count($hasEmail) === 0){
                    $user->email = $data['email'];
                }  else{
                    $validator->errors()->add('email', __('validation.unique',[
                        'attribute' => 'email',
                    ]));
                }
            }


            // 3 alteração da senha
            // 3.1 verifica se o usuário digitou senha
            if(!empty($data['password'])){
                // confere se tem 4 caracteres
                if(strlen($data['password'] >= 4)){
                    // 3.2 verifica se a confirmação está ok
                    if($data['password'] === $data['password_confirmation']){
                    // 3.3 Altera a senha
                        $user->password = Hash::make($data['password']);
                    }
                    else{
                        $validator->errors()->add('password', __('validation.confirmed',[
                            'attribute' => 'password'
                        ]));
                    }
                }else{
                    $validator->errors()->add('password', __('validation.min.string',[
                        'attribute' => 'password',
                        'min' => 4
                    ]));
                }
            }
            if(count($validator->errors()) > 0){
                return redirect()->route('users.edit',[
                    'user' => $id
                ])->withErrors($validator);
            }
            $user->save();
        }

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loggedId = intval(Auth::id());

        if($loggedId !== intval($id)){
            $user = User::find($id);
            $user->delete();
        }
        return redirect()->route('users.index');
    }

}
