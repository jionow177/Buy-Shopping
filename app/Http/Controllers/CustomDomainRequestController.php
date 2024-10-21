<?php

namespace App\Http\Controllers;

use App\Models\CustomDomainRequest;
use Illuminate\Http\Request;

class CustomDomainRequestController extends Controller
{
    public function index()
    {
        if (\Auth::user()->type == 'super admin')
        {
            $custom_domain_requests = CustomDomainRequest::all();

            return view ('custom_domain_request.index',compact('custom_domain_requests'));
        } else {
            return redirect()->back()->with('error',__('Permission Denied.'));
        }
    }

    public function updateRequestStatus($id, $response)
    {
        if(\Auth::user()->type == 'super admin')
        {
            $custom_domain_requests = CustomDomainRequest::find($id);

            if(!empty($custom_domain_requests))
            {
                if($response == 1)
                {
                    $custom_domain_requests->status = 1;
                    $custom_domain_requests->update();
                } else {
                    $custom_domain_requests->status = '2';
                    $custom_domain_requests->update();

                    return redirect()->back()->with('success', __('Request Rejected Successfully.'));
                }
                return redirect()->back()->with('success', __('Request Approved Successfully.'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function destroy($id)
    {
        if(\Auth::user()->type == 'super admin')
        {
            $custom_domain_requests = CustomDomainRequest::find($id);
            $custom_domain_requests->delete();

            return redirect()->route('custom_domain_request.index')->with('success', __('Request deleted successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
