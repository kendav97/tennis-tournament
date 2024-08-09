<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct(
        protected GameService $game
    )
    {}

    /**
     * Play a game
     *
     * @OA\Post(
     *     path="/api/v1/game/play",
     *     tags={"Game"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="gender", type="string", enum={"male", "female"}),
     *             @OA\Property(property="force", type="boolean", default=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Game played successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The winner is John Doe with id = 123"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function play(Request $request)
    {
        $validatedData = $request->validate([
            'gender' => 'required|in:'.implode(',', $this->game::GENDERS),
            'force' => 'boolean',
        ]);
    
        $gender = $validatedData['gender'];
        $force = $validatedData['force'] ?? false;
    
        $result = $this->game->play($gender, $force);

        if (is_numeric($result)) {
            if ($result == 0) {
                return response()->json([
                    'message' => 'There are no participants available in the database.',
                    'success' => false
                ], 200);
            }

            if ($result > 0) {
                return response()->json([
                    'message' => 'The number of participants is not a base 2 power, therefore there may be an unexpected result. Send the parameter "force" = true to run the game anyway.',
                    'success' => false
                ], 200);
            }
        }

        $winnerName = $result->name;
        $winnerId = $result->id;
        $winnerText = "The winner is $winnerName with id = $winnerId";

        return response()->json([
            'message' => $winnerText,
            'success' => true
        ], 200);
    }

    /**
     * Reset the game
     *
     * @OA\Post(
     *     path="/api/v1/game/reset",
     *     tags={"Game"},
     *     @OA\Response(
     *         response=200,
     *         description="Game reseted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Game reseted successfully"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     )
     * )
     */
    public function reset()
    {
        $this->game->reset();

        return response()->json([
            'message' => 'Game reseted successfully',
            'success' => true
        ], 200);
    }

    /**
     * Replay the game
     *
     * @OA\Post(
     *     path="/api/v1/game/replay",
     *     tags={"Game"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="gender", type="string", enum={"male", "female"}),
     *             @OA\Property(property="force", type="boolean", default=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Game replayed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The winner is John Doe with id = 123"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function replay(Request $request)
    {
        $this->reset();

        return $this->play($request);
    }
}
