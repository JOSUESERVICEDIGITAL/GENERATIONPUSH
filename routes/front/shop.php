<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\QrOrderController;

/*
|--------------------------------------------------------------------------
| BOUTIQUE — COMMANDES
|--------------------------------------------------------------------------
|
| Toutes les routes de commande nécessitent un utilisateur connecté.
|
| Parcours :
| Produit
|   ↓
| Création de commande
|   ↓
| Enregistrement de la demande
|   ↓
| Confirmation de réception
|   ↓
| QR Code privé
|
| IMPORTANT :
| La commande n'est PAS automatiquement considérée comme payée.
| L'équipe Generation PUSH contacte le client avant validation.
|
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | FORMULAIRE DE COMMANDE
    |--------------------------------------------------------------------------
    |
    | Affiche le formulaire permettant au membre de choisir :
    | - version physique ou numérique lorsque disponible ;
    | - quantité ;
    | - note éventuelle.
    |
    */

    Route::get(
        '/boutique/commande/{product:slug}',
        [OrderController::class, 'create']
    )->name('front.shop.order.create');


    /*
    |--------------------------------------------------------------------------
    | ENREGISTRER LA DEMANDE DE COMMANDE
    |--------------------------------------------------------------------------
    |
    | La commande est créée avec le statut "pending".
    |
    | Aucun téléchargement numérique n'est accordé à ce stade.
    | L'équipe doit d'abord traiter la commande manuellement.
    |
    */

    Route::post(
        '/boutique/commande/{product:slug}',
        [OrderController::class, 'store']
    )->name('front.shop.order.store');


    /*
    |--------------------------------------------------------------------------
    | CONFIRMATION DE RÉCEPTION
    |--------------------------------------------------------------------------
    |
    | Page affichée après l'enregistrement de la commande.
    |
    | Elle présente notamment :
    | - le numéro de commande ;
    | - le récapitulatif ;
    | - les coordonnées utilisées ;
    | - le message indiquant que l'équipe va contacter le client ;
    | - le QR Code de la commande.
    |
    */

    Route::get(
        '/boutique/commande/confirmation/{order}',
        [OrderController::class, 'success']
    )->name('front.shop.order.success');


    /*
    |--------------------------------------------------------------------------
    | AFFICHER LE QR CODE
    |--------------------------------------------------------------------------
    |
    | Le QR est stocké dans :
    |
    | storage/app/private/orders/qr/
    |
    | Il n'est donc PAS accessible directement depuis Internet.
    |
    | Le contrôleur vérifie que l'utilisateur connecté est bien
    | propriétaire de la commande.
    |
    */

    Route::get(
        '/boutique/commande/{order}/qr',
        [QrOrderController::class, 'show']
    )->name('front.shop.order.qr');


    /*
    |--------------------------------------------------------------------------
    | TÉLÉCHARGER LE QR CODE
    |--------------------------------------------------------------------------
    |
    | Même protection que pour l'affichage.
    |
    */

    Route::get(
        '/boutique/commande/{order}/qr/download',
        [QrOrderController::class, 'download']
    )->name('front.shop.order.qr.download');

});
