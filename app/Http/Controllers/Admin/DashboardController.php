<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // TODO: remplacer par de vraies requêtes Eloquent une fois les modèles créés
        $kpis = [
            ['label' => 'Utilisateurs', 'value' => '2 480', 'trend' => '+12.4%', 'up' => true, 'icon' => 'users'],
            ['label' => 'Membres actifs', 'value' => '1 932', 'trend' => '+8.1%', 'up' => true, 'icon' => 'users'],
            ['label' => 'Formations', 'value' => '46', 'trend' => '+3', 'up' => true, 'icon' => 'book-open'],
            ['label' => 'Conférences', 'value' => '12', 'trend' => '-1', 'up' => false, 'icon' => 'calendar'],
            ['label' => 'Revenus (mois)', 'value' => '4 250 000 FCFA', 'trend' => '+15.2%', 'up' => true, 'icon' => 'dollar-sign'],
            ['label' => 'Transactions', 'value' => '318', 'trend' => '+22', 'up' => true, 'icon' => 'file-text'],
        ];

        return view('admin.dashboard.index', compact('kpis'));
    }
}
