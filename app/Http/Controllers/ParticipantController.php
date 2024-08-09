<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Http\Helpers\NumericHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ParticipantController extends Controller
{
    public function __construct(
        protected Participant $participant
    )
    {}

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

    public function clear(): JsonResponse
    {
        $this->participant->clear();

        return response()->json([
            'success' => true,
            'message' => 'All participants deleted',
        ], 200);
    }
}
