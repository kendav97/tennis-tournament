<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Http\Helpers\NumericHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
* @OA\Info(
*             title="API Tennis tournament", 
*             version="1",
*             description="Manage participants and play the game"
* )
*
* @OA\Server(url="http://localhost:8000")
*/
class ParticipantController extends Controller
{
    public function __construct(
        protected Participant $participant
    )
    {}

    /**
     * Get all participants
     * @OA\Get(
     *     path="/api/v1/participants",
     *     tags={"Participants"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Participant")   
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1',
            'offset' => 'integer|min:0',
        ]);

        $perPage = $request->per_page ?? static::DEFAULT_PAGINATE;

        if ($request->has('page') && !$request->has('offset')) {
            $participants = $this->participant::paginate($perPage, ['*'], 'page', $request->page);
        } elseif ($request->has('offset') && !$request->has('page')) {
            $participants = $this->participant::skip($request->offset)->take($perPage)->get();
        } else {
            $participants = $this->participant::paginate($perPage);
        }

        return response()->json($participants);
    }

    /**
     * Create a participant
     *
     * @OA\Post(
     *     path="/api/v1/participants",
     *     tags={"Participants"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="skill", type="integer", format="int32", example=50),
     *             @OA\Property(property="strength", type="integer", format="int32", example=80),
     *             @OA\Property(property="speed", type="integer", format="int32", example=70),
     *             @OA\Property(property="reaction", type="integer", format="int32", example=90),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Participant created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Participant created successfully"),
     *             @OA\Property(property="participant", ref="#/components/schemas/Participant")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     )
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $min_max = implode(',', [$this->participant::MIN_SCORE, $this->participant::MAX_SCORE]);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'skill' => 'required|integer|between:'.$min_max,
            'strength' => 'required|integer|between:'.$min_max,
            'speed' => 'required|integer|between:'.$min_max,
            'reaction' => 'required|integer|between:'.$min_max,
        ]);

        $validatedData['is_defeated'] = $this->participant::IS_NOT_DEFEATED;

        $participant = $this->participant::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Participant created successfully',
            'participant' => $participant
        ], 201);
    }

    /**
     * Seed participants
     *
     * @OA\Post(
     *     path="/api/v1/participants/seed",
     *     tags={"Participants"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="quantity", type="integer", format="int32", example=64),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Participants seeded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="64 participants created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     )
     * )
     */
    public function seed(Request $request): JsonResponse
    {
        $accept_values = NumericHelper::generatePowersOfTwo($this->participant::SEED_NUMBER_OPTIONS);
        $validatedData = $request->validate([
            'quantity' => 'required|integer|in:'.implode(',', $accept_values),
        ]);

        $this->participant->seed($validatedData['quantity']);

        return response()->json([
            'success' => true,
            'message' => $validatedData['quantity'] . ' aleatory participants created successfully',
        ], 201);
    }

    /**
     * Clear all participants
     *
     * @OA\Post(
     *     path="/api/v1/participants/clear",
     *     tags={"Participants"},
     *     @OA\Response(
     *         response=200,
     *         description="All participants deleted"
     *     )
     * )
     */
    public function clear(): JsonResponse
    {
        $this->participant->clear();

        return response()->json([
            'success' => true,
            'message' => 'All participants deleted',
        ], 200);
    }
}
