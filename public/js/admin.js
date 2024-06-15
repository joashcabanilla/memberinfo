$(document).ready((e) => {
    $("#region").select2({
        theme: 'bootstrap4',
        dropdownParent: $('#memberModal'),
        placeholder: '-- Select Region --'
    });

    $("#province").select2({
        theme: 'bootstrap4',
        dropdownParent: $('#memberModal'),
        placeholder: '-- Select Province --'
    });

    $("#city").select2({
        theme: 'bootstrap4',
        dropdownParent: $('#memberModal'),
        placeholder: '-- Select City --'
    });

    $("#barangay").select2({
        theme: 'bootstrap4',
        dropdownParent: $('#memberModal'),
        placeholder: '-- Select Barangay --'
    });

    $("#dependent-relationship").select2({
        theme: 'bootstrap4',
        dropdownParent: $('#dependentModal'),
        placeholder: '-- Select Relationship --'
    });
});

$(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
});

let userTable = $('#userTable').on('init.dt', function () {
    $(".dataTables_wrapper").prepend("<div class='dataTables_processing card font-weight-bold d-none' role='status'>Loading Please Wait...<i class='fa fa-spinner fa-spin text-warning'></i></div>");
}).DataTable({
    ordering: false,
    serverSide: true,
    dom: 'rtip',
    columnDefs: [
        { targets: 0, width: '1%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 1, width: '10%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 2, width: '20%', className: "text-left align-middle font-weight-bold p-2" },
        { targets: 3, width: '10%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 4, width: '10%', className: "text-left align-middle font-weight-bold p-2" },
        { targets: 5, width: '10%', className: "text-left align-middle font-weight-bold p-2" },
        { targets: 6, width: '5%', className: "text-center align-middle font-weight-bold p-2" },
    ],
    ajax: {
        url: '/admin/userTable',
        type: 'POST',
        data: function (d) {
            d.filterSearch = $("#filterSearch").val();
            d.filterUserType = $("#filterUserType").val();
        },
        beforeSend: () => {
            $(".dataTables_processing").removeClass("d-none");
        },
        complete: () => {
            $(".dataTables_processing").addClass("d-none");
        }
    }
});

$("#filterSearch").keyup((e) => {
    userTable.draw();
});

$("#filterUserType").change((e) => {
    userTable.draw();
});

$("#addBtn").click((e) => {
    $("#userModal").modal("show");
});

$("#showPassword").change((e) => {
    if ($(e.currentTarget).is(":checked")) {
        $("#addPassword").attr("type", "text");
    } else {
        $("#addPassword").attr("type", "password");
    }
});

$("#defaultPassword").change((e) => {
    if ($(e.currentTarget).is(":checked")) {
        $("#addPassword").val($("#defaultPassword").val());
        $("#addPassword").removeClass("is-invalid");
    } else {
        $("#addPassword").val("");
    }
});

$("#addPassword").keyup((e) => {
    $("#defaultPassword").prop("checked", false);
});

$('#userModal').on('hidden.bs.modal', function (e) {
    $("#addUserType").val("");
    $("#addName").val("");
    $("#addUsername").val("");
    $("#addPassword").val("");
    $("#userForm").find("input[type='checkbox']").prop("checked", false);
    $("#userForm").find("input[type='hidden']").val("");
    $('#userModal').find("input[name='password']").prop("required",true);
    $("#userModalLabel").text("Create New User");
});

$("#userForm").submit((e) => {
    e.preventDefault();
    $.LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url: "/admin/createUpdateUser",
        data: $(e.currentTarget).serializeArray(),
        success: (res) => {
            $.LoadingOverlay("hide");
            if(res.status == "failed"){
                for(let errorKey in res.error){
                    $("#userForm").find("input[name='"+errorKey+"']").addClass("is-invalid").focus().next().text(res.error[errorKey]);
                }
            }else{
                $("#userModal").modal("hide");
                Swal.fire({
                    title: "Successfully Saved.",
                    icon: res.status,
                    confirmButtonText: "OK",
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    userTable.ajax.reload(null, false);
                });
            }
        }
    });
});

