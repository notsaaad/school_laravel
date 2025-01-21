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

$("#flip-users").click(function () {
    $("#panel-users").slideToggle(200);
    $("#flip-users span.menu-arrow ").toggleClass("down");
});
$("#flip-trader").click(function () {
    $("#panel-trader").slideToggle(200);
    $("#flip-trader span.menu-arrow ").toggleClass("down");
});

$("#flip-clothes").click(function () {
    $("#panel-clothes").slideToggle(200);
    $("#flip-clothes span.menu-arrow ").toggleClass("down");
});

$("#flip-sec1").click(function () {
    $("#panel-sec1").slideToggle(200);
    $("#flip-sec1 span.menu-arrow ").toggleClass("down");
});
$("#flip-warehouses").click(function () {
    $("#panel-warehouses").slideToggle(200);
    $("#flip-warehouses span.menu-arrow ").toggleClass("down");
});

$("#flip-products").click(function () {
    $("#panel-products").slideToggle(200);
    $("#flip-products span.menu-arrow ").toggleClass("down");
});

$("#flip-orders").click(function () {
    $("#panel-orders").slideToggle(200);
    $("#flip-orders span.menu-arrow ").toggleClass("down");
});
$("#flip-buses").click(function () {
    $("#panel-buses").slideToggle(200);
    $("#flip-buses span.menu-arrow ").toggleClass("down");
});

$("#flip-definitions").click(function () {
    $("#panel-definitions").slideToggle(200);
    $("#flip-definitions span.menu-arrow ").toggleClass("down");
});
$("#flip-applications").click(function () {
    $("#panel-applications").slideToggle(200);
    $("#flip-applications span.menu-arrow ").toggleClass("down");
});
$("#flip-expeness").click(function () {
    $("#panel-expeness").slideToggle(200);
    $("#flip-expeness span.menu-arrow ").toggleClass("down");
});

$(".checkThis").on("keyup", function () {
    check($(this).closest("form"));
});

function search() {
    var currentUrl = window.location.href;

    if (currentUrl.indexOf("export=yes") === -1) {
        if (currentUrl.indexOf("?") !== -1) {
            currentUrl = currentUrl + "&export=yes";
        } else {
            currentUrl = currentUrl + "/search?export=yes";
        }
        window.location.href = currentUrl;
    } else {
        window.location.href = currentUrl;
    }
}

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
    } catch (error) {}

    form.find("#submitBtn").prop("disabled", !allFilled);
}

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

$(document).on("click", function (e) {
    var container = $(".select2-container--open");
    if (
        container.length > 0 &&
        !container.is(e.target) &&
        container.has(e.target).length === 0
    ) {
        $(".js-example-basic-single").select2("close");
    }
});

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

function changeColor(colorName) {
    let color;
    let xbuttonBorder;
    let mainBg;

    if (colorName == "عنبري") {
        color = "rgb(245 158 11 /1)";
        xbuttonBorder = "rgb(180 83 9 /1)";
        mainBg = "rgb(245 158 11 /1)";
    } else if (colorName == "أزرق") {
        color = "rgb(59 130 246 /1)";
        xbuttonBorder = "rgb(29 78 216  /1)";
        mainBg = "rgb(59 130 246 /1)";
    } else if (colorName == "بنفسجي") {
        color = "rgb(79, 70, 156)";
        xbuttonBorder = "#2e238d";
        mainBg = "#4F469C";
    } else if (colorName == "سماوي مفتح") {
        color = "rgb(14 116 144 /1)";
        xbuttonBorder = "rgb(14 116 144 /1)";
        mainBg = "rgb(6 182 212 /1)";
    } else if (colorName == "زمردي") {
        color = "rgb(16 185 129 /1)";
        xbuttonBorder = "rgb(4 120 87  /1)";
        mainBg = "rgb(16 185 129 /1)";
    } else if (colorName == "رمادي") {
        color = "rgb(107 114 128/1)";
        xbuttonBorder = "rgb(55 65 81   /1)";
        mainBg = "rgb(107 114 128/1)";
    } else if (colorName == "زهري") {
        color = "rgb(236 72 153 /1)";
        xbuttonBorder = "rgb(190 24 93 /1)";
        mainBg = "rgb(236 72 153/1)";
    } else if (colorName == "أرجواني") {
        color = "rgb(168 85 247 /1)";
        xbuttonBorder = "rgb(126 34 206  /1)";
        mainBg = "rgb(168 85 247/1)";
    } else if (colorName == "احمر") {
        color = "rgb(239 68 68 /1)";
        xbuttonBorder = "rgb(185 28 28 /1)";
        mainBg = "rgb(239 68 68 /1)";
    } else if (colorName == "أخضر") {
        color = "rgb(34 197 94 /1)";
        xbuttonBorder = "rgb(21 128 61 /1)";
        mainBg = "rgb(34 197 94 /1)";
    } else if (colorName == "ليمي") {
        color = "rgb(132 204 22 /1)";
        xbuttonBorder = "rgb(77 124 15 /1)";
        mainBg = "rgb(132 204 22 /1)";
    } else if (colorName == "برتقالي") {
        color = "rgb(249 115 22 /1)";
        xbuttonBorder = "rgb(194 65 12/1)";
        mainBg = "rgb(249 115 22/1)";
    } else if (colorName == "سماوي") {
        color = "rgb(14 165 233 /1)";
        xbuttonBorder = "rgb(3 105 161/1)";
        mainBg = "rgb(14 165 233/1)";
    } else if (colorName == "شرشيري") {
        color = "rgb(20 184 166 /1)";
        xbuttonBorder = "rgb(15 118 110/1)";
        mainBg = "rgb(20 184 166/1)";
    } else if (colorName == "أصفر") {
        color = "rgb(234 179 8 /1)";
        xbuttonBorder = "rgb(161 98 7/1)";
        mainBg = "rgb(234 179 8 /1)";
    } else if (colorName == "اخضر غامق") {
        mainBg = "#335b54";
    } else if (colorName == "اسود") {
        mainBg = "#1b2225";
    }

    let rootStyles = document.documentElement.style;

    rootStyles.setProperty("--mainColor", `${color}`);
    localStorage.setItem("color", `${color}`);

    rootStyles.setProperty("--mainBg", `${mainBg}`);
    localStorage.setItem("mainBg", `${mainBg}`);
}

function toggleColors() {
    let colors = document.getElementById("colors");
    colors.classList.toggle("colorsShow");
}

const toolbarOptions = [
    [{ header: 1 }, { header: 2 }],
    ["bold", "italic", "underline", "strike", "clean"],
    [
        { align: "" },
        { align: "center" },
        { align: "right" },
        { align: "justify" },
    ],
    [{ list: "ordered" }, { list: "bullet" }],
    ["link"],
    [{ color: [] }, { background: [] }],
    [
        { size: ["14px", "16px", "18px", "20px", "22px", "25px"] }, // Use pixel sizes directly
    ],
];

var Size = Quill.import("attributors/style/size");
Size.whitelist = ["14px", "16px", "18px", "20px", "22px", "25px"];
Quill.register(Size, true);

let quills = document.getElementsByClassName("quill");

for (let i = 0; i < quills.length; i++) {
    var quill = new Quill(quills[i], {
        theme: "snow",
        modules: {
            toolbar: toolbarOptions,
        },
        formats: [
            "bold",
            "align",
            "italic",
            "underline",
            "strike",
            "header",
            "link",
            "list",
            "color",
            "background",
            "font",
            "size",
        ],
    });

    quill.format("font", "cairo");
    quill.format("align", "right");
}

document.addEventListener("DOMContentLoaded", () => {
    const links = document.getElementById("links");

    function showScrollbar() {
        links.classList.add("show-scrollbar");
    }

    function hideScrollbar() {
        links.classList.remove("show-scrollbar");
    }

    links.addEventListener("mouseenter", showScrollbar);
    links.addEventListener("mouseleave", hideScrollbar);

    links.addEventListener("scroll", () => {
        showScrollbar();
        clearTimeout(links.hideScrollbarTimeout);
        links.hideScrollbarTimeout = setTimeout(() => {
            if (!links.matches(":hover")) {
                hideScrollbar();
            }
        }, 1000);
    });
});

