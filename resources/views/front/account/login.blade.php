@extends('front.layouts.app');
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                    <li class="breadcrumb-item">Login</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success') }}
            <!-- // if loop success msg show korar jonno used -->
</div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger">
            {{Session::get('error') }}
            <!-- //thisif loop success msg show korar jonno used -->
</div>
        @endif

        <div class="container">
            <div class="login-form">    
                <form action="{{ route('account.authenticate')}}" method="post">
                @csrf
                    <h4 class="modal-title">Login to Your Account</h4>
                    <div class="form-group">
                        <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">

                        @error('email')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password">

                        @error('password')
                        <p class="invalid-feedback">{{$message}}</p>
                        @enderror

                    </div>
                    <div class="form-group small">
                        <a href="{{ route('front.forgotPassword')}}" class="forgot-link">Forgot Password?</a>
                    </div> 
                    <input type="submit" class="btn btn-dark btn-block btn-lg" value="Login">              
                </form>			
                <div class="text-center small">Don't have an account? <a href="{{ route('account.register')}}">Sign up</a></div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
<!-- <script type="text/javascript">
    $("#registrationForm").submit(function(event){
                        event.preventDefault();
                        var element=$(this);

                        $.ajax({
                            url:'{{route("account.processRegister")}}',
                            type:'post',
                            data: element.serializeArray(),
                            dataType:'json',
                            success: function(response){
                                var errors=response.errors;
                                
                                if(response.status==false){
                                    if(errors.name){
                                    $("#name").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback').html(errors.name);

                                }
                                else{
                                    $("#name").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback').html("");

                                }

                                if(errors.email){
                                    $("#email").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback').html(errors.email);

                                }
                                else{
                                    $("#email").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback').html("");

                                }
                                if(errors.password){
                                    $("#password").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback').html(errors.password);

                                }
                                else{
                                    $("#password").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback').html("");

                                }

                                }
                                else{
                                    $("#name").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback').html("");


                                    $("#email").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback').html("");

                                    $("#password").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback').html("");





                                }
                                
                                

                            

                            },error: function(jQXHR,exception){
                                console.log("something went wrong");
                            }

                            
                        })
                    });

</script> -->

@endsection