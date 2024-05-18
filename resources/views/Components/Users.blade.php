@extends('Layouts.Admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <h1 class="m-0 font-weight-bold p-2 tabTitle">USER ACCOUNT</h1>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline elevation-2 p-3">
                <div class="row mt-1">
                    <div class="col-lg-5 col-md-4 col-sm-12">
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="filterSearch"  placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-lg btn-default" id="userSearchBtn">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <select class="form-control" id="filterUserType" name="filterUserType">
                                    <option value=""> --Select User Type-- </option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <button type="submit" class="btn btn-lg btn-primary float-lg-right font-weight-bold" id="addBtn">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add User
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="userTable" class="table table-hover table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Type</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Ip Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="userModalLabel">Create New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="modal-closeIcon" aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="userForm" method="POST">
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label for="addUserType">User Type</label>
                            <div class="form-group">
                                <select class="form-control" id="addUserType" name="userType" required autocomplete="false">
                                    <option value=""> --Select User Type-- </option>
                                    <option value="admin">Admin</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="addName">Name</label>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Name *" id="addName" name="name" autocomplete="false" required>
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label for="addUsername">Username</label>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Username *" id="addUsername" name="username" autocomplete="false" required>
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="addPassword">Password</label>
                            <div class="form-group mb-0">
                                <input type="password" class="form-control" placeholder="Password *" id="addPassword" name="password" autocomplete="false" required>
                                <div class="invalid-feedback font-weight-bold"></div>
                            </div>
                            <div class="row p-0 mt-1">
                                <div class="col-6">
                                    <div class="icheck-success">
                                        <input type="checkbox" id="showPassword" name="showpassword">
                                        <label for="showPassword">Show password</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="icheck-success">
                                        <input type="checkbox" id="defaultPassword" name="defaultpassword" value="nvdc1976">
                                        <label for="defaultPassword">Default Password</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary font-weight-bold">Submit</button>
                    <a type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection