$("#menu").click(function () {
    $(".layer").addClass("layer_show");
    $("#aside").addClass("ul_show");
});

$(".layer").click(function () {
    $("#aside").removeClass("ul_show");
    $(".layer").removeClass("layer_show");
});

try {
    tippy("[data-tippy-content]", {
        onShow(instance) {
            setTimeout(() => {
                instance.hide(); // Hide the tooltip after 2 seconds
            }, 1000);
        },
    });
} catch (error) {}

function getIds() {
    let ids = [];

    $(".option.selected").each(function () {
        ids.push($(this).attr("id"));
    });

    return ids;
}

function copy(TextToCopy) {
    navigator.clipboard.writeText(TextToCopy);
    Swal.fire({
        position: "center",
        icon: "success",
        title: "Successfully copied",
        showConfirmButton: false,
        timer: 700,
    });
}

function GetOrderDetails(id, type = "admin") {
    $.ajax({
        url: `/${type}/orders/GetOrderDetailsAjax/${id}`,
        type: "GET",
        dataType: "json",

        beforeSend() {
            $("#loader").addClass("d-flex");
            $("#loader").removeClass("d-none");
        },

        success: function (response) {
            $("#loader").addClass("d-none");
            $("#loader").removeClass("d-flex");

            if (response.status == "success") {
                let data = response.data;

                $(".frameContent #reference").text("#" + data.reference);

                $(".frameContent #status").text(data.status);

                $(".frameContent #status").removeClass();
                $(".frameContent #status")
                    .addClass("orderStatus")
                    .addClass(data.class);

                let cartona = ``;

                for (const detail of data.details) {
                    cartona += `
                      <tr>
                      <td><img class="prodcut_img"
                              src="${detail.img}"
                              alt="${detail.discription}"></td>

                      <td> ${detail.discription} </td>
                      <td>${detail.qnt}</td>

                  </tr>`;

                    $("#frameTable").html(cartona);
                }

                $("#frame").removeClass();
                $("#frame").addClass("frame").addClass("d-flex");
            }
        },
        error: function () {
            $("#loader").addClass("d-none");
            $("#loader").removeClass("d-flex");
        },
    });
}

$("#frame").click(function () {
    $("#frame").removeClass();
    $("#frame").addClass("frame").addClass("d-none");
});

function check(form) {
    var allFilled = true;

    try {
        form.find(".checkThis").each(function () {
            if ($(this).hasClass("modelSelect")) {
                if (
                    $(this).select2("data")[0] == undefined ||
                    $(this).select2("data")[0].id === ""
                ) {
                    allFilled = false;
                    return false;
                }
            } else if ($(this).hasClass("select-dropdown")) {
                var selectizeControl = $(".select-dropdown.checkThis")[0]
                    .selectize;
                var selectedValues = selectizeControl.getValue();

                if (selectedValues.length == 0) {
                    allFilled = false;
                    return false;
                }
            } else if ($(this).is(":checkbox") && !$(this).is(":checked")) {
                allFilled = false;
                return false;
            } else {
                if ($(this).val().trim() === "") {
                    allFilled = false;
                    return false;
                }
            }
        });
    } catch (error) {
        console.log("error in valiadtion");
    }

    form.find("#submitBtn").prop("disabled", !allFilled);
}

$(".checkThis").on("keyup", function () {
    check($(this).closest("form"));
});

function checkAllForms() {
    $("form").each(function () {
        check($(this));
    });
}

function checkUseDate() {
    check($("input.date").closest("form"));
}

$(document).ready(function () {
    checkAllForms();
});

var confirmCheckBox = document.getElementById("confirmCheckBox");
if (confirmCheckBox) {
    confirmCheckBox.addEventListener("change", function () {
        var button = document.getElementById("deleteBtn");
        if (this.checked) {
            button.removeAttribute("disabled");
        } else {
            button.setAttribute("disabled", "disabled");
        }
    });
}

function delete_model(e) {
    var confirmCheckBox = document.getElementById("confirmCheckBox");
    confirmCheckBox.checked = false;

    var button = document.getElementById("deleteBtn");
    button.setAttribute("disabled", "true");

    event.stopPropagation();
    let element = e;
    let data_name = element.getAttribute("data-name");
    let data_id = element.getAttribute("data-id");
    $("#delete_title").text(data_name);
    $("#delete_id").val(data_id);
}

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
