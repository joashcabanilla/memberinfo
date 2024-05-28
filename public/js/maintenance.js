let csvData = [];

const insertData = (counter, total, status) => {
    let batchInsert = parseInt($("#batchInsert").val());
    let table = $("#databaseTable").val();
    let totalkeys = Object.keys(csvData[0]).length;

    let alert = {
        title: "Done Processing",
        icon: "success",
        error: {}
    };
    if (counter <= total && status == "success") {
        setTimeout(() => {
            let batchData = [];
            let batchCtr = counter;
            for (let ctr = 0; ctr < batchInsert; ctr++) {
                if (csvData[batchCtr] != undefined && Object.keys(csvData[batchCtr]).length == totalkeys) {
                    batchData.push(csvData[batchCtr]);
                    batchCtr++;
                }
            }

            let data = {
                table: table,
                insert: batchData
            };
            $.ajax({
                type: "POST",
                url: "/admin/batchInsertData",
                data: data,
                async: false,
                success: (res) => {
                    if (res.status == "failed") {
                        alert.title = "Error Occurred";
                        alert.icon = "error";
                        alert.error = res.error;
                    }
                    let percent = parseInt((counter / total) * 100);
                    $(".container-progress").find("h4").text(counter + " / " + total);
                    $(".container-progress").find(".progress-bar").text(percent + "%").css("width", percent + "%");
                    insertData(counter + batchInsert, total, res.status);
                }
            });
        }, 300);

    } else {
        if (alert.icon == "error") {
            console.log(alert);
        }
        $(".container-progress").find(".progress-bar").text("100%").css("width", "100%");
        Swal.fire({
            title: alert.title,
            icon: alert.icon,
            confirmButtonText: "OK",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            $("#databaseTable").attr("disabled", false);
            $("#batchInsert").attr("disabled", false);
            $("#tableFile").attr("disabled", false);
            $("#importDatabaseForm").find("button").attr("disabled", false).removeClass("btn-success").addClass("btn-primary").html("Submit");
            $("#tableFile").val("").next().text("Upload Excel File");
            $("#importDatabaseForm").find("button").addClass("d-none");
            $(".container-progress").addClass("d-none");
            $("#databaseTable").val("");
            $("#batchInsert").val("");
        });
    }
}

$("#tableFile").change((e) => {
    let file = e.target.files[0];
    if (file) {
        $(e.currentTarget).next().text(file.name);
        Papa.parse(file, {
            header: true,
            complete: function (results) {
                csvData = results.data;
                $("#importDatabaseForm").find("button").removeClass("d-none");
                $(".container-progress").removeClass("d-none");
                $(".container-progress").find("h4").text("0 / " + csvData.length);
                $(".container-progress").find(".progress-bar").text("0%").css("width", "0%");
            }
        });
    } else {
        $(e.currentTarget).next().text("Upload Excel File");
        if (!$("#importDatabaseForm").find("button").hasClass("d-none")) {
            $("#importDatabaseForm").find("button").addClass("d-none");
        }
    }
});

$("#importDatabaseForm").submit((e) => {
    e.preventDefault();
    $("#databaseTable").attr("disabled", true);
    $("#batchInsert").attr("disabled", true);
    $("#tableFile").attr("disabled", true);
    $("#importDatabaseForm").find("button").attr("disabled", true).removeClass("btn-primary").addClass("btn-success").html("<i class='fa fa-spinner fa-spin text-warning'></i> Inserting Data...");
    insertData(0, csvData.length, "success");
});

$("#selectReport").change((e) => {
    let report = $(e.currentTarget).val();
    $("#selectUser").attr("disabled", false);
    if(report == "ListOfMembersDuplicate" || report == "ListOfMembersWithUpdatedAddress"){
        $("#selectUser").attr("disabled", true);
    }
});