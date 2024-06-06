<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\User;
use Illuminate\support\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class EtalaseController extends Controller
{
    public function index() {
        if(!auth()->check() || auth()->user()->roles !== 'User'){
            abort(404);
        };

        $user = Auth()->user();

        $obats = Obat::all();
        $logsMap = [];

        foreach ($obats as $obat) {
            $logsMap[] = [
                'nama' => $obat->nama_obat,
                'stok' => $obat->stok,
                'price' => $obat->harga,
            ];
        }

        foreach ($logsMap as $log) {
            $data[] = $log;
        }

        return view('/user_menu/etalase',[
            'data' => $data,
        ]);
    }
}