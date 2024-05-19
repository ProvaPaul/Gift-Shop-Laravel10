
@extends('front.layouts.app')

@section('content')
<section class="section-1">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">

                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/jj.jpg') }}">
                        <img src="{{ asset('front-assets/images/jj.jpg') }}" alt="Descriptive alt text" style="width: 1920px; height: 852px;">
                      </picture>
                    
        

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Occassions & Holydays</h1>
                            <p class="mx-md-5 px-5">From birthdays to holidays, find the perfect gift for every special occasion in our diverse selection, curated to bring joy and celebration to every moment</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop')}}">Shop Now</a>
                        </div>
                    </div>
                </div>

                
                <div class="carousel-item active">
                    <!-- <img src="images/carousel-1.jpg" class="d-block w-100" alt=""> -->

                    
                      <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/Special-Gifts-for-Special-Occasions.png') }}">
                        <img src="{{ asset('front-assets/images/Special-Gifts-for-Special-Occasions.png') }}" alt="Descriptive alt text" style="width: 1820px; height: 852px;">
                      </picture>
                      

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Kids Gifts</h1>
                            <p class="mx-md-5 px-5">Discover joy and wonder with our delightful selection of kids' gifts, perfect for every occasion!</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop')}}">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/SSS-Pink-Holiday-17.jpg') }}">
                        <img src="{{ asset('front-assets/images/SSS-Pink-Holiday-17.jpg') }}" alt="Descriptive alt text" style="width: 1920px; height: 552px; display: block;">
                      </picture>
                    
        

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Womens Gifts</h1>
                            <p class="mx-md-5 px-5">Explore our curated selection of gifts for women, designed to celebrate her unique style and elegance.</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop')}}">Shop Now</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/gifts-for-men1.webp') }}">
                        <img src="{{ asset('front-assets/images/gifts-for-men1.webp') }}" alt="Descriptive alt text" style="width: 1720px; height: 552px; display: block;">
                      </picture>
                    
        

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Mens Gifts</h1>
                            <p class="mx-md-5 px-5">Elevate his style and sophistication with our handpicked collection of gifts for men, tailored to suit every taste and occasion.</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop')}}">Shop Now</a>
                        </div>
                    </div>
                </div>

                
                
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    {{-- <section class="section-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Quality Product</h5>
                    </div>                    
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Free Shipping</h2>
                    </div>                    
                </div>
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">14-Day Return</h2>
                    </div>                    
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">24/7 Support</h5>
                    </div>                    
                </div>
            </div>
        </div>
    </section> --}}
   
    <section class="section-3 o" style="padding-top: 50px;">
    <div class="container">
        <div class="section-title oo">
            <h2 class="o">Categories</h2>
        </div>
        <div class="row pb-3">
            {{-- Check if categories are not empty --}}
            @if(getCategories()->isNotEmpty())
                {{-- Iterate over categories --}}
                @foreach (getCategories() as $category)
                    <div class="col-lg-3">
                        <div class="cat-card">
                            <div class="left">
                                {{-- Check if category image exists --}}
                                @if($category->image)
                                    <img src="{{ asset('uploads/category/thumb/'.$category->image) }}" alt="" class="img-fluid">
                                @endif
                            </div>
                            <div class="right">
                                <div class="cat-data">
                                    <h2>{{ $category->name }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- End of foreach loop --}}
                @endforeach
            {{-- End of if statement --}}
            @endif
        </div>
    </div>
</section>

    
    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Featured Products</h2>
            </div>    
            <div class="row pb-3">

            @if($featuredProducts->isNotEmpty())
                {{-- Iterate over categories --}}
                @foreach ($featuredProducts as $product)

                @php
                                         $productImage = $product->product_images->first();
                                             @endphp

                <div class="col-md-3">
                    <div class="card product-card">
                        <div class="product-image position-relative">
                            <a href="{{ route('front.product',$product->slug) }}" class="product-img">
                                
                            <!-- <img class="card-img-top" src="{{asset('front-assets/images/product-1.jpg')}}" alt=""></a> -->


                            @if(!empty($productImage->image))
                                                <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image)}}" width="50" />
                                                @else
                                                <img src="{{asset('admin-assets/img/1711691346.png')}}"  width="50" />
                                                
                           @endif



                           <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>                            

                            <div class="product-action">
                                @if ($product->track_qty == 'Yes')
                                @if ($product->qty >0)
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                    <i class="fa fa-shopping-cart"></i>Add To Cart
                                </a> 
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);">
                                    Out of Stock
                                </a>                               
                                @endif    
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                                    
                                @endif
                                                           
                            </div>
                        </div>                        
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="product.php">{{ $product->title }}</a>
                            <div class="price mt-2 o">
                                <span class="h5"><strong>${{ $product->price }}</strong></span>
                                @if($product->compare_price >0)
                                <span class="h6 text-underline"><del>${{$product->compare_price}}</del></span>
                                @endif
                            </div>
                        </div>                        
                    </div>                                               
                </div>  


                <!-- ////////////// -->
                   
                {{-- End of foreach loop --}}
                @endforeach
            {{-- End of if statement --}}
            @endif


                       
            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Latest Produsts</h2>
            </div>    
            <div class="row pb-3">
            @if($latestProducts->isNotEmpty())
                {{-- Iterate over categories --}}
                @foreach ($latestProducts as $product)

                @php
                                         $productImage = $product->product_images->first();
                                             @endphp

                <div class="col-md-3">
                    <div class="card product-card">
                        <div class="product-image position-relative">
                            <a href="{{ route('front.product',$product->slug) }}" class="product-img">
                                
                            <!-- <img class="card-img-top" src="{{asset('front-assets/images/product-1.jpg')}}" alt=""></a> -->


                            @if(!empty($productImage->image))
                                                <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image)}}" width="50" />
                                                @else
                                                <img src="{{asset('admin-assets/img/1711691346.png')}}"  width="50" />
                                                
                           @endif



                           <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>                            

                            <div class="product-action">
                                @if ($product->track_qty == 'Yes')
                                @if ($product->qty >0)
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                    <i class="fa fa-shopping-cart"></i>Add To Cart
                                </a> 
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);">
                                    Out of Stock
                                </a>                               
                                @endif    
                                @else
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                </a>
                                    
                                @endif                           
                            </div>
                        </div>                        
                        <div class="card-body text-center mt-3">
                            <a class="h6 link" href="product.php">{{ $product->title }}</a>
                            <div class="price mt-2">
                                <span class="h5"><strong>${{ $product->price }}</strong></span>
                                @if($product->compare_price >0)
                                <span class="h6 text-underline"><del>${{$product->compare_price}}</del></span>
                                @endif
                            </div>
                        </div>                        
                    </div>                                               
                </div>  


                <!-- ////////////// -->
                   
                {{-- End of foreach loop --}}
                @endforeach
            {{-- End of if statement --}}
            @endif

            </div>
        </div>
    </section>

    @endsection