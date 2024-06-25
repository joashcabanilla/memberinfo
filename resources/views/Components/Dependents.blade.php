@extends('Layouts.Admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <h1 class="m-0 font-weight-bold p-2 tabTitle">DEPENDENTS AND BENEFICIARIES</h1>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline elevation-2 p-3">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label for="dependent-memberTypeFilter">Member Type</label>
                        <div class="form-group">
                            <select class="form-control" id="dependent-memberTypeFilter" name="memberType">
                                <option value=""> -- Select Member Type -- </option>
                                @foreach($memberTypeList as $type)
                                    <option value="{{$type}}">{{$type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>        
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label for="dependent-memberClearFilter"> &nbsp;</label>
                        <div class="form-group">
                            <button class="btn btn-sm btn-primary font-weight-bold" id="dependent-memberClearFilter"><i class="fas fa-filter"></i> Clear Filter</button>
                        </div> 
                    </div>
                </div>
            </div>
            
            <div class="card card-primary card-outline elevation-2 p-3">
                <div class="row mt-1">
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="dependent-memberfilterSearch"  placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-lg btn-default" id="dependent-memberSearchBtn">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="dependent-memberTable" class="table table-hover table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Member Type</th>
                                <th>Memid</th>
                                <th>Pbno</th>
                                <th>Name</th>
                                <th>Dependents</th>
                                <th>Beneficiaries</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="dependentModal" tabindex="-1" role="dialog" aria-labelledby="dependentModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="dependentModalLabel">Dependents</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="modal-closeIcon" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="dependentForm" method="POST">
                    <div class="row">
                        <div class="col-lg-6 col-md-4 col-sm-12">
                            <label for="dependent-membername">Member Name</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="dependent-membername" name="membername" autocomplete="false" readonly>
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <label for="dependent-memid">Member Id</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="dependent-memid" name="memid" autocomplete="false" readonly>
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <label for="dependent-pbno">Passbook No</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="dependent-pbno" name="pbno" autocomplete="false" readonly>
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="member_id">
                    <input type="hidden" name="id">
                    <input type="hidden" name="created_by" value="{{Auth::user()->id}}">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <label for="dependent-firstname">First Name</label>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="First Name *" id="dependent-firstname" name="firstname" autocomplete="false" required>
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <label for="dependent-middlename">Middle Name</label>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Middle Name" id="dependent-middlename" name="middlename" autocomplete="false">
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <label for="dependent-lastname">Last Name</label>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Last Name *" id="dependent-lastname" name="lastname" autocomplete="false" required>
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <label for="dependent-suffix">Suffix</label>
                            <div class="form-group">
                                <select class="form-control" id="dependent-suffix" name="suffix">
                                    <option value=""> -- Select Suffix -- </option>
                                    @foreach($suffixList as $suffix)
                                        <option value="{{$suffix}}">{{$suffix}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <label for="dependent-birthdate">Birthdate</label>
                            <div class="form-group">
                                <input type="date" class="form-control" id="dependent-birthdate" name="birthdate" autocomplete="false">
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <label for="dependent-relationship">Relationship</label>
                            <div class="form-group">
                                <select class="form-control font-weight-bold" id="dependent-relationship" name="relationship" required autocomplete="false">
                                    <option value="">-- Select Relationship --</option>
                                    @foreach($relationshipList as $key => $relationship)
                                        <option value="{{$key}}">{{$relationship}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <label for="dependent-contact">Contact No.</label>
                            <div class="form-group">
                                <input type="text" class="form-control" id="dependent-contact" name="contact_no" autocomplete="false" maxlength="11" minlength="11">
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <label for="dependent-addBtn"> &nbsp;</label>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary font-weight-bold" id="dependent-addBtn"><i class="fas fa-save"></i> Save</button>
                            </div> 
                        </div>
                    </div>
                </form>
                <div class="card card-primary p-1 modalTableContainer">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection