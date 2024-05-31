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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'images.*.image' => 'Only .jpg, .jpeg and .png is allowed extension',
        ]);

        $data = $request->except('_token', 'images');
        $data['user_id'] = $request->user()->id;
        $data['campaign_id'] = $id;

        $image_names = [];
        if ($images = $request->file('images')) {
            foreach ($images as $image) {
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
                $image_names[] = $image_name;
            }
        }

        $data['images'] = $image_names;

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
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'images.*.image' => 'Only .jpg, .jpeg and .png are allowed extensions',
        ]);

        $update = Update::findOrFail($update_id);
        $update->title = $request->input('title');
        $update->description = $request->input('description');

        // Handle existing images
        $existingImages = $update->images ?: [];
        $deleteImages = $request->input('delete_images', []);

        // Filter out images marked for deletion
        $existingImages = is_string($existingImages) ? json_decode($existingImages, true) : $existingImages;
        $existingImages = array_filter($existingImages, function($image) use ($deleteImages) {
            return !in_array($image, $deleteImages);
        });

        // Handle new image uploads
        $newImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $extension = strtolower($image->getClientOriginalExtension());
                $validExtensions = ['jpg', 'jpeg', 'png'];

                if (!in_array($extension, $validExtensions)) {
                    return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png are allowed extensions');
                }

                $uploadDir = 'storage/uploads/updates/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileBaseName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $imageName = strtolower(time() . str_random(5) . '-' . str_slug($fileBaseName)) . '.' . $extension;
                $imageFileName = $uploadDir . $imageName;

                Image::make($image)->resize(900, 400)->save(public_path($imageFileName));

                $newImages[] = $imageName;
            }
        }

        // Merge new images with existing images
        $allImages = array_merge($existingImages, $newImages);

        // Save all images as JSON
        $update->images = json_encode($allImages);

        if ($update->save()) {
            return redirect(route('edit_campaign_updates', $campaign_id))->with('success', trans('app.update_updated'));
        }

        return redirect()->back()->with('error', 'Something went wrong')->withInput($request->input());
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
