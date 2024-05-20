<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoleUser;
use App\Models\UserDetail;
use App\Models\LeadProgram;
use App\Models\PackageUser;
use App\Models\ClientGroup;
use App\Models\ClientGroupMapping;
use App\Models\ClientGroupUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;
use stdClass;
use Illuminate\Support\Facades\URL;
use App\Notifications\NewAccountCreated;
use DB;
use Illuminate\Support\Facades\Log;

class RazorpayController extends Controller
{
	protected $api;

	public function __construct()
	{
		$this->api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
	}

	public function order(Request $request)
	{
		$request->validate([
			'amount' => 'required',
			'currency' => 'required',
			'id' => 'sometimes'
		]);

		if ($request->id) {

			$packageUser =  PackageUser::find($request->id);

			if ($packageUser) {
				$response = $this->api->order->create($request->only(['amount', 'currency']));

				if ($response->id) {
					$packageUser->meta = ['order_id' => $response->id];
					$packageUser->save();
				}

				return response()->json([
					'success' => true,
					'order' => $response->toArray()
				]);
			}
			return response()->json([
				'success' => false,
				'mesage' => 'Please provide valid details'
			], 400);
		} else {
			$response = $this->api->order->create($request->only(['amount', 'currency']));
			return response()->json([
				'success' => true,
				'order' => $response->toArray()
			]);
		}
	}

	public function payment(Request $request)
	{
		$packageUser =  PackageUser::find($request->id);

		if ($packageUser) {

			$generated_signature = hash_hmac('sha256', $packageUser->meta['order_id'] . "|" . $request->razorpay_payment_id, config('services.razorpay.secret'));

			if ($generated_signature == $request->razorpay_signature) {
				$packageUser->transaction_id = $request->razorpay_payment_id;
				$packageUser->transaction_date = date('Y-m-d');
				if(empty($packageUser->joined_date)){
					$packageUser->joined_date = date('Y-m-d');
				}
				$packageUser->enrolled = true;
				$packageUser->cost = $request->cost;
				$packageUser->save();
				return response()->json([
					'success' => true,
					'order' => 'payment processed successfull'
				]);
			}
			return response()->json([
				'success' => false,
				'mesage' => 'Payment request from invalid source'
			], 500);
		}
	}

	public function leadorder(Request $request)
	{
		$request->validate([
			'amount' => 'required',
			'currency' => 'required',
			'id' => 'sometimes'
		]);

		if ($request->id) {			

	        if (base64_decode($request->id, true) !== false) {
	            $request->id = base64_decode($request->id);
	        }

			if ($request->for_external) {
		        $groupProgram = ClientGroup::find($request->id);
				if (!$groupProgram) {
					return response()->json([
						'success' => false,
						'mesage' => 'Please provide valid details'
					], 400);
				}
			} else {
				$LeadProgram =  LeadProgram::find($request->id);
				if (!$LeadProgram) {
					return response()->json([
						'success' => false,
						'mesage' => 'Please provide valid details'
					], 400);
				}
		    }

			$response = $this->api->order->create($request->only(['amount', 'currency']));
			if ($response->id && isset($LeadProgram)) {
				$LeadProgram->meta = ['order_id' => $response->id];
				$LeadProgram->save();
			}

			if ($response->id && isset($groupProgram)) {
				$groupProgram->meta = ['order_id' => $response->id];
				$groupProgram->save();
			}

			return response()->json([
				'success' => true,
				'order' => $response->toArray()
			]);

		} else {
			$response = $this->api->order->create($request->only(['amount', 'currency']));
			return response()->json([
				'success' => true,
				'order' => $response->toArray()
			]);
		}
	}

	public function consultantorder(Request $request)
	{
		$request->validate([
			'amount' => 'required',
			'currency' => 'required',
			'id' => 'sometimes'
		]);

		if ($request->id) {
			$user_detail = UserDetail::where('user_id', $request->id)->first();
			if ($user_detail) {
				$response = $this->api->order->create($request->only(['amount', 'currency']));
				if ($response->id) {
					$user_detail->meta = ['order_id' => $response->id];
					$user_detail->save();
				}
				return response()->json([
					'success' => true,
					'order' => $response->toArray()
				]);
			}
			return response()->json([
				'success' => false,
				'mesage' => 'Please provide valid details'
			], 400);
		} else {
			$response = $this->api->order->create($request->only(['amount', 'currency']));
			return response()->json([
				'success' => true,
				'order' => $response->toArray()
			]);
		}
	}

	public function consultantpayment(Request $request, $user_id)
	{  		
		try {
			$user_detail = UserDetail::where('user_id', $user_id)->first();
			$meta = json_decode($user_detail->meta);
			$generated_signature = hash_hmac('sha256', $meta->order_id . "|" . $request->razorpay_payment_id, config('services.razorpay.secret'));

			if ($generated_signature == $request->razorpay_signature) {
				\App\Models\UserDetail::where('user_id', $user_id)->update(['is_upgraded' => 1]);
	            return response()->json([
	                'success' => true,
	                'message' => 'Consultant access updated successfully'
	            ]);
	            exit();
			}

		}catch (\Exception $e) {
			DB::rollback();
			Log::debug('Error while processing payment request');
			Log::debug($e->getMessage());
			return response(['success' => false, 'message' => 'something went wrong, Please try after some time']);
		}
	}

