<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CardController extends Controller
{

    /** แสดงรายการบัตร (ถ้ามีหน้าการ์ดแยก) */
    public function index()
    {
        $cards = Card::where('user_id', Auth::id())->latest()->get();
        return view('cards.index', compact('cards'));
    }

    /** ไม่ใช้ฟอร์มแยกแล้ว -> ตัด create() ทิ้งได้ หรือคงไว้แต่ไม่ทำอะไร */
    public function create()
    {
        abort(404);
    }

    /** บันทึกบัตรใหม่ (ใช้จากฟอร์มในหน้า profile) */
    public function store(Request $request)
    {
        // เอาช่องว่างออกก่อน validate (ผู้ใช้พิมพ์เป็นกลุ่มตัวเลขได้)
        $cardNo = preg_replace('/\s+/', '', (string) $request->input('card_no'));
        $request->merge(['card_no' => $cardNo]);

        $request->validate([
            'card_no' => [
                'required',
                // 12–19 ตัวเลข (ช่วงที่พบได้ในบัตรหลายประเภท)
                'regex:/^\d{12,19}$/',
                // ไม่ซ้ำภายใน user เดียวกัน
                Rule::unique('cards', 'card_no')->where(fn ($q) => $q->where('user_id', Auth::id())),
            ],
            'expire_date' => ['required', 'date'],
        ]);

        Card::create([
            'user_id'     => Auth::id(),
            'card_no'     => $request->card_no,
            'expire_date' => $request->expire_date,
        ]);

        // ฝังในหน้าโปรไฟล์ -> กลับไปที่โปรไฟล์
        return redirect()->route('profile.show')->with('success', 'Card added.');
    }

    public function show(Card $card)
    {
        abort(404);
    }

    public function edit(Card $card)
    {
        abort(404);
    }

    public function update(Request $request, Card $card)
    {
        abort(404);
    }

    /** ลบบัตร */
    public function destroy(Card $card)
    {
        abort_if($card->user_id !== Auth::id(), 403);

        $card->delete();

        // กลับหน้าก่อนหน้า (profile) จะยืดหยุ่นกว่า
        return back()->with('success', 'Card deleted.');
    }
}
