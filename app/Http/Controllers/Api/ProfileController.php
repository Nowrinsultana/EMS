<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json($request->user()->only([
            'id', 'name', 'email', 'phone_number', 'date_of_birth',
            'passport_number', 'staff_id', 'leave_dates',
        ]));
    }

    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $user->fresh()->only([
                'id', 'name', 'email', 'phone_number', 'date_of_birth',
                'passport_number', 'staff_id', 'leave_dates',
            ]),
        ]);
    }
}
