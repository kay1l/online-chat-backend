<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Auth::user()->contacts()->select('id', 'name', 'avatar', 'is_online')->get();

        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:users,id|not_in:' . Auth::id(),
        ]);

        $user = Auth::user();

        if ($user->contacts()->where('contact_id', $request->contact_id)->exists()) {
            return response()->json(['message' => 'Contact already exists'], 409);
        }

        $user->contacts()->attach($request->contact_id);
        return response()->json(['message' => 'Contact added']);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user->contacts()->where('contact_id', $id)->exists()) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $user->contacts()->detach($id);
        return response()->json(['message' => 'Contact removed']);
    }
}
