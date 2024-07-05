@extends('Layouts.Guest')
@section('content')
    <div class="container-login hold-transition login-page">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <img src="{{asset('image/1.png')}}" alt="logo" width="250" />
                </div>
                <div class="card-body">
                    <p class="text-monospace font-weight-bold mb-2">Please enter your PbNo or MemID and Name to search for your account.</p>
                    <form id="emailForm" method="POST">
                        <input type="hidden" name="id" />
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="PbNo or MemID *" id="pbno_memid" name="pbno_memid" autocomplete="false" required autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="First Name *" id="firstname" name="firstname" autocomplete="false" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Last Name *" id="lastname" name="lastname" autocomplete="false" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-monospace font-weight-bold mb-2 labelEmail d-none">Please save your email address for the new system.</p>

                        <div class="input-group mb-3 d-none">
                            <input type="email" class="form-control" placeholder="Email *" id="email" name="email" autocomplete="false">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-12">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 