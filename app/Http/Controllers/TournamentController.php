<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Gets a list of tournaments, optionally filtered by gender and date
     *
     * @OA\Get(
     *     path="/api/v1/tournaments",
     *     tags={"Tournaments"},
     *     summary="Gets a list of tournaments",
     *     description="Returns a collection of tournaments, each with its associated participant. Can be filtered by gender and date.
     *     **Filtering:**
     *     * **gender:** Filters tournaments by participant's gender.
     *     * **date:** Filters tournaments by creation date.",
     *     @OA\Parameter(
     *         name="gender",
     *         in="query",
     *         description="Participant's gender",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Tournament creation date",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of tournaments",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Tournament")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $tournaments = Tournament::with('participant');

        if ($request->has('gender')) {
            $tournaments->whereHas('participant', function ($query) use ($request) {
                $query->where('gender', $request->gender);
            });
        }

        if ($request->has('date')) {
            $tournaments->whereDate('created_at', $request->date);
        }

        return response()->json($tournaments->get());
    }

    public static function save(string $gender, int $participant_id)
    {
        Tournament::create([
            'gender' => $gender,
            'participant_id' => $participant_id,
        ]);
    }
}
