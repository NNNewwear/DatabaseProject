<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = Card::where('user_id', Auth::id())->get();
        return view('cards.index', compact('cards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $request->validate([
            'card_no' => 'required|string|max:20',
            'expire_date' => 'required|date',
        ]);

        Card::create([
            'user_id' => Auth::id(),
            'card_no' => $request->card_no,
            'expire_date' => $request->expire_date,
        ]);

        return redirect()->route('cards.index')->with('success', 'Card created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        if ($card->user_id != Auth::id()) abort(403);
        $card->delete();

        return redirect()->route('cards.index')->with('success', 'Card deleted.');
    }
}
