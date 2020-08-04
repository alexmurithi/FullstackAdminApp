<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Category;

class AdminController extends Controller
{
   public function addTag(Request $request){
       $this->validate($request,[
         'tagName'=>'required',
       ]);

     return Tag::create([
       'tagName'=>$request->tagName
     ]);
   }

   public function getTags(){
     $tags =Tag::orderBy('created_at','DESC')->get();
     return response()->json($tags);
   }

   public function editTag(Request $request){
    $this->validate($request,[
      'tagName'=>'required',
      'id'=>'required'
    ]);

     Tag::where('id',$request->id)->update([
        'tagName'=>$request->tagName
    ]);

    $tags =Tag::orderBy('created_at','DESC')->get();
    return response()->json($tags);

   }

   public function destroy(Request $request){
      $this->validate($request,[
        'id'=>'required',
      ]);

    Tag::where('id',$request->id)->delete();
    $tags =Tag::orderBy('created_at','DESC')->get();
    return response()->json($tags);

   
   }

   public function uploadImg(Request $request){

    $this->validate($request,[
      'file'=>'required|mimes:jpeg,jpg,gif,png'

    ]);

     $image =time().'_'.$request->file->getClientOriginalName();

     $request->file->move(public_path('uploads'),$image);
     return $image;

   }

   public function addCategory(Request $request){

    $this->validate($request,[
      'iconImage'=>'required',
      'categoryName'=>'required'

    ]);
    return Category::create($request->all());
    
   }

   public function getCategories(){
    $categories =Category::orderBy('created_at','DESC')->get();
    return response()->json($categories);
  }


}
