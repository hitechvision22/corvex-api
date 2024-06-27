<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\users\SendInfoAccountLoginJob;
use App\Jobs\users\WelcomeUserJob;
use App\Mail\users\ResetPasswordMail;
use App\Mail\users\WelcomeUserMail;
use App\Models\Code;
use App\Models\Piece;
use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:160',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $extension = $image->getClientOriginalExtension();
            $newFileName = time() . '.' . $extension;
            $image->move(public_path('avatars'), $newFileName);
            $request['avatar'] = $newFileName;
        }
       
        $user = User::create($request->toArray());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        if(request('type') == 1) Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);
        $response = ['token' => $token];

        WelcomeUserJob::dispatch($user)->onQueue('UserEmail');
        return response()->json(['access_token' => $response, 'user' => $user], 200);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();
        // MokoloMarket@12345789
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token];


                return response(['access_token' => $response, 'user' => $user], 200);
            } else {
                $response = ["message" => "mot de passe incorrecte"];
                return response()->json($response, 422);
            }
        } else {
            $response = ["message" => 'ce compte n\'exite pas'];
            return response()->json($response, 422);
        }
    }

    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();
        // MokoloMarket@12345789
        if ($user) {
            if ($user->type == 3) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $response = ['token' => $token];

                    return response(['access_token' => $response, 'user' => $user], 200);
                } else {
                    $response = ["message" => "mot de passe incorrecte"];
                    return response()->json($response, 422);
                }
            } else {
                $response = ["message" => 'acces interdit'];
                return response()->json($response, 422);
            }
        } else {
            $response = ["message" => 'ce compte n\'exite pas'];
            return response()->json($response, 422);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function user()
    {
        $user = User::with('trajets', 'reservations', 'vehicule')->find(Auth::user()->id);

        return response()->json($user);
    }

    public function deletedUser($id){
        User::find($id)->delete();
        return response()->json(['message'=>'utilisateur supprimer']);
    }


    public function editUser(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->name) $user->name = $request->name;
        if ($request->prenom) $user->prenom = $request->prenom;
        if ($request->phone) $user->phone = $request->phone;
        if ($request->ville) $user->ville = $request->ville;
        if ($request->hasFile('avatar')) {
            File::delete(public_path() . '/images/' . $user->avatar);
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $imgAvatarname = time() . '.' . $extension;
            $file->move(public_path('images'), $imgAvatarname);
            $user->avatar = $imgAvatarname;
        }
        $user->update();
        return response()->json(['message' => 'user updated']);
    }


    public function sendmail(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        $code = rand(1542, 9999);
        if ($user) {
            try {
                Mail::to($request->email)->send(new ResetPasswordMail($code));
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => "mauvaise connexion",
                ]);
            }
            $codelink = Code::where('email', $request->email)->first();
            if ($codelink) {
                Code::where('email', $request->email)->update(['code' => $code]);
            } else {
                Code::create(['email' => $request->email, 'code' => $code]);
            }
            return response()->json([
                'message' => true,
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'message' => "Aucun compte trouve"
            ], 422);
        }
    }


    public function send(Request $request)
    {
        $code = rand(2657, 9999);
        Mail::to($request->email)->send(new ResetPasswordMail($code));
        $preuv = Code::where("email", $request->email)->first();
        if ($preuv) {
            Code::where("email", $request->email)->update(['code' => $code]);
        } else {
            $codelink = new Code();
            $codelink->email = $request->email;
            $codelink->code = $code;
            $codelink->save();
        }

        return response()->json(['message' => 'updated successfully']);
    }

    public function valitated(Request $request)
    {
        $line = Code::where("code", $request->code)->where("email", $request->email)->first();
        if ($line) {
            return response()->json(['message' => true]);
        }
        return response()->json(['message' => false]);
    }

    public function resetpassword(Request $request, $id)
    {
        User::find($id)->update(['password' => Hash::make(request('password'))]);
        return response(['message' => 'updated successfully']);
    }

    public function Acceuil()
    {
        $Alltrajets = Trajet::where('etat', 'Actif')->orderBy('created_at', 'DESC')->simplePaginate(30);
        return response()->json([$Alltrajets]);
    }

    public function StartDashboard()
    {
        $chauffeurs = User::where('type', 1)->get()->count();
        $clients = User::where('type', 0)->get()->count();
        $covoits = Trajet::all()->count();
        $resers = Reservation::all()->count();
        $users = User::all()->count();
        $caissieres = User::all()->count();

        $reservations = Cache::rememberForever('reservation-' . request('page', 1), function () {
            return Reservation::with('trajet', 'user')->orderBy('created_at', 'DESC')->simplePaginate(30);
        });
        return response()->json([
            $chauffeurs,
            $clients,
            $covoits,
            $resers,
            $reservations,
            $users,
            $caissieres,
        ]);
    }

    public function AllUsers()
    {
        $users = User::orderBy('created_at', 'desc')->simplePaginate(30);
        return response()->json($users);
    }

    public function AllVehicules()
    {
        $vehicules = Vehicule::simplePaginate(30);
        return response()->json($vehicules);
    }

    public function SwithUser(Request $request, $id)
    {
        User::find($id)->update(['status' => $request->status]);
        return response()->json(['message' => 'status mis a jour']);
    }

    public function CreateUser(Request $request){

        $user = new User();
        $user->name = $request->name;
        $user->email= $request->email;
        $user->phone= $request->numero;
        $user->type = $request->type;
        $user->Viewpassword = trim($request->name.time());
        $user->password = Hash::make($user->Viewpassword);
        $user->save();

        SendInfoAccountLoginJob::dispatch($user)->onQueue('userEmail');
        return response()->json($user);
    }
}
