<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    

    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    // รับ product_id จากฟอร์ม
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,product_id'],
        ]);

        Wishlist::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $data['product_id']],
            ['wishlist_date' => now()]
        );

        return back()->with('success', 'เพิ่มลง Wishlist แล้ว');
    }

    // รับ product_id จากพารามิเตอร์ URL
    public function destroy($product_id)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();

        return back()->with('success', 'นำออกจาก Wishlist แล้ว');
    }
}