$("#collape_btn").click(function () {
    $("nav").toggleClass("nav_collapse");
    $("#content").toggleClass("content_collapse");
    $("aside").toggleClass("aside_collapse");
    $("#collape_btn").toggleClass("collapse");

    // Save the current collapse state in localStorage
    localStorage.setItem("navCollapsed", $("nav").hasClass("nav_collapse"));
    localStorage.setItem(
        "contentCollapsed",
        $("#content").hasClass("content_collapse")
    );
    localStorage.setItem(
        "asideCollapsed",
        $("aside").hasClass("aside_collapse")
    );
    localStorage.setItem(
        "btnCollapsed",
        $("#collape_btn").hasClass("collapse")
    );
});

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

                $(".frameContent #reference").text(data.reference);
                $(".frameContent #name").text(data.name);
                $(".frameContent #mobile").text(data.phone);
                $(".frameContent #city").text(data.city);

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

$("#TestingFream").click(function () {
    $("#TestingFream").removeClass();
    $("#TestingFream").addClass("frame").addClass("d-none");
});

function showMessage(message) {
    Swal.fire(message);
}

function markAsReadAndRedirect(element, notificationId, link) {
    // Mark as read (send an AJAX request to mark the notification as read)
    fetch(`/mark-as-read/${notificationId}`, {
        method: "get",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}", // Add CSRF token for security
        },
    })
        .then((response) => {
            if (response.ok) {
                // Redirect to the link
                window.location.href = "/" + link;
            } else {
                console.error("Failed to mark notification as read.");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function clearSearch() {
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);

    // Save the value of 'add_new_product' if it exists
    let addNewProduct = params.get("add_new_product");

    // Clear all query parameters
    params = new URLSearchParams();

    // Restore 'add_new_product' parameter if it was present
    if (addNewProduct) {
        params.set("add_new_product", addNewProduct);
    }

    // Set the new search parameters
    url.search = params.toString();

    // Remove '/search' from the pathname if it exists
    if (url.pathname.includes("/search")) {
        url.pathname = url.pathname.replace("/search", "");
    }

    // Redirect to the new URL
    window.location.href = url.toString();
}

function copy(TextToCopy) {
    navigator.clipboard.writeText(TextToCopy);
    toastr["success"]("تم النسخ");
}

function applyDarkMode() {
    const darkModeEnabled = localStorage.getItem("dark") === "true";
    const aside = document.getElementById("aside");
    const checkbox = document.getElementById("dark_light_switch");

    if (darkModeEnabled) {
        aside.classList.add("dark");
        checkbox.checked = true;
        document.body.classList.add("dark");
    } else {
        aside.classList.remove("dark");
        checkbox.checked = false;
        document.body.classList.remove("dark");
    }
}

function toggleDarkMode() {
    const aside = document.getElementById("aside");
    const checkbox = document.getElementById("dark_light_switch");
    const darkModeEnabled = checkbox.checked;

    if (darkModeEnabled) {
        aside.classList.add("dark");
        document.body.classList.add("dark-mode");
        localStorage.setItem("dark", "true");
    } else {
        aside.classList.remove("dark");
        document.body.classList.remove("dark-mode");
        localStorage.setItem("dark", "false");
    }
}

applyDarkMode();

// Add event listener
document
    .getElementById("dark_light_switch")
    .addEventListener("change", toggleDarkMode);

// let btn = document.getElementById("test");

// btn.addEventListener("click", function () {
//     alert("hello");
// });

function success_with_sound(message) {
    toastr["success"](message);
    var audio = document.getElementById("successSound");
    audio.play();
}

function error_with_sound(message) {
    toastr["error"](message);
    var audio = document.getElementById("errorSound");
    audio.play();
}

$(".modelSelect").on("change", function () {
    checkAllForms();
});