$("#userForm").find("input").keyup((e) => {
    $(e.currentTarget).removeClass("is-invalid");
});

$('#userTable').on('click', '.editBtn', (e) => {
    let userId = $(e.currentTarget).data("id");
    $("#userModalLabel").text("Update User Info");
    $.LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url: "/admin/getUser",
        data: {id:userId},
        success: (res) => {
            $.LoadingOverlay("hide");
            $('#userModal').find("input[name='id']").val(res.id);
            $('#userModal').find("select[name='userType']").val(res.user_type);
            $('#userModal').find("input[name='name']").val(res.name);
            $('#userModal').find("input[name='username']").val(res.username);
            $('#userModal').find("input[name='password']").prop("required",false);
            $("#userModal").modal("show");
        }
    });
});

$('#userTable').on('click', '.deactivateBtn', (e) => {
    let userId = $(e.currentTarget).data("id");
    $.ajax({
        type: "POST",
        url: "/admin/getUser",
        data: {id:userId},
        success: (res) => {
            $.LoadingOverlay("hide");
            Swal.fire({
                title: "Deactivate Account",
                text: "Are you sure you want to deactivate " + res.name + " account?",
                icon: "question",
                showCancelButton: true,
                showConfirmButton: false,
                showDenyButton:true,
                denyButtonText: "Deactivate",
                iconColor:"#ea5455",
                willOpen: (e) => {
                    $(".swal2-actions").addClass("w-100").css("justify-content","flex-end");
                }
            }).then((result) => {
                if(result.isDenied){
                    $.ajax({
                        type: "POST",
                        url: "/admin/deactivateUser",
                        data: {id:userId},
                        success: (res) => {
                            userTable.ajax.reload(null, false);
                        }
                    });
                }
            });
        }
    });
});

$('#userTable').on('click', '.activateBtn', (e) => {
    let userId = $(e.currentTarget).data("id");
    $.ajax({
        type: "POST",
        url: "/admin/getUser",
        data: {id:userId},
        success: (res) => {
            $.LoadingOverlay("hide");
            Swal.fire({
                title: "Activate Account",
                text: "Are you sure you want to Activate " + res.name + " account?",
                icon: "question",
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: "Activate",
                iconColor:"#2b7d62",
                willOpen: (e) => {
                    $(".swal2-actions").addClass("w-100").css("justify-content","flex-end");
                }
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        type: "POST",
                        url: "/admin/deactivateUser",
                        data: {
                            id:userId,
                            status:"activate"
                        },
                        success: (res) => {
                            userTable.ajax.reload(null, false);
                        }
                    });
                }
            });
        }
    });
});


let memberTable = $('#memberTable').on('init.dt', function () {
    $(".dataTables_wrapper").prepend("<div class='dataTables_processing card font-weight-bold d-none' role='status'>Loading Please Wait...<i class='fa fa-spinner fa-spin text-warning'></i></div>");
}).DataTable({
    ordering: false,
    serverSide: true,
    dom: 'rtip',
    columnDefs: [
        { targets: 0, width: '1%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 1, width: '7%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 2, width: '7%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 3, width: '7%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 4, width: '15%', className: "text-left align-middle font-weight-bold p-2" },
        { targets: 5, width: '25%', className: "text-left align-middle font-weight-bold p-2" },
        { targets: 6, width: '3%', className: "text-center align-middle font-weight-bold p-2" },
    ],
    ajax: {
        url: '/admin/memberTable',
        type: 'POST',
        data: function (d) {
            d.filterSearch = $("#memberfilterSearch").val();
            d.filterMemberType = $("#memberTypeFilter").val();
            d.filterStatus = $("#statusFilter").val();
        },
        beforeSend: () => {
            $(".dataTables_processing").removeClass("d-none");
        },
        complete: () => {
            $(".dataTables_processing").addClass("d-none");
        }
    },
    rowCallback: function(row, data, index) {   
        let status = $(row).find(".editBtn").data("status");
        if(status == "updated"){
            $(row).addClass("bg-danger");
        }
    }
});

$("#memberTypeFilter,#statusFilter").change((e) => {
    memberTable.draw();
});

