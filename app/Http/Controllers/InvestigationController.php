<?php

namespace App\Http\Controllers;

use App\Models\CoordinatorAlliancepartner;
use App\Models\Investigation;
use App\Models\Test;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use Log;
use DB;

class InvestigationController extends Controller
{

    public function index(Request $request)
    {
        $role = auth()->user()->role_id;
        $role_info = \App\Models\Role::find($role);
        $id =  $request->id ?? null;
        $alliance_partners = CoordinatorAlliancepartner::where('coordinator_id', auth()->user()->id)->pluck('alliancepartner_id');
        $alliance_clients = DB::table('alian_client')->whereIn('aliance_id', $alliance_partners)->pluck('client_id');

        $investigation =  Investigation::select('investigations.*', 'users.name as consultant', 'user_details.specialized_in', 'patients.name as patient', 'patients.phone', 'alliance_details.company_name as ap_company', 'dh_user.name as dp_name','sc_user.name as sample_collector')
            ->join('users', 'users.id', '=', 'investigations.user_id')
            ->leftJoin('user_details', 'investigations.user_id', '=', 'user_details.user_id')
            ->join('users as patients', 'patients.id', '=', 'investigations.client_id')
            ->leftJoin('alian_client', 'alian_client.client_id', '=', 'investigations.client_id')
            ->leftJoin('user_details as alliance_details', 'alliance_details.user_id', '=', 'alian_client.aliance_id')
            ->leftJoin('diagnostichead_alliance as dha', 'dha.ap_id', '=', 'alian_client.aliance_id')
            ->leftJoin('users as dh_user', 'dh_user.id', '=', 'dha.dh_id')
            ->leftJoin('sample_collectors_investigations as sc_inv', 'sc_inv.inv_id', '=', 'investigations.id')
            ->leftJoin('users as sc_user', 'sc_user.id', '=', 'sc_inv.sc_id')
            ->when($role === 3, function ($q) {
                $q->where('investigations.client_id', auth()->user()->id);
            })
            ->when($role > 3 && $role_info->is_consultant == 1, function ($q) {
                //$q->where('investigations.user_id', auth()->user()->id);
            })
            ->when($id, function ($q) use ($id) {
                $q->where('investigations.client_id', $id);
            })
            ->when($role === 10, function ($q) {
                // $alliance_partners = CoordinatorAlliancepartner::where('coordinator_id', auth()->user()->id)->pluck('alliancepartner_id');
                // $alliance_clients = DB::table('alian_client')->whereIn('aliance_id', $alliance_partners)->pluck('client_id');
                // $q->whereIn('investigations.client_id', $alliance_clients);
            })
            ->when($role === 17, function ($q) {
                // $dh_clients = DB::table('diagnostichead_clients')->whereIn('dh_id', auth()->user()->id)->pluck('client_id');
                $q->whereIn('investigations.payment_status', ['completed']);
            })
            ->when($role === 18, function ($q) {
                $q->whereIn('sc_inv.sc_id', [auth()->user()->id]);
            })
            ->where([
                'investigations.active' => 1,
            ])
            ->orderBy('investigations.id', 'desc');

        $i1 = clone $investigation;
        $investigations = $i1->limit($request->per_page)->offset(($request->page - 1) * $request->per_page)->get();
        foreach ($investigations as $key => $investigation) {
            $alian_client = DB::table('alian_client')->select('alliancepartner.name as alliance_partner')->join('users as alliancepartner', 'alliancepartner.id', '=', 'alian_client.aliance_id')->where('alian_client.client_id', $investigation->client_id)->first();
            $investigations[$key]->alliance_partner = $alian_client ? $alian_client->alliance_partner : '';
            $investigations[$key]->no = (!empty(explode('-', $investigation->no)[1])) ? explode('-', $investigation->no)[1] : $investigation->no;
        }

        return response()->json([
            'investigations' => $investigations,
            'page' => $request->page,
            'total' => $investigation->count(),
            'per_page' => $request->per_page,
        ]);
    }

