<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:categories',
            'image' => 'required|mimes:jpg,png,jpeg,bmp'
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->name);
        if ($image) {
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            //check category dir is exist
            if (!Storage::disk('public')->exists('category')) {
                Storage::disk('public')->makeDirectory('category');
            }

            //resize image for category and upload
            $category = Image::make($image)->resize(1600, 479)->stream();
            Storage::disk('public')->put('category/'.$imagename, $category);

            //check category slider dir is exist
            if (!Storage::disk('public')->exists('category/slider')) {
                Storage::disk('public')->makeDirectory('category/slider');
            }

            //resize image for category slider and upload
            $slider = Image::make($image)->resize(500, 333)->stream();
            Storage::disk('public')->put('category/slider/'.$imagename, $slider);
        }else{
            $imagename = $category->image;
        }

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imagename;
        $category->save();
        Toastr::success('Category Added Successfully','Success');
        return redirect()->route('admin.category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.editcategory', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'image' => 'mimes:jpg,png,jpeg,bmp'
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->name);
        $category = Category::find($id);

        //check image is uploaded
        if ($image) {
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            //check category dir is exist
            if (!Storage::disk('public')->exists('category')) {
                Storage::disk('public')->makeDirectory('category');
            }

            //delete old image
            if(Storage::disk('public')->exists('category/'.$category->image)){
                Storage::disk('public')->delete('category/'.$category->image);
            }

            //resize image for category and upload
            $categoryimage = Image::make($image)->resize(1600, 479)->stream();
            Storage::disk('public')->put('category/'.$imagename, $categoryimage);

            //check category slider dir is exist
            if (!Storage::disk('public')->exists('category/slider')) {
                Storage::disk('public')->makeDirectory('category/slider');
            }

            //delete old slider image
            if(Storage::disk('public')->exists('category/slider/'.$category->image)){
                Storage::disk('public')->delete('category/slider/'.$category->image);
            }

            //resize image for category slider and upload
            $slider = Image::make($image)->resize(500, 333)->stream();
            Storage::disk('public')->put('category/slider/'.$imagename, $slider);
        }else{
            $imagename = $category->image;
        }

        //upload to the database
        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imagename;
        $category->save();
        Toastr::success('Category Edited Successfully','Success');
        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Category::find($id);
        //delete old image
        if(Storage::disk('public')->exists('category/'.$cat->image)){
            Storage::disk('public')->delete('category/'.$cat->image);
        }

        //delete old slider image
        if(Storage::disk('public')->exists('category/slider/'.$category->image)){
            Storage::disk('public')->delete('category/slider/'.$category->image);
        }

        //delete form database
        $cat->delete();

        Toastr::success('Category Deleted Successfully :)', 'Success');
        return redirect()->back();
    }
}