$("#memberfilterSearch").keyup((e) => {
    memberTable.draw();
});

$("#memberSearchBtn").click((e) => {
    memberTable.draw();
});

$("#memberClearFilter").click((e) => {
    $("#memberTypeFilter,#statusFilter,#memberfilterSearch").val("");
    memberTable.draw();
});

$("#memberAddBtn").click((e) => {
    $("#memberForm").find("input:not([name='updated_by'])").val("").attr("disabled", false);
    $("#memberForm").find("select").val("").attr("disabled", false);
    $("#memberForm").find("textarea").val("").attr("disabled", false).removeClass("font-weight-bold");
    $("#memberForm").find("select").trigger("change");
    $("#province,#city,#barangay").attr("disabled", true);
    $("#memberModalLabel").text("Add Member");
    $("#memberModal").modal("show");
});

$("#region").change((e) => {
    let region = $(e.currentTarget).val();
    if(region != ""){
        $.ajax({
            type: "POST",
            url: "/admin/getProvinces",
            data: {
                region_code:region
            },
            success: (res) => {
                $("#province").empty();
                $('#province').append("<option value=''> -- Select Province -- </option>");
                for(let provinceId in res){
                    let provinceObj = res[provinceId];
                    let option = $('<option>', {
                        value: provinceObj.province_code,
                        text: provinceObj.name
                        });
                        $('#province').append(option);
                }
                $("#province").attr("disabled", false);
                $("#province").trigger("change");
                $("#city").empty();
                $('#city').append("<option value=''> -- Select City -- </option>");
                $('#city').attr("disabled",true);
                $("#city").trigger("change");
                $("#barangay").empty();
                $('#barangay').append("<option value=''> -- Select Barangay -- </option>");
                $('#barangay').attr("disabled",true);
                $("#barangay").trigger("change");
                
                if($("#memberModalLabel").text() != "Add Member"){
                    if($('#province option[value="' + $("#province").data("province_code") + '"]').length > 0){
                        $('#province').val($("#province").data("province_code")).trigger("change");
                    }
                }
            }
        });
    }
});

$("#province").change((e) => {
    let province = $(e.currentTarget).val();
    if(province != ""){
        $.ajax({
            type: "POST",
            url: "/admin/getCities",
            data: {
                region_code:$("#region").val(),
                province_code:province

            },
            success: (res) => {
                $("#city").empty();
                $('#city').append("<option value=''> -- Select City -- </option>");
                for(let cityId in res){
                    let cityObj = res[cityId];
                    let option = $('<option>', {
                        value: cityObj.citymun_code,
                        text: cityObj.name
                      });
                      $('#city').append(option);
                }
                $("#city").attr("disabled", false);
                $("#city").trigger("change");
                $("#barangay").empty();
                $('#barangay').append("<option value=''> -- Select Barangay -- </option>");
                $('#barangay').attr("disabled",true);
                $("#barangay").trigger("change");

                if($("#memberModalLabel").text() != "Add Member"){
                    if($('#city option[value="' + $("#city").data("citymun_code") + '"]').length > 0){
                        $('#city').val($("#city").data("citymun_code")).trigger("change");
                    }
                }
            }
        });
    }
});

$("#city").change((e) => {
    let city = $(e.currentTarget).val();
    if(city != ""){
        $.ajax({
            type: "POST",
            url: "/admin/getBarangay",
            data: {
                region_code:$("#region").val(),
                province_code:$("#province").val(),
                citymun_code:city,

            },
            success: (res) => {
                $("#barangay").empty();
                $('#barangay').append("<option value=''> -- Select Barangay -- </option>");
                for(let barangayId in res){
                    let barangayObj = res[barangayId];
                    let option = $('<option>', {
                        value: barangayObj.brgy_code,
                        text: barangayObj.name
                      });
                      $('#barangay').append(option);
                }
                $("#barangay").attr("disabled", false);
                $("#barangay").trigger("change");

                if($("#memberModalLabel").text() != "Add Member"){
                    if($('#barangay option[value="' + $("#barangay").data("barangay_code") + '"]').length > 0){
                        $('#barangay').val($("#barangay").data("barangay_code")).trigger("change");
                    }
                }
            }
        });
    }
});

