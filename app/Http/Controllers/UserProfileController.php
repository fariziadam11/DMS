<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersProfile;
use App\Models\MasterDivisi;
use App\Models\MasterDepartment;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect()->route('profile.edit');
    }

    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;

        if (!$profile) {
            // Create profile if not exists (should be created by observer/controller, but for safety)
            $profile = UsersProfile::create([
                'id_user' => $user->id,
                'email' => $user->email,
                'first_name' => explode(' ', $user->name)[0],
                // Other fields default null
            ]);
        }

        // We might need master data for dropdowns if applicable,
        // strictly speaking text fields are requested but some like religion/blood_type could be enums/selects.

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:25',
            'nip' => 'nullable|string|max:20', // Should be read-only if synced from User?
            'ktp' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'gender' => 'nullable|in:1,2', // 1: Male, 2: Female
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:25',
            'country' => 'nullable|string|max:25',
            'poscode' => 'nullable|string|max:12',
            'employee_status' => 'nullable|in:Permanent,Contract,Internship',
            'join_date' => 'nullable|date',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:25',
            'blood_type' => 'nullable|in:A,B,AB,O',
            'religion' => 'nullable|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'education_level' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:2048', // 2MB
            'signature_file' => 'nullable|image|max:2048',
            'facebook' => 'nullable|string|max:125',
            'twitter' => 'nullable|string|max:125',
            'instagram' => 'nullable|string|max:125',
            'linkedin' => 'nullable|string|max:125', // website column
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($profile->photo) {
                Storage::delete('public/profiles/' . $profile->photo);
            }

            $filename = time() . '_photo_' . $user->id . '.' . $request->photo->extension();
            $request->photo->storeAs('public/profiles', $filename);
            $validated['photo'] = $filename;
        }

        if ($request->hasFile('signature_file')) {
             if ($profile->signature_file) {
                Storage::delete('public/signatures/' . $profile->signature_file);
            }

            $filename = time() . '_sign_' . $user->id . '.' . $request->signature_file->extension();
            $request->signature_file->storeAs('public/signatures', $filename);
            $validated['signature_file'] = $filename;
        }

        // Website field mapping
        if(isset($validated['linkedin'])) {
            $validated['website'] = $validated['linkedin'];
            unset($validated['linkedin']);
        }

        $profile->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
