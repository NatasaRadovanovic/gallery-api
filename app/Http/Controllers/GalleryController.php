<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gallery;
use App\User;
use App\Image;
use App\Http\Requests\GalleryRequest;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries = Gallery::orderBy('created_at', 'desc')
                                ->with('images','user')
                                ->paginate(10);
        
        return $galleries;
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
    public function store(GalleryRequest $request)
    {
        $gallery = new Gallery();
        $gallery->name = $request['name'];
        $gallery->description = $request['description'];
        $gallery->user_id = Auth()->user()->id;
        $gallery->save();
        $images = [];
        
        foreach ($request->images as $image) {
           array_push($images, new Image([
               'url' => $image,
               'gallery_id' => $gallery->id
               ]));
        }
        $gallery->images()->saveMany($images);
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gallery = Gallery::with('images', 'user','comments.user')
                            ->findOrFail($id);

        return $gallery;
    }

    public function showAuthorsGalleries($id)
    {   
        $galleries = Gallery::orderBy('created_at', 'desc')
                                ->with('images','user')
                                ->where('user_id', $id)
                                ->paginate(10);

        return $galleries;
    }

    public function showOwnersGalleries()
    {
        $user_id = auth()->user()->id;
        
        $galleries = Gallery::with('images','user')
                            ->where('user_id',  $user_id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return $galleries;  
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GalleryRequest $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->update($request->all()); 
        
        return $gallery;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gallery::destroy($id);
        return response()->json(['message' => 'Gallery deleted successfully']);
    }
}