$("#memberModal").find(".modal-footer").find("button[type='submit']").click((e) => {
    $("#memberSubmitBtn").trigger("click");
});

$("#memberForm").submit((e) => {
    e.preventDefault();
    $.LoadingOverlay("show");
    let data = $(e.currentTarget).serializeArray();

    if($("#memberModalLabel").text() != "Add Member"){
        data.push({
            name: "member_type",
            value: $("#memberForm").find("select[name='member_type']").val()
        });
        data.push({
            name: "memid",
            value: $("#memberForm").find("input[name='memid']").val()
        });
        data.push({
            name: "pbno",
            value: $("#memberForm").find("input[name='pbno']").val()
        });
        data.push({
            name: "title",
            value: $("#memberForm").find("select[name='title']").val()
        });
        data.push({
            name: "firstname",
            value: $("#memberForm").find("input[name='firstname']").val()
        });
        data.push({
            name: "middlename",
            value: $("#memberForm").find("input[name='middlename']").val()
        });
        data.push({
            name: "lastname",
            value: $("#memberForm").find("input[name='lastname']").val()
        });
        data.push({
            name: "suffix",
            value: $("#memberForm").find("select[name='suffix']").val()
        });
        data.push({
            name: "full_address",
            value: $("#memberForm").find("textarea[name='full_address']").val()
        });
    }

    $.ajax({
        type: "POST",
        url: "/admin/createUpdateMember",
        data: data,
        success: (res) => {
            $.LoadingOverlay("hide");
            if(res.status == "failed"){
                Swal.fire({
                    title: "Oops...",
                    text: "Something went wrong!",
                    icon: "error",
                    confirmButtonText: "OK",
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            }else{
                $("#memberModal").modal("hide");
                Swal.fire({
                    title: "Successfully Saved.",
                    icon: res.status,
                    confirmButtonText: "OK",
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    memberTable.ajax.reload(null, false);
                });
            }
        }
    });
});

$('#memberTable').on('click', '.editBtn', (e) => {
    let memberId = $(e.currentTarget).data("id");
    $("#memberModalLabel").text("Update Member's Info");
    $("#region").val("").trigger("change");
    $("#province").val("").trigger("change");
    $("#city").val("").trigger("change");
    $("#barangay").val("").trigger("change");
    $("#province").removeAttr("data-province_code");
    $("#city").removeAttr("data-citymun_code");
    $("#barangay").removeAttr("data-barangay_code");

    $.LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url: "/admin/getMember",
        data: {id:memberId},
        success: (res) => {
            $.LoadingOverlay("hide");
            for(let key in res){
                if(key != "updated_by"){
                    $("#memberForm").find("[name='"+key+"']").val(res[key]).attr("disabled", true);
                }

            }
            $("#memberForm").find("textarea").addClass("font-weight-bold");
            $("#region").attr("disabled", false);

            if(res.region_code != null){
                $("#region").val(res.region_code).trigger("change");
            }

            if(res.province_code != null){
                $("#province").data("province_code", res.province_code);
            }
            
            if(res.citymun_code != null){
                $("#city").data("citymun_code", res.citymun_code);
            }

            if(res.barangay_code != null){
                $("#barangay").data("barangay_code", res.barangay_code);
            }
            
            $("#street,#subdivision,#area,#unitFloor").attr("disabled", false);
            $("#memberForm").find("input[type='hidden']").attr("disabled", false);
            $("#memberModal").modal("show");
        }
    });
});

