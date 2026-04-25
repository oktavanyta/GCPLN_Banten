<?php

namespace App\Http\Controllers;
use App\Models\Groundcheck;
use App\Models\Upi;
use App\Models\Ulp;
use Illuminate\Http\Request;

class GroundcheckController extends Controller
{

    public function index(Request $request)
    {

        // Set default tanggal to today if not set
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $query = Groundcheck::with('ulp.up3.upi');

        // FILTER TANGGAL
        if ($tanggal) {
            $query->whereDate('groundchecks.created_at', $tanggal);
        }

        // FILTER JENIS
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        // FILTER UPI
        if ($request->upi_id) {
            $query->whereHas('ulp.up3.upi', function ($q) use ($request) {
                $q->where('id', $request->upi_id);
            });
        }

        // FILTER UP3
        if ($request->up3_id) {
            $query->whereHas('ulp.up3', function ($q) use ($request) {
                $q->where('id', $request->up3_id);
            });
        }

        // FILTER ULP
        if ($request->ulp_id) {
            $query->where('ulp_id', $request->ulp_id);
        }

        $data = $query
            ->join('ulps', 'groundchecks.ulp_id', '=', 'ulps.id')
            ->orderBy('groundchecks.updated_at')
            ->orderBy('ulps.kode')
            ->select('groundchecks.*')
            ->paginate(12)
            ->withQueryString();

        $upis = Upi::all();

        return view('groundcheck.index', compact('data', 'upis'));
    }

    public function create()
    {
        $ulps = Ulp::with('up3.upi')->get();
        
        return view('groundcheck.create', compact('ulps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'created_at' => 'required',
            'ulp_id' => 'required|array',
        ]);

        foreach ($request->ulp_id as $i => $ulp_id) {

            $open = isset($request->open[$i]) && $request->open[$i] !== '' ? $request->open[$i] : 0;
            $submitted = isset($request->submitted[$i]) && $request->submitted[$i] !== '' ? $request->submitted[$i] : 0;
            $rejected = isset($request->rejected[$i]) && $request->rejected[$i] !== '' ? $request->rejected[$i] : 0;

            Groundcheck::create([
                'jenis' => $request->jenis,
                'ulp_id' => $ulp_id,
                'open' => $open,
                'submitted' => $submitted,
                'rejected' => $rejected,
                'created_at' => $request->created_at,
            ]);
        }

        return redirect()->route('groundcheck.index')
            ->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        $data = Groundcheck::findOrFail($id);
        return view('groundcheck.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Groundcheck::findOrFail($id);

        $data->update($request->all());

        return redirect()->route('groundcheck.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Groundcheck::destroy($id);

        return back()->with('success', 'Data dihapus');
    }
}