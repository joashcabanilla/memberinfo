$("#loginForm").submit((e) => {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "login",
        data: $(e.currentTarget).serializeArray(),
        success: (res) => {
            if(res.status == "failed"){
                $(".error-text").removeClass("d-none").text(res.message);
                setTimeout(() => {
                    $(".error-text").addClass("d-none");
                },3000);
            }else{
                location.reload();
            }
        }
    });
});

$("#showPassword").change((e)  => {
    if($(e.currentTarget).is(":checked")){
        $("#password").attr("type", "text");
    }else{
        $("#password").attr("type", "password");
    }
});

$("#emailForm").submit((e) => {
    e.preventDefault();
    if($(e.currentTarget).find("button").text() == "Search"){
        $.ajax({
            type: "POST",
            url: "searchMember",
            data: $(e.currentTarget).serializeArray(),
            success: (res) => {
                if(res.status == "success"){
                    $(e.currentTarget).find("input").attr("readonly", true);
                    $(e.currentTarget).find(".labelEmail").removeClass("d-none");
                    $(e.currentTarget).find(".labelEmail").next().removeClass("d-none");
                    $(e.currentTarget).find("input[name='email']").attr("required", true).removeAttr('readonly').focus();
                    $(e.currentTarget).find("input[name='id']").val(res.member.id);
                    $(e.currentTarget).find("button").text("Save");
                }else{
                    $(".searchMember-error").removeClass("d-none").text(res.error);
                    setTimeout(() => {
                        $(".searchMember-error").addClass("d-none");
                    },3000);
                }
            }
        });
    }else{
        $.ajax({
            type: "POST",
            url: "updateEmail",
            data: $(e.currentTarget).serializeArray(),
            success: (res) => {
                if(res.status == "success"){
                    Swal.fire({
                        title: "Successfully Saved.",
                        icon: res.status,
                        confirmButtonText: "OK",
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        $(e.currentTarget).find("input").val("").removeAttr("readonly");
                        $(e.currentTarget).find(".labelEmail").addClass("d-none");
                        $(e.currentTarget).find(".labelEmail").next().addClass("d-none");
                        $(e.currentTarget).find("input[name='email']").removeAttr("required");
                        $(e.currentTarget).find("input[name='pbno_memid']").focus();
                        $(e.currentTarget).find("button").text("Search");
                    });
                }else{
                    $(".searchMember-error").removeClass("d-none").text(res.error.email[0]);
                    setTimeout(() => {
                        $(".searchMember-error").addClass("d-none");
                    },3000);
                }
            }
        });
    }
});