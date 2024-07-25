<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(App\Http\Controllers\Api\AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('loginAdmin', 'loginAdmin');
    Route::post('sendmail', 'sendmail');
    Route::post('send-code', 'send');
    Route::post('valitated', 'valitated');
    Route::post('resetpassword/{id}', 'resetpassword');
});

// afficher tout les trajets
Route::get('trajet', [App\Http\Controllers\TrajetController::class, 'index']);
Route::put('UpdateEtatTrajet/{id}', [App\Http\Controllers\TrajetController::class, 'UpdateEtatTrajet']);
Route::post('search', [App\Http\Controllers\ClientController::class, 'search']);

// acceuil data
Route::get('Acceuil', [App\Http\Controllers\Api\AuthController::class, 'Acceuil']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('user', [App\Http\Controllers\Api\AuthController::class, 'user']);
    Route::put('editUser/{id}', [App\Http\Controllers\Api\AuthController::class, 'editUser']);
    Route::delete('deletedUser/{id}', [App\Http\Controllers\Api\AuthController::class, 'deletedUser']);

    Route::get('notification', [App\Http\Controllers\ClientController::class, 'notification']);

    Route::controller(App\Http\Controllers\TrajetController::class)->group(function () {
        Route::get('trajet/{id}', 'show');
        Route::get('MyPosts', 'MyPosts');
        Route::post('trajet', 'store');
        Route::put('trajet/{id}', 'update');
        Route::put('UpdateEtatTrajet/{id}', 'UpdateEtatTrajet');
        Route::delete('trajet/{id}', 'destroy');
        Route::get('verifiedPiece', 'verifiedPiece');
        Route::delete('DeletedLastPost', 'DeletedLastPost');
    });

    Route::controller(App\Http\Controllers\VehiculeController::class)->group(function () {
        Route::post('SaveVehicule', 'SaveVehicule');
        Route::post('SaveVehiculePiece/{id}', 'SaveVehiculePiece');
        Route::get('VehiculePiece/{id}', 'VehiculePiece');
        Route::delete('Vehicule/{id}', 'DeleteVehicule');
    });

    Route::controller(App\Http\Controllers\ReservationController::class)->group(function () {
        Route::get('reservation', 'index');
        Route::get('reservations', 'Reservations');
        Route::get('reservations/{id}', 'FindReservation');
        Route::delete('reservation/{id}', 'deletedReservation');
        Route::put('reservation/{id}', 'updatedResev');
        Route::post('reservation', 'store');
        Route::put('DeniedReservation/{ReservationId}', 'DeniedReservation');
        Route::get('ClientTrajet/{trajetId}', 'ClientTrajet');
        Route::put('DeletedLastPost', 'DeletedLastPost');
        
    });

    // piece

    // tout les pieces
    Route::get('Allpieces', [App\Http\Controllers\PieceController::class, 'index']);
    // tout les pieces d'un utilisateur
    Route::get('Mypiece/{id}', [App\Http\Controllers\PieceController::class, 'Mypiece']);
    Route::post('StorePiece', [App\Http\Controllers\PieceController::class, 'StorePiece']);
    Route::post('UpdatePiece', [App\Http\Controllers\PieceController::class, 'UpdatePiece']);
    Route::get('GetPiece', [App\Http\Controllers\PieceController::class, 'GetPiece']);

    // routes dashboard
    Route::get('StartDashboard', [App\Http\Controllers\Api\AuthController::class, 'StartDashboard']);
    Route::get('AllVehicules', [App\Http\Controllers\Api\AuthController::class, 'AllVehicules']);
    Route::get('AllUsers', [App\Http\Controllers\Api\AuthController::class, 'AllUsers']);
    Route::post('CreateUser', [App\Http\Controllers\Api\AuthController::class, 'CreateUser']);
    Route::get('getFrais', [App\Http\Controllers\ReservationController::class, 'getFrais']);
    Route::put('updateFrais', [App\Http\Controllers\ReservationController::class, 'updateFrais']);

    // transactions
    Route::get('AllTransactions', [App\Http\Controllers\TransactionController::class, 'AllTransactions']);
    Route::get('DetailTransaction/{id}', [App\Http\Controllers\TransactionController::class, 'DetailTransaction']);
    Route::delete('DeleteTransaction/{id}', [App\Http\Controllers\TransactionController::class, 'DeleteTransaction']);
    Route::get('AllTransactions', [App\Http\Controllers\TransactionController::class, 'AllTransactions']);
    Route::get('CreditTransaction', [App\Http\Controllers\TransactionController::class, 'CreditTransaction']);
    Route::get('DebitTransaction', [App\Http\Controllers\TransactionController::class, 'DebitTransaction']);


    // messagerie
    Route::get('GetConversations', [App\Http\Controllers\ConversationController::class, 'getConversations']);
    Route::get('GetConversationMessages/{reservationId}', [App\Http\Controllers\ConversationController::class, 'getConversationMessages']);
    Route::post('sendMessage/{reservationId}', [App\Http\Controllers\ConversationController::class, 'sendMessage']);
    Route::put('MarkMessageAsRead', [App\Http\Controllers\ConversationController::class, 'markMessageAsRead']);
    Route::delete('DeleteMessage/{id}', [App\Http\Controllers\ConversationController::class, 'deleteMessage']);
    Route::get('GetConversationAdmin/{reserId}', [App\Http\Controllers\ConversationController::class, 'GetConversationAdmin']);
    
    
    // wallet
    Route::get('MyWallet', [App\Http\Controllers\WalletController::class, 'MyWallet']);
    Route::put('Retrait', [App\Http\Controllers\WalletController::class, 'Retrait']);
    
    // profil
    Route::get('UserPiece/{id}', [App\Http\Controllers\Api\AuthController::class, 'UserPiece']);
    Route::get('UserPosts/{id}', [App\Http\Controllers\Api\AuthController::class, 'UserPosts']);
    Route::get('UserVehicule/{id}', [App\Http\Controllers\Api\AuthController::class, 'UserVehicule']);
    Route::get('UserAvis/{id}', [App\Http\Controllers\Api\AuthController::class, 'UserAvis']);
    
    
    // avis
    Route::post('StoreAvis', [App\Http\Controllers\AvisController::class, 'StoreAvis']);
    Route::post('DeletedAvis/{id}', [App\Http\Controllers\AvisController::class, 'DeletedAvis']);

    // code promo
    Route::get('CodePromo/{id}', [App\Http\Controllers\CodePromoController::class, 'index']);
    Route::get('ValidedCodePromo/{id}', [App\Http\Controllers\CodePromoController::class, 'validated']);
    Route::get('UpdatedEtat/{id}', [App\Http\Controllers\CodePromoController::class, 'UpdatedEtat']);
    Route::get('DeletedCodePromo/{id}', [App\Http\Controllers\CodePromoController::class, 'DeletedCodePromo']);

});