    public function show(Request $request, int $id)
    {
        $role = auth()->user()->role_id;
        $is_consultant = \App\Models\Role::find(auth()->user()->role_id)->is_consultant;
        $is_client = $request->client_id;

        $investigation = Investigation::where('investigations.id', $id)
            ->select('investigations.*')
            ->when(($role === 3 || $role === 10 || $role === 11 || $role === 1 || $is_consultant == 1), function ($q) {
                $q->join('users', 'users.id', '=', 'investigations.user_id')
                    ->join('users as client', 'client.id', '=', 'investigations.client_id')
                    ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
                    ->leftJoin('user_details as client_details', 'client.id', '=', 'client_details.user_id')
                    ->addSelect(
                        'users.name as consultant',
                        'users.email as consultant_email',
                        'user_details.specialized_in as consultant_specialized_in',
                        'user_details.clinic_name as consultant_clinic',
                        'user_details.pincode as consultant_pincode',
                        DB::raw("concat(user_details.address,', ',user_details.city,'-',user_details.state,' ','India') as consultant_address"),
                        'client_details.gender as patient_gender',
                        'user_details.specialized_in',
                        'users.email',
                        'users.phone',
                        'user_details.specialization',
                        'client_details.dob',
                        'users.avatar',
                        'client.name as patient_name',
                        'user_details.logo',
                        'user_details.signature_img',
                        'client.email as patient_email',
                        'client.phone as patient_phone'
                    );
            })->first();

        $test_data = (!empty($investigation->test_data)) ? json_decode($investigation->test_data, TRUE) : [];
        if ($role === 3 || $role === 10 || $role === 11 || $role === 1 || $is_consultant == 1) {
            $tests = [];
            foreach ($investigation->test as $key => $test) {
                if (empty($test)) {
                    continue;
                }
                $tests = array_merge($tests, $test);
            }

            $advice = Test::whereIn('tests.id', $tests)
            ->select('tests.name', 'tests.id')
            ->when($role === 3 && $is_client, function($q) use($is_client) {
                $q
                ->leftJoin('alian_client', 'alian_client.client_id', '=', DB::raw($is_client))
                ->leftJoin('diagnostichead_alliance as dha', 'dha.ap_id', '=', 'alian_client.aliance_id')
                ->leftJoin('dh_investigation_tests', function($join) {
                    $join
                    ->on('dh_investigation_tests.user_id', '=', 'dha.dh_id')
                    ->on('dh_investigation_tests.sub_category_id', '=', 'tests.id');
                })
                ->addSelect('dh_investigation_tests.cost');
            })
            ->get();
            $advice_data = [];
            foreach ($advice as $advice) {
                $advice_data[] = array(
                    'id' => $advice->id,
                    'name' => $advice->name,
                    'cost' => $advice->cost ?? 0,
                    'unit' => (!empty($test_data[$advice->id]['unit'])) ? $test_data[$advice->id]['unit'] : '',
                    'result' => (!empty($test_data[$advice->id]['result'])) ? $test_data[$advice->id]['result'] : '',
                    'ref_range_min' => (!empty($test_data[$advice->id]['ref_range_min'])) ? $test_data[$advice->id]['ref_range_min'] : '',
                    'ref_range_max' => (!empty($test_data[$advice->id]['ref_range_max'])) ? $test_data[$advice->id]['ref_range_max'] : '',
                );
            }

            $investigation->tests = $advice_data;
            $investigation->no = (!empty(explode('-', $investigation->no)[1])) ? explode('-', $investigation->no)[1] : $investigation->no;
        }

        return response()->json([
            'investigation' => $investigation,
            'success' => true
        ]);
    }

    public function test()
    {
        $test = Test::where('tests.active', 1)->join('test_categories', 'test_categories.id', '=', 'tests.test_category_id')
            ->select('tests.name as text', 'tests.id as value', 'test_categories.name as category')->get();

        return response()->json([
            'success' => true,
            'test' => $test->groupBy('category')
        ]);
    }

    public function store(Request $request)
    {
        $investigation = new Investigation([
            'client_id' => $request->client,
            'user_id' => auth()->user()->id,
            'test' => $request->test
        ]);

        $investigation->save();

        $alian_client = DB::table('alian_client')->select('alliancepartner.name as alliance_partner')->join('users as alliancepartner', 'alliancepartner.id', '=', 'alian_client.aliance_id')->where('alian_client.client_id', $investigation->client_id)->first();
        $alliance_partner = str_replace(' ', '', $alian_client->alliance_partner);
        $investigation->no = $alliance_partner . '-' . ($investigation->id > 9 ? $investigation->id : "0{$investigation->id}");

        $investigation->save();

        return response()->json([
            'success' => true,
            'message' => 'new investigation created successfully'
        ]);
    }

