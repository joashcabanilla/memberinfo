@extends('Layouts.Admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <h1 class="m-0 font-weight-bold p-2 tabTitle">MEMBER INFORMATION</h1>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline elevation-2 p-3">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label for="memberTypeFilter">Member Type</label>
                        <div class="form-group">
                            <select class="form-control" id="memberTypeFilter" name="memberType">
                                <option value=""> -- Select Member Type -- </option>
                                @foreach($memberTypeList as $type)
                                    <option value="{{$type}}">{{$type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
        
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label for="statusFilter">Status</label>
                        <div class="form-group">
                            <select class="form-control" id="statusFilter" name="status">
                                <option value=""> -- Select Status -- </option>
                                <option value="updated">UPDATED</option>
                                <option value="not-updated">NOT UPDATED</option> 
                            </select>
                        </div>
                    </div>
        
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label for="memberClearFilter"> &nbsp;</label>
                        <div class="form-group">
                            <button class="btn btn-sm btn-primary font-weight-bold" id="memberClearFilter"><i class="fas fa-filter"></i> Clear Filter</button>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline elevation-2 p-3">
                <div class="row mt-1">
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="memberfilterSearch"  placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-lg btn-default" id="memberSearchBtn">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->user_type == "admin")
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <button type="submit" class="btn btn-lg btn-primary float-lg-right font-weight-bold" id="memberAddBtn">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Member
                        </button>
                    </div>
                    @endif
                </div>
                <div class="table-responsive">
                    <table id="memberTable" class="table table-hover table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Member Type</th>
                                <th>Memid</th>
                                <th>Pbno</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>

<div class="modal fade" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="memberModalLabel">Add Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="modal-closeIcon" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="memberForm" method="POST">
                    <input type="hidden" name="id">
                    <input type="hidden" name="updated_by" value="{{Auth::user()->id}}">
                    <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="memberType">Member Type</label>
                                <div class="form-group">
                                    <select class="form-control" id="memberType" name="member_type" required autofocus>
                                        <option value=""> -- Select Member Type -- </option>
                                        @foreach($memberTypeList as $type)
                                            <option value="{{$type}}">{{$type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="memid">Memid</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Memid" id="memid" name="memid" autocomplete="false">
                                    <div class="invalid-feedback font-weight-bold"></div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="pbno">Pbno</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Pbno" id="pbno" name="pbno" autocomplete="false">
                                    <div class="invalid-feedback font-weight-bold"></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="title">Title</label>
                                <div class="form-group">
                                    <select class="form-control" id="title" name="title" required>
                                        <option value=""> -- Select Title -- </option>
                                        @foreach($titleList as $title)
                                            <option value="{{$title}}">{{$title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="firstname">First Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="First Name *" id="firstname" name="firstname" autocomplete="false" required>
                                    <div class="invalid-feedback font-weight-bold"></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="middlename">Middle Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Middle Name" id="middlename" name="middlename" autocomplete="false">
                                    <div class="invalid-feedback font-weight-bold"></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="lastname">Last Name</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Last Name *" id="lastname" name="lastname" autocomplete="false" required>
                                    <div class="invalid-feedback font-weight-bold"></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="suffix">Suffix</label>
                                <div class="form-group">
                                    <select class="form-control" id="suffix" name="suffix">
                                        <option value=""> -- Select Suffix -- </option>
                                        @foreach($suffixList as $suffix)
                                            <option value="{{$suffix}}">{{$suffix}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="address">Full Address</label>
                                    <textarea class="form-control" placeholder="Full Address *" id="address" name="full_address" style="height: 80px" required autocomplete="false"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="region">Region</label>
                                <div class="form-group">
                                    <select class="form-control font-weight-bold" id="region" name="region_code" required autocomplete="false">
                                        <option value="">-- Select Region --</option>
                                        @foreach($regionList as $key => $region)
                                            <option value="{{$key}}">{{$region}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="province">Province</label>
                                <div class="form-group">
                                    <select class="form-control" id="province" name="province_code" required autocomplete="false">
                                        <option value=""> -- Select Province -- </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="city">City</label>
                                <div class="form-group">
                                    <select class="form-control" id="city" name="citymun_code" required>
                                        <option value=""> -- Select City -- </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="barangay">Barangay</label>
                                <div class="form-group">
                                    <select class="form-control" id="barangay" name="barangay_code" required>
                                        <option value=""> -- Select Barangay -- </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="street">Unit Floor No.</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Unit Floor No. *" id="unitFloor" name="unit_floor_no" autocomplete="false" required>
                                    <div class="invalid-feedback font-weight-bold"></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="street">Street</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Street *" id="street" name="street" autocomplete="false" required>
                                    <div class="invalid-feedback font-weight-bold"></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="subdivision">Subdivision</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Subdivision" id="subdivision" name="subdivision" autocomplete="false">
                                    <div class="invalid-feedback font-weight-bold"></div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="area">Area</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Area" id="area" name="area" autocomplete="false">
                                    <div class="invalid-feedback font-weight-bold"></div>
                                </div>
                            </div>
                    </div>

                    <button type="submit" class="d-none" id="memberSubmitBtn">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary font-weight-bold">Submit</button>
                <a type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancel</a>
            </div>
        </div>
    </div>
</div>
@endsection