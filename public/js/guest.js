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
            }
        });
    }else{

    }
});