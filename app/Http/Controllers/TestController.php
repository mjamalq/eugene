<?php
namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Test;
use App\Transformers\TestsTransformer;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __construct(
        protected TestsTransformer $testsTransformer
    ){}

    public function index()
    {
        $tests = Test::query()->orderBy('updated_at', 'desc')
            ->paginate(100)
            ->through(fn(Test $test) => $this->testsTransformer->transform($test));

        return view('tests.index', compact('tests'));
    }

    public function create()
    {
        $doctors = Doctor::all();

        return view('tests.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => '',
            'referring_doctor_id' => 'exists:doctors,id',
        ]);

        Test::create($validatedData);

        return redirect()->route('tests.index')->with('success', 'Test created successfully.');
    }
}