let dependentTable = $('#dependent-memberTable').on('init.dt', function () {
    $(".dataTables_wrapper").prepend("<div class='dataTables_processing card font-weight-bold d-none' role='status'>Loading Please Wait...<i class='fa fa-spinner fa-spin text-warning'></i></div>");
}).DataTable({
    ordering: false,
    serverSide: true,
    dom: 'rtip',
    columnDefs: [
        { targets: 0, width: '1%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 1, width: '10%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 2, width: '10%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 3, width: '10%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 4, width: '30%', className: "text-left align-middle font-weight-bold p-2" },
        { targets: 5, width: '5%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 6, width: '5%', className: "text-center align-middle font-weight-bold p-2" },
        { targets: 7, width: '3%', className: "text-center align-middle font-weight-bold p-2" },
    ],
    ajax: {
        url: '/admin/dependentTable',
        type: 'POST',
        data: function (d) {
            d.filterSearch = $("#dependent-memberfilterSearch").val();
            d.filterMemberType = $("#dependent-memberTypeFilter").val();
        },
        beforeSend: () => {
            $(".dataTables_processing").removeClass("d-none");
        },
        complete: () => {
            $(".dataTables_processing").addClass("d-none");
        }
    }
});

$("#dependent-memberfilterSearch").keyup((e) => {
    dependentTable.draw();
});

$("#dependent-memberTypeFilter").change((e) => {
    dependentTable.draw();
});

$("#dependent-memberSearchBtn").click((e) => {
    dependentTable.draw();
});

$("#dependent-memberClearFilter").click((e) => {
    $("#dependent-memberTypeFilter,#dependent-memberfilterSearch").val("");
    dependentTable.draw();
});

var dependentBeneficiariesTable;

const dependentsBeneficiariesModal = (e,title) => {
    let memberName = $(e.currentTarget).data("membername");
    let memid = $(e.currentTarget).data("memid");
    let pbno = $(e.currentTarget).data("pbno");
    let member_id = $(e.currentTarget).data("id");

    $("#dependentModalLabel").text(title);
    $("#dependentModal").find("input[name='membername']").val(memberName);
    $("#dependentModal").find("input[name='memid']").val(memid);
    $("#dependentModal").find("input[name='pbno']").val(pbno);
    $("#dependentModal").find("input[name='member_id']").val(member_id);
    $("#dependentModal").find(".modalTableContainer").append("<div class='table-responsive'> <table id='dependentBeneficiariesTable' class='table table-hover table-bordered dataTable modalTable'> <thead> <tr> <th>ID</th> <th>Name</th> <th>Birthdate</th> <th>Contact No.</th> <th>Relationship</th> <th>Action</th> </tr></thead></table></div>");
    $("#dependentModal").modal("show");
}

$('#dependent-memberTable').on('click', '.dependents-editBtn', (e) => {
    let title = "List of dependents";
    dependentsBeneficiariesModal(e,title);
});

$('#dependent-memberTable').on('click', '.beneficiaries-editBtn', (e) => {
    let title = "List of beneficiaries";
    dependentsBeneficiariesModal(e,title);
});

$('#dependentModal').on('shown.bs.modal', function (e) {
    $("#dependentForm").find("input:not([name='membername']):not([name='memid']):not([name='pbno']):not([name='member_id']):not([name='created_by'])").val("");
    $("#dependentForm").find("select").val("").trigger('change');
    $("#dependentForm").find("input[name='firstname']").focus();

    dependentBeneficiariesTable = $('#dependentBeneficiariesTable').on('init.dt', function () {
        $(".dataTables_wrapper").prepend("<div class='dataTables_processing card font-weight-bold d-none' role='status'>Loading Please Wait...<i class='fa fa-spinner fa-spin text-warning'></i></div>");
    }).DataTable({
        ordering: false,
        serverSide: true,
        dom: 'rtip',
        pageLength: 5,
        bInfo: false,
        columnDefs: [
            { targets: 0, width: '1%', className: "text-center align-middle font-weight-bold" },
            { targets: 1, width: '25%', className: "text-left align-middle font-weight-bold" },
            { targets: 2, width: '8%', className: "text-center align-middle font-weight-bold" },
            { targets: 3, width: '8%', className: "text-center align-middle font-weight-bold" },
            { targets: 4, width: '20%', className: "text-left align-middle font-weight-bold" },
            { targets: 5, width: '3%', className: "text-center align-middle font-weight-bold" },
        ],
        ajax: {
            url: '/admin/dependentBeneficiariesTable',
            type: 'POST',
            data: function (d) {
                d.action = $("#dependentModalLabel").text() == "List of dependents" ? "dependents" : "beneficiaries";
                d.memid = $("#dependentForm").find("input[name='memid']").val();
                d.pbno = $("#dependentForm").find("input[name='pbno']").val();
            },
            beforeSend: () => {
                $(".dataTables_processing").removeClass("d-none");
            },
            complete: () => {
                $(".dataTables_processing").addClass("d-none");
            }
        }
    });
    
    $('#dependentBeneficiariesTable').on('click', '.editBtn', (e) => {
        $.LoadingOverlay("show");
        $.ajax({
            type: "POST",
            url: "/admin/getDependentBeneficiary",
            data: {
                id: $(e.currentTarget).data("id"),
                action: $("#dependentModalLabel").text() == "List of dependents" ? "dependents" : "beneficiaries" 
            },
            success: (res) => {
                for(let key in res){
                    $("#dependentForm").find("input[name='"+key+"']").val(res[key]);
                    $("#dependentForm").find("select[name='"+key+"']").val(res[key]).trigger("change");
                }
                $.LoadingOverlay("hide");
            }
        });
    });

    $('#dependentBeneficiariesTable').on('click', '.deleteBtn', (e) => {
        $("#dependentForm").find("input:not([name='membername']):not([name='memid']):not([name='pbno']):not([name='member_id']):not([name='created_by'])").val("");
        $("#dependentForm").find("select").val("").trigger('change');
        
        $.LoadingOverlay("show");
        let action = $("#dependentModalLabel").text() == "List of dependents" ? "dependent" : "beneficiary";
        $.ajax({
            type: "POST",
            url: "/admin/getDependentBeneficiary",
            data: {
                id: $(e.currentTarget).data("id"),
                action: $("#dependentModalLabel").text() == "List of dependents" ? "dependents" : "beneficiaries" 
            },
            success: (res) => {
                $.LoadingOverlay("hide");
                let name = res.firstname+" "+res.lastname; 
                Swal.fire({
                    title: action == "dependent" ? "Remove Dependent" : "Remove Beneficiary",
                    text: "Are you sure you want to remove " + name + " as a "+ action +"?",
                    icon: "question",
                    showCancelButton: true,
                    showConfirmButton: false,
                    showDenyButton: true,
                    denyButtonText: "Remove",
                    iconColor: "#ea5455",
                    willOpen: (e) => {
                        $(".swal2-actions").addClass("w-100").css("justify-content","flex-end");
                    }
                }).then((result) => {
                    if(result.isDenied){
                        $.LoadingOverlay("show");
                        $.ajax({
                            type: "POST",
                            url: "/admin/deleteDependentBeneficiary",
                            data:{
                                id:res.id,
                                action: $("#dependentModalLabel").text() == "List of dependents" ? "dependents" : "beneficiaries"
                            },
                            success: (res) => {
                                $.LoadingOverlay("hide");
                                dependentBeneficiariesTable.ajax.reload(null, false);
                            }
                        });
                    }
                });
            }
        });
    });
});

$('#dependentModal').on('hidden.bs.modal', function (e) {
    $("#dependentModal").find(".modalTableContainer").find(".table-responsive").remove();
    dependentTable.ajax.reload(null, false);
});

$("#dependentForm").submit((e) => {
    e.preventDefault();
    $.LoadingOverlay("show");
    let data = $(e.currentTarget).serializeArray();
    data.push({
        name: "action",
        value: $("#dependentModalLabel").text() == "List of dependents" ? "dependents" : "beneficiaries"
    });
    $.ajax({
        type: "POST",
        url: "/admin/createDependentBeneficiary",
        data: data,
        success: (res) => {
            $.LoadingOverlay("hide");
            dependentBeneficiariesTable.ajax.reload(null, false);
            $("#dependentForm").find("input:not([name='membername']):not([name='memid']):not([name='pbno']):not([name='member_id']):not([name='created_by'])").val("");
            $("#dependentForm").find("select").val("").trigger('change');
            $("#dependentForm").find("input[name='firstname']").focus();
        }
    });
});