<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\str;
use Illuminate\support\facades\File;
use Iintervention\Image\LaravelFacades\Image;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\Slide;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Carbon\Carbon;




class  Admincontroller extends Controller
{

    public function index()
    {
        $orders = order::orderBy('created_at','DESC')->get()->take(10);
        $dashboardDatas = DB::select("Select sum(total) As TotalAmount,
                    sum(if(status='ordered', total, 0)) As TotalOrderedAmount,
                    sum(if(status='delivered', total, 0)) As TotaledDeliveredAmount,
                    sum(if(status='canceled', total, 0)) As TotalCanceledAmount,
                    Count(*) As Total,
                    sum(if(status='ordered', 1, 0)) As TotalOrdered,
                    sum(if(status='delivered', 1, 0)) As TotaledDelivered,
                    sum(if(status='canceled', 1, 0)) As TotalCanceled

                    From Orders
        ");
        $monthlyDatas = DB::select("SELECT M.id AS MonthNo, M.name AS MonthName,
            IFNULL(D.TotalAmount, 0) AS TotalAmount,
            IFNULL(D.TotalOrderedAmount, 0) AS TotalOrderedAmount,
            IFNULL(D.TotalDeliveredAmount, 0) AS TotalDeliveredAmount,
            IFNULL(D.TotalCanceledAmount, 0) AS TotalCanceledAmount
        FROM month_names M
        LEFT JOIN (SELECT DATE_FORMAT(created_at, '%b') AS MonthName,
                MONTH(created_at) AS MonthNo,
                SUM(IF(status='ordered', total, 0)) AS TotalOrderedAmount,
                SUM(IF(status='delivered', total, 0)) AS TotalDeliveredAmount,
                SUM(IF(status='canceled', total, 0)) AS TotalCanceledAmount,
                SUM(total) AS TotalAmount
            FROM Orders WHERE YEAR(created_at) = YEAR(NOW()) GROUP BY YEAR(created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')
                ORDER BY MONTH(created_at)) D ON D.MonthNo = M.id
    ");


    $AmountM = implode(',',collect($monthlyDatas)->pluck('TotalAmount')->toArray());
    $OrderAmountM = implode(',',collect($monthlyDatas)->pluck('TotalOrderAmount')->toArray());
    $DeliveredAmountM = implode(',',collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
    $CanceledAmountM = implode(',',collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());

    $TotalAmount = collect(',',collect($monthlyDatas)->sum('TotalAmount'));
    $TotalOrderAmount = collect(',',collect($monthlyDatas)->sum('TotalOrderAmount'));
    $TotalDeliveredAmount = collect(',',collect($monthlyDatas)->sum('TotalDeliveredAmount'));
    $TotalCanceledAmount = collect(',',collect($monthlyDatas)->sum('TotalCanceledAmount'));

        return view("admin.index",compact('orders','dashboardDatas','TotalAmount','TotalOrderAmount','TotalDeliveredAmount','TotalCanceledAmount','AmountM','OrderAmountM','DeliveredAmountM','CanceledAmountM'));
    }

    public function brands()
    {
      $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view("admin.brands" , compact ('brands'));
    }

    public function add_brand()
    {
        return view("admin.brand-add");
    }
    public function brand_store(Request $request){
    $request-> validate([
    'name'=>'required',
    'slug'=>'required|unique:brands,slug',
    'image'=>'mimes:png,jpg,jpeg|max:2048',
]);

$brand =new Brand();
$brand->name =$request->name;
$brand->slug = Str::slug($request->name);
$image = $request->file('image');
$file_extention = $request->file('image')->extension();
$file_name = Carbon::now()-> timestamp.'.'.$file_extention;
$this->GenerateBrandThumbnailsImage($image,$file_name);
$brand->image = $file_name;
$brand->save();
return redirect()->route('admin.brands')->with('status','brand has been added succesfully');
    }


    public function brand_edit($id)
{
 $brand=Brand::find($id);
    return  view('admin.brand-edit', compact('brand'));
}
public function  brand_update(request $request)
{
    $request-> validate([
        'name'=>'required',
        'slug'=>'required|unique:categories,slug '.$request->id,
        'image'=>'mimes:png,jpg,jpeg|max:2048',
    ]);
    $brand = Brand::find($request->id);
    $brand->name =$request->name;
    $brand->slug = str::slug($request->name);
    if($request->hasfile('image')){
        if(File::exists(public_path('upload/brands') .'/'.$brand->image))
        {
          File::delete(public_path('upload/brands').'/'.$brand->image);
        }
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name =Carbon::now()-> timestamp.'.'.$file_extension;
        $this->GenerateBrandThumbnailsImage($image,$file_name);
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.categories')->with('status','brand has been added succesfully');

    }


}
    public function GenerateBrandThumbnailsImage($image,$imageName)
    {
        $destinationpath =public_path('upload/brands');
        // $img =Image::read($image->path());
        $image->cover(124,124,"top");
        $img->resize(124,124, function($constraint){
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

    }
        public function  brand_delete($id)
        {
           $brand=Brand::find($id);
        if(file::exists(public_Path('upload/brands').'/'.$brands->image));
        {
           file::delete(public_Path('upload/brands').'/'.$brands->image);
        }
        $brand->delete();
        return  redirect()->route("admin.brands")->with('status', 'Brand has been deleted succesfuly');
       }

// endbrand





public function categories()
{
    $categories = category::orderBy('id ','DESC')->paginate(10);
    return view('admin.categories',compact('categories'));
}

public function category_add()
{
 return view('admin.category-add');
}
public function category_store(Request $request)
{

        $request-> validate([
            'name'=>'required',
            'slug'=>'required|unique:categories ,slug',
            'image'=>'mimes:png,png,jpg,jpeg|max:2048',
        ]);
        $category =new Category();
        $category->name =$request->name;
        $category->slug = str::slug($request->name);
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_nane =Carbon::now()-> timestamp.'.'.$file_extension;
        $this->GeneratecategoryThumbailsImage($image,$file_name);
        $category->image = $file_name;
        $category->save();
        return redirect()->route('admin.categories')->with('status','category has been added succesfully');
}
public function GeneratecategoryThumbailsImage($image ,$imageName)
{
    $destinationpath =public_Path('upload/categories');
    $img = Image::read($image->path());
    $img->cover(124,124,"top");
    $img->resize(124,124, function($constraint){
        $constraint->aspectRatio();
    })->save($destinationpath.'/'.$imageName);
}
public function category_edit($id)
{
    $category = category::find($id);
    return view('admin.category-edit'.compact('category'));
}

public function category_update(request $request)
{
   $request-> validate([
       'name'=>'required',
       'slug'=>'required|unique:categories,slug '.$request->id,
       'image'=>'mimes:png,png,jpg,jpeg|max:2040',
   ]);

  $category=category::find($request->id);

 $category->name =$request->name;
 $category->slug = str::slug($request->name);
   if($request->hash_file('image')){
       if(file::exists(public_path('upload/categories').'/' .$category->image));
       {
           file::delete(public_path('upload/categories').'/' .$category->image);

       }
       $image = $request->file('image');
       $file_extension = $request->file('image')->extension();
       $file_nane =Carbon::now()-> timestamp.'.'.$file_extension;
       $this->GeneratecategoryThumbailsImage($image,$file_name);
     $category->image = $file_name;
   }

   $image = $request->file('image');
   $file_extension = $request->file('image')->extension();
   $file_nane =Carbon::now()-> timestamp.'.'.$file_extension;
    $this->GeneratecategoryThumbailsImage($image,$file_name);
 $category->image = $file_name;
 $category->save();
   return redirect()->route('admin.categories')->with('status','category has been update succesfully');
}
public function category_delete($id){
    $category=category::find($id);
    if(file::exists(public_path('upload/categories').'/' .$category->image));
    {
        file::delete(public_path('upload/categories').'/' .$category->image);
    }
    $category->delete();
    return redirect()->route('admin.categories')->with('status', 'category has been delete succesfully');
}



// endcategories



public function products()
{
    $products =product::orderBy('created_at','DESC')->paginate(10);
    return view ('admin.products',compact('products'));
}
public function product_add()
    {
        $categories=Category::select('id','name')->orderBy('name')->get();
        $brands =Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add',compact('categories','brands'));
    }
    public function product_store(request $request)
    {
        $request->validate([
            '  name' =>'required' ,
            '  slug' =>'required|unique:producta,slug' ,
            '  short_description' =>'required' ,
            '  description' =>'required' ,
            '  reguar_price' =>'required' ,
            '  sale_price' =>'required' ,
            '  SKU'=>'required' ,
            '  stock_status' =>'required' ,
            '  feature' =>'required' ,
            '  quantity' =>'required' ,
            '  image' =>'required|mimes:png,jpg,jpeg|max:2048' ,
            '  category_id' =>'required' ,
            '  brand_id' =>'required' ,
        ]);
        $product = new product();
            $product->name=$request->name;
            $product->slug =str::slug($request->name);
            $product->short_description=$request->short_descriptionroduct;
            $product->description=$request->description;
            $product->reguar_price=$request->reguar_price;
            $product->sale_price=$request->sale_price;
            $product->SKU=$request->SKU;
            $product->stock_status=$request->stock_status;
            $product->feature=$request->feature;
            $product->quantit=$request->quantit;
            $product->category_id=$request->category_id;
            $product->brand_id=$request->brand_id;

            $current_timestamp = Carbon::now()->timestamp;


            if($request ->hasfile('image'));
            {
                $image =$request->file('image');
            $imageName =  $current_timestamp.'.'.$image->extension();
            $this->GenerateProductThumbnailImag($image,$imageName);
            $product->image =  $imageName;
            }
            $gallery_arr = array();
            $gallery_images = "";
            $counter=1;

            if($request->hasfile('images'))
            {
            $allowedfileExtion = ['jpj' ,'png' , 'jpeg'];
            $file = $request->file('images');
            foreach($files as $file)
            {
                $gextension =$file->getClientOriginalExtension();
                $gcheck = in_array($gextension , $allowedfileExtion);
                if($gcheck)
                {
                    $gfileName = $current_timestamp. '.' .$counter . ".".$gextension;
                    $this->GenerateProductThumbnailImag($file, $gfileName);
                    array_push($gallery_arr,$gfileName);
                    $counter=$counter +1;

                }
            }
            $gallery_images = implode(',',$gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'product has been added succesfully');
    }



    public function GenerateProductThumbnailImage($image ,$imageName)
    {
        $destinationpaththumbnail =public_Path('upload/products/thumbnails');
        $destinationpath =public_Path('upload/products');
        $img = Image::read($image->path());

        $img->cover(540,689,"top");
        $img->resize(540,689, function($constraint){
            $constraint->aspectRatio();
        })->save($destinationpath.'/'.$imageName);

        $img->resize(104,104, function($constraint){
            $constraint->aspectRatio();
        })->save($destinationpath.'/'.$imageName);

    }
    public function prodect_edit($id)
    {
        $prodect=prodect::find($id);
        $categories= Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.prodect-edit',compact('prodect','categories','brands'));
    }
    public function product_update(request $request)
    {
        $request->validate([
            '  name' =>'required' ,
            '  slug' =>'required|unique:producta,slug,'.$request->id ,
            '  short_description' =>'required' ,
            '  description' =>'required' ,
            '  reguar_price' =>'required' ,
            '  sale_price' =>'required' ,
            '  SKU'=>'required' ,
            '  stock_status' =>'required' ,
            '  feature' =>'required' ,
            '  quantity' =>'required' ,
            '  image' =>'mimes:png,jpg,jpeg|max:2048' ,
            '  category_id' =>'required' ,
            '  brand_id' =>'required' ,
        ]);
        $prodect = prodect::find($request->id);
        $product->name=$request->name;
        $product->slug =str::slug($request->name);
        $product->short_description=$request->short_descriptionroduct;
        $product->description=$request->description;
        $product->reguar_price=$request->reguar_price;
        $product->sale_price=$request->sale_price;
        $product->SKU=$request->SKU;
        $product->stock_status=$request->stock_status;
        $product->feature=$request->feature;
        $product->quantit=$request->quantit;
        $product->category_id=$request->category_id;
        $product->brand_id=$request->brand_id;


        $current_timestamp = Carbon::now()->timestamp;
        if($request ->hasfile('image'));
        {
            if(file::exists(public_Path('upload/products').'/'.$product->image));
            {
               file::delete(public_Path('upload/products').'/'.$product->image);
            }
            if(file::exists(public_Path('upload/products/thumbnails').'/'.$product->image));
            {
               file::delete(public_Path('upload/products/thumbnails').'/'.$product->image);
            }


        $image =$request->file('image');
        $imageName =  $current_timestamp.'.'.$image->extension();
        $this->GenerateProductThumbnailImag($image,$imageName);
        $product->image =  $imageName;
        }
        $gallery_arr = array();
        $gallery_images = "";
        $counter=1;

        if($request->hasfile('images'))
        {
            foreach(explode(',',$prodect_images)as $ofile)
            {
                if(file::exists(public_Path('upload/products').'/'.$ofile));
                {
                   file::delete(public_Path('upload/products').'/'.$ofile);
                }
                if(file::exists(public_Path('upload/products/thumbnails').'/'.$ofile));
                {
                   file::delete(public_Path('upload/products/thumbnails').'/'.$ofile);
                }
            }
        $allowedfileExtion = ['jpj' ,'png' , 'jpeg'];
        $file = $request->file('images');
        foreach($files as $file)
        {
            $gextension =$file->getClientOriginalExtension();
            $gcheck = in_array($gextension , $allowedfileExtion);
            if($gcheck)
            {
                $gfileName = $current_timestamp. '.' .$counter . ".".$gextension;
                $this->GenerateProductThumbnailImag($file, $gfileName);
                array_push($gallery_arr,$gfileName);
                $counter=$counter +1;

            }
        }
        $gallery_images = implode(',',$gallery_arr);
        $product->images = $gallery_images;
    }

    $product->save();
    return redirect()->route('admin.products')->with('status', 'product has been update succesfully');
}










    public function product_delete($id)
    {
        $product = product::find($id);
        if(file::exists(public_path('upload/products').'/'.$prodect->image))
        {
            file::delete(public_path('upload/products').'/'.$prodect->image);
        }
        if(file::exists(public_path('upload/products/thumbnails').'/'.$prodect->image))
        {
            file::delete(public_path('upload/products/thumbnails').'/'.$prodect->image);
        }

        foreach(explode( ',',$prodect->images)as $ofile)
            {
            if(file::exists(public_path('upload/products').'/'.$ofile))
            {
                file::delete(public_path('upload/products').'/'.$ofile);
            }

            if(file::exists(public_path('upload/products/thumbnails').'/'.$ofile))
            {
                file::delete(public_path('upload/products/thumbnails').'/'.$ofile);
            }



    }
    $product->delete();
    return rredirect()->route('admin.products')->with('status', 'prodect has been delete sucessfully');

    }
    public function coupons()
    {
        $coupons = Coupon::orderBy('expiry_date','DESC')->paginate(12);
        return view('admin.coupons',compact('coupons'));
    }
    public function coupon_add()
    {
        return view('admin.coupon-add');
    }
    public function coupon_store(Request $request)
    {
        $request->validate([
            'code' =>"required",
            'type' =>"required",
            'value' =>"required|numeric",
            'cart_value' =>"required|numeric",
            'expiry_date' =>"required|date",
        ]);
        $coupon = new Coupon();
        $coupon->code = $request-> code;
        $coupon->type = $request->type ;
        $coupon-> value= $request->value ;
        $coupon-> cart_value= $request->cart_value ;
        $coupon->expiry_date = $request-> expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status','Coupon has been added successfully!');
    }
    public function coupon_edit($id)
    {
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit',compact('coupon'));
    }
    public function coupon_update(Request $request)
    {
        $request->validate([
            'code' =>"required",
            'type' =>"required",
            'value' =>"required|numeric",
            'cart_value' =>"required|numeric",
            'expiry_date' =>"required|date",
        ]);
        $coupon =Coupon::find($request->id);
        $coupon->code = $request-> code;
        $coupon->type = $request->type ;
        $coupon-> value= $request->value ;
        $coupon-> cart_value= $request->cart_value ;
        $coupon->expiry_date = $request-> expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status','Coupon has been update successfully!');
    }

    public function coupon_delete($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status','Coupon has been delete successfully!');
    }
    public function orders()
    {
        $order = Order::orderBy('created_at','DESC')->paginate(12);
        return view('admin.orders' ,compact('orders'));
    }
    public function  order_details($order_id)
    {
        $order = Order::find($order_id);
        $orderItemes = OrderItem::where('order_id',$order_id)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id',$order_id)->first();
        return view('admin.order-details',compact('order','orderItems','transaction'));
    }
    public function update_order_status (Requesr $request)
    {
        $order = order::find($request->order_id);
        $order_status = $request->order_status;
        if($request->order_status == 'delivered')
        {
            $order->delivered_date = Carbon::now();
        }
        else if ($request->order_status == 'canceled')
        {
            $order->canceled_date = Carbon::now();
        }
        $order->save();
        if($request->order_status=='delivered')
        {
            $transaction = Transaction::where('order_id',$request->order_id)->first();
            $transaction->status = 'approved';
            $transaction->save();

        }
        return back()->with('status','status changed successfully!');

    }
    public function slides()
    {
        $slides = Slide::orderBy('id','DESC')->paginate(12);
        return view('admin.slides',compact('slides'));
    }
    public function slide_add()
    {
        return view('admin.slide-add');
    }
    public function slide_store(Request $request)
    {
        $request->validate([
            'tagline'=>"required",
            'title'=>"required",
            'subtitle'=>"required",
            'link'=>"required",
            'status'=>"required",
            'image'=>"required|mimes:png.jpg.jpeg|max:2048",
        ]);
        $slide = new Slide();
        $slide->tagline =$request->tagline ;
        $slide->title =$request-> title;
        $slide->subtitle =$request-> subtitle;
        $slide->link =$request->link ;
        $slide->status =$request->status ;
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_nane =Carbon::now()-> timetamp.'.'.$file_extension;
        $this->GenerateSlideThumbailsImage($image,$file_name);
        $slide->image = $file_name;
        $slide->save();
        return  redirect()->route('admin.slides')->with('status','Slide dded seccessfully!');
    }
    public function GenerateSlideThumbailsImage($image ,$imageName)
{
    $destinationpath =public_Path('upload/slides');
    $img = Image::read($image->path());
    $img->cover(400,690,"top");
    $img->resize(400,690, function($constraint){
        $constraint->aspectRatio();
    })->save($destinationpath.'/'.$imageName);
}
    public function slide_edit($id)
    {
        $slide =Slide::find($id);
        return view('admin.slide-edit',compact('slide'));
    }
    public function slide_update(Request $request)
    {
        $request->validate([
            'tagline'=>"required",
            'title'=>"required",
            'subtitle'=>"required",
            'link'=>"required",
            'status'=>"required",
            'image'=>"mimes:png.jpg.jpeg|max:2048",
        ]);
        $slide = Slide::find($request->id);
        $slide->tagline =$request->tagline ;
        $slide->title =$request-> title;
        $slide->subtitle =$request-> subtitle;
        $slide->link =$request->link ;
        $slide->status =$request->status ;
        if($request->hasfile('image'))
        {
            if(File::exists(public_path('upload/slides').'/' .$slide->image))
            {
                File::delete(Public_path('upload/slides').'/'.$slide->image);
            }
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_nane =Carbon::now()-> timetamp.'.'.$file_extension;
        $this->GenerateSlideThumbailsImage($image,$file_name);
        $slide->image = $file_name;
        }
        $slide->save();
        return  redirect()->route('admin.slides')->with('status','Slide Update seccessfully!');
    }
    public function  slide_delete($id)
    {
        $slide = Slide::find($id);
        if(File::exists(public_path('upload/slides').'/' .$slide->image))
        {
            File::delete(Public_path('upload/slides').'/'.$slide->image);
        }
        $slide->delete();
        return  redirect()->route('admin.slides')->with('status','Slide deleted seccessfully!');
    }
    }

