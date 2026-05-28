<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Http\Requests\StoreStockTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $type = $request->input('type');

        $transactions = StockTransaction::with(['product', 'user'])
            ->when(in_array($type, ['IN', 'OUT']), function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('transactions.index', compact('transactions', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $products = Product::orderBy('name')->get();

        return view('transactions.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockTransactionRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                // Eager row-locking to guard against race conditions
                $product = Product::lockForUpdate()->findOrFail($request->product_id);

                $quantity = (int) $request->quantity;

                // Validate stock will not fall below zero
                if ($request->type === 'OUT' && $product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => "Insufficient stock. Current available stock: {$product->stock}.",
                    ]);
                }

                // Adjust product stock level
                if ($request->type === 'IN') {
                    $product->increment('stock', $quantity);
                } else {
                    $product->decrement('stock', $quantity);
                }

                // Insert the stock transaction log
                StockTransaction::create([
                    'product_id' => $request->product_id,
                    'user_id' => $request->user()->id,
                    'type' => $request->type,
                    'quantity' => $quantity,
                    'note' => $request->note,
                ]);

                // Dispatch database alert if stock drops to or below the minimum limit
                if ($product->stock <= $product->minimum_stock) {
                    $managers = \App\Models\User::role(['Admin', 'Manager Gudang'])->get();
                    \Illuminate\Support\Facades\Notification::send($managers, new \App\Notifications\LowStockNotification($product));
                }
            });

            return redirect()->route('transactions.index')
                ->with('success', 'Stock transaction recorded successfully.');

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'error' => 'An error occurred while processing the transaction: ' . $e->getMessage()
            ]);
        }
    }
}