    public function update(Request $request, int $id)
    {
        $investigation = Investigation::find($id);
        if ($investigation) {
            $investigation->update([
                'test' =>  $request->test
            ]);
            return response()->json([
                'success' => true,
                'message' => 'investigation updated successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'investigation not found in storage'
        ]);
    }

    public function destroy(int $id)
    {

        $investigation = Investigation::find($id);

        if ($investigation) {
            $investigation->update([
                'active' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'investigation deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'investigation not found in storage'
        ]);
    }
    public function storeInvestConfig(Request $request)
    {
        if (count($request->test_items) > 0 && $request->test_categories) {
            $testCategory = new TestCategory([
                'name' => $request->test_categories,
                'active' => 1
            ]);
            $testCategory->save();

            foreach ($request->test_items as $investigation_item) {
                Log::info($investigation_item['test_items']);
                Test::create([
                    'test_category_id' => $testCategory->id,
                    'name' => $investigation_item['test_items'],
                    'active' => 1
                ]);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'New Investigation items added'
        ]);
    }

    public function updateTestData(Request $request)
    {
        $data = [];
        foreach ($request->tests as $key => $test) {
            $data[$test['id']] = array(
                'id' => $test['id'],
                'name' => $test['name'],
                'unit' => $test['unit'],
                'result' => $test['result'],
                'ref_range_min' => $test['ref_range_min'],
                'ref_range_max' => $test['ref_range_max']
            );
            // Test::where('id', $test['id'])->update([
            //     'unit' => $test['unit'],
            //     'result' => $test['result'],
            //     'ref_range_min' => $test['ref_range_min'],
            //     'ref_range_max' => $test['ref_range_max'],
            // ]);
        }
        Investigation::where('id', $request->id)->update(['test_data' => $data]);
        return response()->json([
            'success' => true,
            'message' => 'Test info updated'
        ]);
    }




    public function upload(Request $request, int $id)
    {
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,docx,ods',
        ]);

        $investigation = Investigation::find($id);
        $file = $request->file('file');
        if ($file && $investigation) {
            $name = uniqid() . '__' . trim($file->getClientOriginalName());
            $path = $request->file('file')->storeAs('investigation/' . $investigation->no, $name);

            $files = $investigation->files ?? [];
            array_push($files, ['name' => $name, 'path' => $path]);
            $investigation->files = $files;
            $investigation->save();

            return response()->json([
                'success' => true,
                'message' => 'file uploaded successfully'
            ]);
        }
        return true;
    }

    public function scassignment(Request $request) {
        $request->validate([
            'sc_id' => 'required',
            'inv_id' => 'required'
        ]);

        $is_exists = DB::table('sample_collectors_investigations')->where([
            'inv_id' => (int) $request->input('inv_id')
        ])->exists();

        if ($is_exists) {
            return response()->json([
                'success' => false,
                'message' => 'Already Assigned!'
            ]);

            exit();
        }

        if (!$is_exists) {
            DB::table('sample_collectors_investigations')->insert([
                'sc_id' => (int) $request->input('sc_id'),
                'inv_id' => (int) $request->input('inv_id')
            ]);

            Investigation::where('id', $id)->update(['status' => 'assigned']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Assigned successfully'
        ]);
    }

    public function updatestatus(Request $request, $id) {
        $request->validate([
            'status' => 'required'
        ]);

        $status = $request->input('status');

        Investigation::where('id', $id)->update(['status' => $status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function removeFile(Request $request, int $id)
    {
        $investigation =  Investigation::find($id);
        $files = $investigation->files;
        foreach ($files as $k => $v) {
            if (explode('__', $v['name'])[1] === $request->input('file')) {
                unset($files[$k]);
            }
        }
        $investigation->files = $files;
        $investigation->save();
        return response()->json([
            'success' => true,
            'message' => 'file deleted successfully'
        ]);
    }
}
