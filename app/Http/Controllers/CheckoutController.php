<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index() {
        $cartItems = Auth::user()->cartItems;
        $total = 0;
        foreach ($cartItems as $cartItem) {
            $total += $cartItem->harga * $cartItem->pivot->quantity;
        }
        return view('user_menu/checkout', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function addQuantity(Request $request) {
        try {
            $user = User::find(Auth::id());
            $cartItem = $user->cartItems()->where('obat_id', $request->obat_id)->first();
            $cartItem->pivot->quantity += 1;
            $cartItem->pivot->save();
            return response()->json(['quantity' => $cartItem->pivot->quantity, 'total' => $this->calculateTotal($user->cartItems)]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    public function reduceQuantity(Request $request) {
        try {
            $user = User::find(Auth::id());
            $cartItem = $user->cartItems()->where('obat_id', $request->obat_id)->first();
            if ($cartItem->pivot->quantity > 1) {
                $cartItem->pivot->quantity -= 1;
                $cartItem->pivot->save();
                return response()->json(['quantity' => $cartItem->pivot->quantity, 'total' => $this->calculateTotal($user->cartItems)]);
            } else {
                $user->cartItems()->detach($request->obat_id);
                return response()->json(['deleted' => true, 'total' => $this->calculateTotal($user->cartItems)]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function calculateTotal($cartItems) {
        $total = 0;
        foreach ($cartItems as $cartItem) {
            $total += $cartItem->harga * $cartItem->pivot->quantity;
        }
        return $total;
    }

    public function createTransaction(Request $request) {
        $user = User::find(Auth::id());
        $checkedItems = $request->checked_items;
        $cartItems = $user->cartItems()->whereIn('obat_id', $checkedItems)->get();
        $total = $this->calculateTotal($cartItems);
    
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'No items selected for transaction'], 400);
        }
    
        try {
            DB::transaction(function () use ($user, $cartItems, $total, $checkedItems) {
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'tanggal' => now(),
                    'total_harga' => $total
                ]);
    
                foreach ($cartItems as $cartItem) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'obat_id' => $cartItem->id,
                        'jumlah' => $cartItem->pivot->quantity,
                        'harga' => $cartItem->harga,
                        'diskon' => 0
                    ]);
                }
    
                $user->cartItems()->detach($checkedItems);
            });
    
            return response()->json(['message' => 'Transaction completed successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
}
