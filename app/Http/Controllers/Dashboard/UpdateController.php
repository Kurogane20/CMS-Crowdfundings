<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Update;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;


class UpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $title = trans('app.campaign_updates');
        $campaign_id = $id;
        $updates = Update::whereCampaignId($id)->get();
        return view('dashboard.updates.index', compact('title', 'updates', 'campaign_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'image.image' => 'Only .jpg, .jpeg and .png is allowed extension',
        ]);

        $data = $request->except('_token');
        $data['user_id'] = $request->user()->id;
        $data['campaign_id'] = $id;

        $image_name = '';
        if ($image = $request->file('image')) {
            $valid_extensions = ['jpg', 'jpeg', 'png'];
            $extension = strtolower($image->getClientOriginalExtension());
            if (!in_array($extension, $valid_extensions)) {
                return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension');
            }

            $upload_dir = './storage/uploads/updates/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_base_name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $image_name = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.'.$extension;
            $imageFileName = $upload_dir.$image_name;

            Image::make($image)->resize(900, 400)->save($imageFileName);
        }

        $data['image'] = $image_name;

        if (Update::create($data)) {
            return back()->with('success', trans('app.update_created'));
        }

        return back()->with('error', trans('app.something_went_wrong'))->withInput($request->input());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function show(Update $update)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function edit($campaign_id, $udpate_id)
    {
        $user_id = request()->user()->id;
        $title = trans('app.edit_reward');
        $update = Update::find($udpate_id);
        $campaign = Campaign::findOrFail($campaign_id);
        if ($campaign_id != $update->campaign_id || $user_id != $update->user_id){
            die(trans('app.unauthorised_access'));
        }
        return view('dashboard.updates.edit', compact('title', 'campaign', 'update'));
    }

    /**
     * Update the specified resource in storage.
     *
     * This function handles the update of a campaign update. It first validates the request data
     * based on the defined rules. Then it extracts the data from the request except the `_token`
     * field. If the request has a file with the name `image`, it processes the image and updates
     * the `image` field in the data. Finally, it updates the update in the database using the
     * `update` method provided by the Eloquent ORM.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $campaign_id, $update_id)
    {
        // Define the validation rules for the request data
        $rules = [
            'title'       => 'required',     // The title field is required
            'description' => 'required',     // The description field is required
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',    // The image field is optional, must be an image file, and the file format must be jpeg, png, jpg, or gif. The file size must not exceed 2048 kilobytes
        ];

        // Validate the request data against the defined rules
        $request->validate($rules);

        // Extract the data from the request except the `_token` field
        $data = $request->except('_token');

        // Process the image if present
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $valid_extensions = ['jpg', 'jpeg', 'png'];
            $extension = strtolower($image->getClientOriginalExtension());
            if (!in_array($extension, $valid_extensions)) {
                return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension');
            }

            $upload_dir = './storage/uploads/updates/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $image_name = strtolower(time().str_random(5).'-'.str_slug($image->getClientOriginalName())).'.'.$extension;
            $imageFileName = $upload_dir.$image_name;

            Image::make($image)->resize(900, 400)->save($imageFileName);

            $data['image'] = $image_name;
        }

        // Update the update in the database
        if (Update::where('id', $update_id)
            ->where('user_id', $request->user()->id)
            ->where('campaign_id', $campaign_id)
            ->update($data)
        ) {
            // If the update is successfully updated, redirect the user to the edit campaign updates page with a success message
            return redirect(route('edit_campaign_updates', $campaign_id))->with('success', trans('app.update_updated'));
        }

        // If the update fails to update, return a redirect back to the previous page with an error message and the input data
        return back()->with('error', trans('app.something_went_wrong'))->withInput($request->input());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Update  $update
     * @return \Illuminate\Http\Response
     */
    public function destroy(Update $update, Request $request)
    {
        $user_id = request()->user()->id;
        $data_id = $request->data_id;
        $r = $update::find($data_id);
        if ($r->user_id != $user_id){
            die(trans('app.unauthorised_access'));
        }
        $r->delete();
        return ['success' => 1];
    }
}
