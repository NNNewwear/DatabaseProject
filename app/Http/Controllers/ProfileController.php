<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;


class ProfileController extends Controller{

    public function editImage()
    {
    $user = \Illuminate\Support\Facades\Auth::user();
    return view('profile.image', compact('user'));
    }

    public function updateImage(Request $request)
    {
    $user = \Illuminate\Support\Facades\Auth::user();

    $data = $request->validate([
        'profile_image' => ['required', \Illuminate\Validation\Rules\File::image()->max(5 * 1024)],
    ]);

    if ($request->hasFile('profile_image')) {
        $data['profile_image_path'] = $request->file('profile_image')->store('profile', 'public');
        $user->update($data);
    }

    return redirect()->route('profile.show')->with('success', 'Profile image updated.');
    }

    public function show(){
        $user = Auth::user();
        $cards = \App\Models\Card::where('user_id', $user->id)->get();

        return view('profile.show', compact('user', 'cards'));
    }


    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    // app/Http/Controllers/ProfileController.php (เฉพาะเมธอด update)
    public function update(Request $request){
    $user = Auth::user();

    // validate ให้ตรงกับฟอร์ม edit (firstname/lastname)
    $data = $request->validate([
        'firstname' => ['nullable', 'string', 'max:255'],
        'lastname'  => ['nullable', 'string', 'max:255'],
        'username'  => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
        'email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        'phone'     => ['nullable', 'string', 'max:50'],
        'address'   => ['nullable', 'string', 'max:500'],
        'password'  => ['nullable', 'confirmed', 'min:8'], // กรอกเมื่ออยากเปลี่ยนเท่านั้น
        // ไม่ต้องรับ profile_image ที่นี่แล้ว เพราะไปอยู่ที่ updateImage()
    ]);

    // ถ้าไม่ได้ส่งรหัสผ่านมา ไม่ต้องแตะฟิลด์นี้
    if ($request->filled('password')) {
        // ถ้าโมเดล User มี casts ['password' => 'hashed'] จะถูกแฮชให้อัตโนมัติ
        $data['password'] = $request->password;
    } else {
        unset($data['password']);
    }

    $user->update($data);

    return redirect()->route('profile.show')->with('success', 'บันทึกโปรไฟล์แล้ว');
    }


    // (ถ้าใช้ Breeze จะมี destroy อยู่แล้ว; คงไว้ได้)
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
