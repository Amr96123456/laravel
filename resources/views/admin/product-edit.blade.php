
@extends("layout.admin")
@section("content")

<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{route('admin.products')}}">
                        <div class="text-tiny">Products</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">edit product</div>
                </li>
            </ul>
        </div>
        <!-- form-add-product -->
        <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"action="{{route('admin.product.update')}}">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{$prodect->id}}">
            <div class="wg-box">
                <fieldset class="name">
                    <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                    </div>
                    <input class="mb-10" type="text" placeholder="Enter product name"name="name" tabindex="0" value="{{$prodect->name}}" aria-required="true" required="">
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('name')<span class="alert alert-danger text-center">{{$message}}  @enderror

                <fieldset class="name">
                    <div class="body-title mb-10">Slug <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter product slug"name="slug" tabindex="0" value="{{$prodect->slug}}" aria-required="true" required="">
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('slug')<span class="alert alert-danger text-center">{{$message}}  @enderror



                <div class="gap22 cols">
                    <fieldset class="category">
                        <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="category_id">
                                <option>Choose category</option>
                                @foreach ($categories as$Category)
                                <option value="{{$category->id}}"{{$prodect->category_id ==$category->id ? "selected":""}}>{{$category->name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </fieldset>
                    @error('category_id')<span class="alert alert-danger text-center">{{$message}}  @enderr
                    @error('slug')<span class="alert alert-danger text-center">{{$message}}  @enderror
                    <fieldset class="brand">
                        <div class="body-title mb-10">Brand <span class="tf-color-1">*</span>
                        </div>
                        <div class="select">
                            <select class="" name="brand_id">
                                <option>Choose Brand</option>
                                @foreach ( $brands as $brand )


                                <option value="{{$brand->id}}"{{$prodect->brand_id ==$brand->id ? "selected":""}}>{{$brand->name}}</option>
                                @endforeach


                            </select>
                        </div>
                    </fieldset>
                    @error('brand_id')<span class="alert alert-danger text-center">{{$message}}  @enderror
                </div>

                <fieldset class="shortdescription">
                    <div class="body-title mb-10">Short Description <spanclass="tf-color-1">*</span></div>
                    <textarea class="mb-10 ht-150" name="short_description"placeholder="Short Description" tabindex="0" aria-required="true"
                        required="">{{$prodect->short_description}}</textarea>
                    <div class="text-tiny">Do not exceed 100 characters when entering the
                        product name.</div>
                </fieldset>
                @error('Short Description')<span class="alert alert-danger text-center">{{$message}}  @enderror
                <fieldset class="description">
                    <div class="body-title mb-10">Description <span class="tf-color-1">*</span>
                    </div>
                    <textarea class="mb-10" name="description" placeholder="Description"tabindex="0" aria-required="true" required="">{{$prodect->description}}</textarea>
                    <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                </fieldset>
                @error('description')<span class="alert alert-danger text-center">{{$message}}  @enderror
            </div>
            <div class="wg-box">
                <fieldset>
                    <div class="body-title">Upload images <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        @if($prodect->image)
                        <div class="item" id="imgpreview" >
                            <img src="{{asset("uploade/products")}}/{{$prodect->image}}"class="effect8" alt="{{$prodect->name}}">
                        </div>
                        @endif
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span
                                        class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image')<span class="alert alert-danger text-center">{{$message}}  @enderror

                <fieldset>
                    <div class="body-title mb-10">Upload Gallery Images</div>
                    <div class="upload-image mb-16">
                        @if($prodect->image)
                        @foreach (exblode(',',$prodect->image) as $image)
                        <div class="item gitems">
                            <img src="{{asset('upload/products')}}/{{trim($img)}}"alt="">
                        </div>
                        @endforeach
                        @endif                -
                        <div id="galUpload" class="item up-load">
                            <label class="uploadfile" for="gFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="text-tiny">Drop your images here or select <span
                                        class="tf-color">click to browse</span></span>
                                <input type="file" id="gFile" name="images[]" accept="image/*"
                                    multiple="">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('images')<span class="alert alert-danger text-center">{{$message}}  @enderror

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Regular Price <span
                                class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter regular price"
                            name="regular_price" tabindex="0" value="{{$prodect->regular_price}}" aria-required="true"
                            required="">
                    </fieldset>
                    @error('regular_price')<span class="alert alert-danger text-center">{{$message}}  @enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Sale Price <span
                                class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter sale price"
                            name="sale_price" tabindex="0" value="{{$prodect->sale_price}}" aria-required="true"
                            required="">
                    </fieldset>
                    @error('sale_price')<span class="alert alert-danger text-center">{{$message}}  @enderror
                </div>


                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU"
                            tabindex="0" value="{{$prodect->SKU}}" aria-required="true" required="">
                    </fieldset>
                    @error('SKU')<span class="alert alert-danger text-center">{{$message}}  @enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter quantity"
                            name="quantity" tabindex="0" value="{{$prodect->quantity}}" aria-required="true"
                            required="">
                    </fieldset>
                    @error('quantity')<span class="alert alert-danger text-center">{{$message}}  @enderror
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Stock</div>
                        <div class="select mb-10">
                            <select class="" name="stock_status">
                                <option value="instock"{{$prodect->stock_status == "instock" ? "selected":""}}>InStock</option>
                                <option value="outofstock"{{$prodect->stock_status =="outofstock"? "selected":""}}>Out of Stock</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('stock_status')<span class="alert alert-danger text-center">{{$message}}  @enderror
                    <fieldset class="name">
                        <div class="body-title mb-10">Featured</div>
                        <div class="select mb-10">
                            <select class="" name="featured">
                                <option value="0"{{$prodect->featured =="0" ? "selected":""}}>No</option>
                                <option value="1"{{$prodect->featured =="1" ? "selected":""}}>Yes</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('featured')<span class="alert alert-danger text-center">{{$message}}  @enderror
                </div>
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">update product</button>
                </div>
            </div>
        </form>
        <!-- /form-add-product -->
    </div>
    <!-- /main-content-wrap -->
</div>
@endsection
@push('script')
<Script>


    $(function(){
        $('#myfile').on("change",function(e){
            const photoInp =$('#myfile');
            const[file] =this.files;
            if(file)
            {
                $("#imgpreview img").atter('src'.URL.createobjectURL(file));
                $("#imgpreview").show();
            }
        });

        $('#gfile').on("change",function(e){
            const photoInp =$('#gfile');
            const gphotos =this.files;
         $.each(gphotos.function (key,val) {
            $("galUpload").prepend('<div class="item gitems"><img src="${URL.createObjectURL(val)}"/></div>');
         });
        });

        $("input[name='name']").on("change",function(){
        $("input[name='slug']").val(stringToslug($(this).val()));
    });

    });
    function stringToslug(Text)
    {
        return Text.TolowerCase()
        .replace(/[^\w]+/g,"")
        .replace(/ +/g,"." );
    }



</Script>
@endpush
