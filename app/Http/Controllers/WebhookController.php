<?php

namespace App\Http\Controllers;

use App\Models\Webhook;
use Illuminate\Http\Request;
use auth;

class WebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('webhook.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store = \Auth::user()->current_store;
        $validator = \Validator::make(
            $request->all(),
            [
                'module' => 'required|unique:webhooks,module,NULL,id,store_id,' . $store,
                'method' => 'required',
                'webbbook_url' => 'required|',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $webhook = new webhook();
        $webhook->module = $request->module;
        $webhook->method = $request->method;
        $webhook->url = $request->webbbook_url;
        $webhook->store_id = $store;
        $webhook->save();

        return redirect()->back()->with('success' , __('Webhook setting created successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function show(webhook $webhook)
    {
        //

        return view('users.setting',compact('webhooks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $webhook = Webhook::find($id);

        $store = \Auth::user()->current_store;
        $webhook = webhook::where('id', $id)->where('store_id', $store)->get();
        return view('webhook.edit', compact('webhook'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $store = \Auth::user()->current_store;
        // dd($request->all());
                $webhook['module']       = $request->module;
                $webhook['method']      = $request->method;
                $webhook['url']       = $request->webbbook_url;
                $webhook['store_id'] = $store;
                webhook::where('id', $id)->update($webhook);

                return redirect()->back()->with('success', __('Webhook Setting Succssfully Updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Webhook  $webhook
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $webhook = webhook::find($id);
        if ($webhook) {

                $webhook->delete();

            return redirect()->back()->with('success', __('Webhook Setting successfully deleted .'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }

    }
}
