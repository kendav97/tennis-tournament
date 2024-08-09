<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Http\Helpers\NumericHelper;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    const SEED_NUMBER_OPTIONS = 6;

    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1',
            'offset' => 'integer|min:0',
        ]);

        $perPage = $request->per_page ?? static::DEFAULT_PAGINATE;

        if ($request->has('page') && !$request->has('offset')) {
            $participants = Participant::paginate($perPage, ['*'], 'page', $request->page);
        } elseif ($request->has('offset') && !$request->has('page')) {
            $participants = Participant::skip($request->offset)->take($perPage)->get();
        } else {
            $participants = Participant::paginate($perPage);
        }

        return response()->json($participants);
    }

    public function create(Request $request)
    {
        $min_max = implode(',', [Participant::MIN_SCORE, Participant::MAX_SCORE]);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'hability' => 'required|integer|between:'.$min_max,
            'force' => 'required|integer|between:'.$min_max,
            'speed' => 'required|integer|between:'.$min_max,
            'reaction' => 'required|integer|between:'.$min_max,
        ]);

        $validatedData['is_defeated'] = Participant::IS_NOT_DEFEATED;

        $participant = Participant::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Participant created successfully',
            'participant' => $participant
        ], 201);
    }

    public function seed($quantity)
    {
        $participant = new Participant();

        $participant->autoAdd($quantity);
    }

    public function requestSeed(Request $request)
    {
        $accept_values = NumericHelper::generatePowersOfTwo(self::SEED_NUMBER_OPTIONS);
        $validatedData = $request->validate([
            'quantity' => 'required|integer|in:'.implode(',', $accept_values),
        ]);

        $this->seed($validatedData['quantity']);

        return response()->json([
            'success' => true,
            'message' => $validatedData['quantity'] . ' aleatory participants created successfully',
        ], 201);
    }

    public function clear()
    {
        $participants = Participant::all();

        foreach($participants as $participant) {
            $participant->delete();
        }
    }
}
