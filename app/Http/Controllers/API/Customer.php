<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\api\v1\Controller;
use Illuminate\Support\Facades\Validator;
use App\Model\CustomerModel;
use App\Model\OrderModel;
use App\Model\BookingModel;
use Image;
use File;

class Customer extends Controller
{
    /**
     * Returns list of Customer. 
     * Request object may have optional query string parameters 
     * @Queryparam  $q search in name, email, phone
     * @Queryparam  $perPage number of items per page. set false to all recourds. Default false
     *
     * @param Request $request
     * @return Response
     */

    public function index(Request $request)
    {
        $where = ['is_deleted' => 0];
        $objData = CustomerModel::where($where);
        if ($q = $request->get('q')) {
            $objData->where(function ($query)  use ($q) {
                $query->where('first_name', 'like', "%$q%");
                $query->orWhere('last_name', 'like', "%$q%");
                $query->orWhere('email', 'like', "%$q%");
                $query->orWhere('phone', 'like', "%$q%");
            });
        }
        $perPage = $request->get('perPage');
        $perPage = (!empty($perPage) && $perPage != 'all') ? (int) $perPage : 0;
        if ($perPage) {
            $objData = $objData->paginate($perPage)->appends(request()->query());
        } else {
            $objData = $objData->get();
        }
        $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $objData
        ];
        return response()->json($response, 200);
    }

    /**
     * Returns Customer Details. 
     *
     * @param Int $id customer id
     * @return Response
     */
    public function single($id)
    {
        $objData = CustomerModel::with('country', 'state', 'city')->findOrFail($id);
        //   $objData = $objData->append('attachments_url','front_image_url','back_image_url')->toArray();
        $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $objData
        ];
        return response()->json($response, 200);
    }

    /**
     * Save Customer. 
     *
     * @return Response
     */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', 'string', 'email'],
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'validation-error',
                'errors' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $customer = CustomerModel::where('email', $request->email)->first();
        if (!$customer) {
            $customer = new CustomerModel;
            $customer->email = $request->email;
        }
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->phone = $request->phone;
        $customer->zip_code = $request->zip_code;
        $customer->country_id = $request->country_id;
        $customer->state_id = $request->state_id;
        $customer->city_id = $request->city_id;
        $customer->address = $request->address;
        $customer->status = 0;
        $customer->card_no = $request->card_no;
        $customer->card_name = $request->card_name;
        $customer->card_cvc = $request->card_cvc;
        $customer->card_expiry_date = $request->card_expiry_date;
        $customer->is_deleted = 0;

        if ($customer->save()) {
            $response = [
                'status' => true,
                'message' => 'Customer successfully saved',
                'data' => $customer
            ];
            return response()->json($response, 200);
        }

        return $this->response_fail();
    }


    /**
     * Customers Search by email, First 15 records for Autocomplete
     * Request object may have optional query string parameters 
     * @Queryparam  $q search in email
     *
     * @param Request $request
     * @return Response
     */

    public function emailAutocomplete(Request $request)
    {

        $where = ['is_deleted' => 0];
        $objData = CustomerModel::where($where)->orderBy('email', 'asc');;
        if ($q = $request->get('q')) {
            $objData->where('email', 'like', "%$q%");
        }
        $objData = $objData->take(15)->pluck('email', '_id');
        $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $objData
        ];
        return response()->json($response, 200);
    }

     /**
     * Returns list of booking. 
     * Request object may have optional query string parameters 
     * @Queryparam  $q search in booking id, asset name, customer name, location
     * @Queryparam  $perPage number of items per page. set false to all recourds. Default false
     *
     * @param Request $request
     * @return Response
     */
    public function booking(Request $request, $id)
    {
        $where = ['client_id' => $request->apiClient->_id, 'customer_id' => $id];
        $objData = BookingModel::with('asset.image')->where($where)->whereNotNull('asset_data');
        if ($q = $request->get('q')) {
            $objData->where(function ($query)  use ($q) {
                $query->where('booking_id', 'like', "%{$q}%");
                $query->orWhere('asset_data.name', 'like', "%{$q}%");
                $query->orWhere('customer_data.first_name', 'like', "%{$q}%");
                $query->orWhere('customer_data.last_name', 'like', "%{$q}%");
                $query->orWhere('drop_location_data.name', 'like', "%{$q}%");
                $query->orWhere('net_amount', 'like', "%{$q}%");
            });
        }
        $perPage = $request->get('perPage');
        $perPage = (!empty($perPage) && $perPage != 'all') ? (int) $perPage : 0;
        if ($perPage) {
            $objData = $objData->paginate($perPage)->appends(request()->query());
        } else {
            $objData = $objData->get();
        }
        $response = [
            'status' => true,
            'message' => 'Success',
            'data' => $objData
        ];
        return response()->json($response, 200);
    }
    public function details($id)
    {
        $customer = CustomerModel::with('country', 'state', 'city')->where(['is_deleted' => 0])->findOrFail($id);
        $customer = $customer->append('attachments_thumbnail_url', 'front_thumbnail_url', 'back_thumbnail_url')->toArray();
        return $this->response_data($customer, '');
    }
    public function salesHistory($id)
    {
        $sales = OrderModel::where(['order_type' => 'sale', 'customer_id' => $id])->latest();
        $sales = $sales->paginate(PAGINATE);
        return $this->response_data($sales, '');
    }
    public function rentalHistory($id)
    {
        $rantal = BookingModel::with('asset.image')->whereNotNull('asset_data')->latest();
        $rantal = $rantal->paginate(PAGINATE);
        return $this->response_data($rantal, '');
    }
}