	public function leadpayment(Request $request)
	{
		Log::debug('Lead Payment Initiator:' . $request->id);
		DB::beginTransaction();

        if (base64_decode($request->id, true) !== false) {
            $request->id = base64_decode($request->id);
        }
		
		if ($request->for_external) {
	        $groupProgram = ClientGroup::find($request->id);
			$meta = json_decode($groupProgram->meta);
			$generated_signature = hash_hmac('sha256', $meta->order_id . "|" . $request->razorpay_payment_id, config('services.razorpay.secret'));

			if ($generated_signature == $request->razorpay_signature) {
				return response()->json([
					'success' => true,
					'message' => 'payment processed successfully!'
				]);

				exit();
			}
			
			return response()->json([
				'success' => false,
				'message' => 'Payment request from invalid source'
			], 500);

			exit();
		}

		try {
			$LeadProgram =  LeadProgram::find($request->id);
			$lead = \App\Models\Lead::find($LeadProgram->lead_id);
			$program = \App\Models\Package::find($LeadProgram->program_id);
			$hc_lead = \App\Models\HealthcoachLead::where('lead_id', $lead->id)->first();

			if ($LeadProgram) {
				$meta = json_decode($LeadProgram->meta);
				$generated_signature = hash_hmac('sha256', $meta->order_id . "|" . $request->razorpay_payment_id, config('services.razorpay.secret'));
				if ($generated_signature == $request->razorpay_signature) {
					$user = new \App\Models\User;
					$user->first_name = $lead->name;
					$user->last_name = '';
					$user->name = $lead->name;
					$user->email = $lead->email;

					$user->role_id = 3;
					$user->phone = $lead->phone;
					$user->save();



					$role_user = new \App\Models\RoleUser;
					$role_user->user_id = $user->id;
					$role_user->role_id = 3;
					$role_user->save();

					$alian_client = new \App\Models\AlianClient;
					$alian_client->client_id = $user->id;
					$alian_client->aliance_id = $lead->alliancepartner_id;
					$alian_client->referred = '1';
					if (!empty($hc_lead)) {
						$alian_client->cis_id = $hc_lead->healthcoach_id;
					}
					$alian_client->save();

					$data[] = [
						'package_id' => $program->id,
						'user_id' => $user->id,
						'cost' => $LeadProgram->price,
						'data' => json_encode(array($LeadProgram->plan => $LeadProgram->price)),
						'transaction_id' => $request->razorpay_payment_id,
						'transaction_date' => date('Y-m-d'),
						'enrolled' => true,
						'joined_date' => date('Y-m-d')
					];
					PackageUser::insert($data);
					$resetUrl = URL::signedRoute('generate-password', ['user' => $user->id]);
					$user->notify(new NewAccountCreated($resetUrl));
					$data = array();
					$data['user_id'] = $user->id;
					$data['gender'] = $lead->gender;
					$data['dob'] = $lead->dob;
					\App\Models\UserDetail::create($data);

					$lead->delete();
					$LeadProgram->delete();

					$notification_to = array();
					$admin = \App\Models\User::where('role_id', 1)->first();
					$coordinator = \App\Models\CoordinatorAlliancepartner::where('alliancepartner_id', $lead->alliancepartner_id)->first();
					$marketinghead = \App\Models\MarketingheadAlliancepartner::where('alliancepartner_id', $lead->alliancepartner_id)->first();
					$marketingexecutive = \App\Models\MarketingexecutiveAlliancepartner::where('alliancepartner_id', $lead->alliancepartner_id)->first();
					if (!empty($hc_lead)) {
						$notification_to[] = $hc_lead->healthcoach_id;
					}
					if (!empty($admin)) {
						$notification_to[] = $admin->id;
					}
					if (!empty($coordinator)) {
						$notification_to[] = $coordinator->coordinator_id;
					}
					if (!empty($marketinghead)) {
						$notification_to[] = $marketinghead->mh_id;
					}
					if (!empty($marketingexecutive)) {
						$notification_to[] = $marketingexecutive->me_id;
					}
					foreach ($notification_to as $notification_to) {
						\App\Models\Notification::create([
							'user_id' => $notification_to,
							'message' => "New client enrolled - " . $lead->name,
							'meta' => [
								'client_id' => $user->id
							]
						]);
					}
					DB::commit();
					Log::debug('Lead Payment Completed');
					return response()->json([
						'success' => true,
						'message' => 'payment processed successfully!'
					]);
				}
				return response()->json([
					'success' => false,
					'message' => 'Payment request from invalid source'
				], 500);
			}
		} catch (\Exception $e) {
			DB::rollback();
			dd($e);
			Log::debug('Error while processing payment request');
			Log::debug($e->getMessage());
			return response(['success' => false, 'message' => 'something went wrong, Please try after some time']);
		}
	}
}
