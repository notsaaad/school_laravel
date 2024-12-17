@props(['model', 'type'])


<style>
    #loader {
        display: none;
        align-items: center;
        justify-content: center;
        color: var(--mainBg);
        font-size: 40px;
    }
</style>

<div id="loader">
    <i class="fa-solid fa-spinner fa-spin"></i>
</div>


<script>
    function UpdateOrder() {

        const promises = [];


        let ids = $('.ids')

        for (let i = 0; i < ids.length; i++) {

            let id = ids[i].value;


            @if (isset($type))
                const promise = fetch(`/{{ $type }}/{{ $model }}/changeOrder?id=${id}&order=${i}`);
            @else
                const promise = fetch(`/admin/{{ $model }}/changeOrder?id=${id}&order=${i}`);
            @endif
            promises.push(promise);

        }

        $("#loader").addClass("d-flex");
        $("#loader").removeClass("d-none");


        Promise.all(promises)
            .then((responses) => {
                $("#loader").addClass("d-none");
                $("#loader").removeClass("d-flex");

                Swal.fire({
                    title: 'تم',
                    text: "تم تغيير الترتيب بنجاح",
                    icon: 'success',
                    confirmButtonText: 'فهمت',
                    preConfirm: function() {
                        location.reload();
                    }
                }).then((result) => {
                    location.reload();
                });


            })

            .catch((err) => {
                console.error(err);
            });


    }
</script>


<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
    $(function() {
        $("#sortable-table tbody").sortable({
            axis: "y",
            helper: function(event, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            },
            start: function(event, ui) {
                ui.helper.css('width', 'auto');

            },

            update: function(event, ui) {
                var order = $(this).sortable("toArray");

                for (var i = 0; i < order.length; i++) {
                    var rowIndex = order[i].substr(8); // remove 'sortable' prefix
                    $(this).append($("#sortable_" + rowIndex));
                }
            }
        }).disableSelection();
    });
</script>
