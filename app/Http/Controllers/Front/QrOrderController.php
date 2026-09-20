<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class QrOrderController extends Controller
{
    /**
     * Afficher le QR Code d'une commande.
     */
    public function show(Order $order): Response
    {
        $this->authorizeOrder($order);

        if (!$order->qr_code_path) {
            abort(404, 'QR Code introuvable pour cette commande.');
        }

        if (!Storage::disk('private')->exists($order->qr_code_path)) {
            abort(404, 'Fichier QR Code introuvable.');
        }

        $qrCode = Storage::disk('private')->get($order->qr_code_path);

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'private, no-store, max-age=0');
    }

    /**
     * Télécharger le QR Code d'une commande.
     */
    public function download(Order $order): Response
    {
        $this->authorizeOrder($order);

        if (!$order->qr_code_path) {
            abort(404, 'QR Code introuvable pour cette commande.');
        }

        if (!Storage::disk('private')->exists($order->qr_code_path)) {
            abort(404, 'Fichier QR Code introuvable.');
        }

        $qrCode = Storage::disk('private')->get($order->qr_code_path);

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header(
                'Content-Disposition',
                'attachment; filename="' . $order->order_number . '-QR.svg"'
            )
            ->header('Cache-Control', 'private, no-store, max-age=0');
    }

    /**
     * Vérifie que l'utilisateur connecté est bien
     * propriétaire de la commande.
     */
    private function authorizeOrder(Order $order): void
    {
        abort_unless(
            auth()->check(),
            401,
            'Vous devez être connecté pour accéder à ce QR Code.'
        );

        abort_unless(
            $order->user_id === auth()->id(),
            403,
            'Vous n\'êtes pas autorisé à accéder à cette commande.'
        );
    }
}
