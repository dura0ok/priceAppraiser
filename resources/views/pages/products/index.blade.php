@extends("layouts.base.template")
@push("styles")
    <link href="https://unpkg.com/tabulator-tables@4.9.3/dist/css/tabulator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
@endpush

@section("content")
    <h1 class="section_name">Товары @if(!is_null($catalog))|| {{ $catalog->name }}@endif</h1>
    <div class="filter">
        <button id="filter-clear">Очистить фильтр</button>
        <select id="filter-field">
            <option></option>
            <option value="articul">Артикул</option>
            <option value="name">Имя</option>
            <option value="description">Описание</option>
            <option value="price">Цена</option>
        </select>

        <select id="filter-type">
            <option value="=">=</option>
            <option value="<"><</option>
            <option value="<="><=</option>
            <option value=">">></option>
            <option value=">=">>=</option>
            <option value="!=">!=</option>
            <option value="like">like</option>
        </select>

        <input id="filter-value" type="text" placeholder="Значение фильтра">
    </div>
    <div class="products">
        <div id="table"></div>
    </div>
@endsection

@push("scripts")
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.9.3/dist/js/tabulator.min.js"></script>
    <script>
        const catalog_id = {{ $catalog->id ?? 'undefined' }}
        const fieldEl = document.getElementById("filter-field");
        const typeEl = document.getElementById("filter-type");
        const valueEl = document.getElementById("filter-value");


        const printIcon = function(cell, formatterParams, onRendered){ //plain text value
            return "<i class='fa fa-edit'></i>";
        };

        function updateFilter(){
            const filterVal = fieldEl.options[fieldEl.selectedIndex].value;
            const typeVal = typeEl.options[typeEl.selectedIndex].value;

            typeEl.disabled = false;
            valueEl.disabled = false;

            if(filterVal){
                table.setFilter(filterVal,typeVal, valueEl.value);
            }
        }

        document.getElementById("filter-field").addEventListener("change", updateFilter);
        document.getElementById("filter-type").addEventListener("change", updateFilter);
        document.getElementById("filter-value").addEventListener("keyup", updateFilter);

        //Clear filters on "Clear Filters" button click
        document.getElementById("filter-clear").addEventListener("click", function(){
            fieldEl.value = "";
            typeEl.value = "=";
            valueEl.value = "";

            table.clearFilter();
        });
        sizes = [];
        for(let i = 20; i < 1000; i+=20){
            sizes.push(i)
        }
        const table = new Tabulator("#table", {
            pagination: "remote", //enable remote pagination
            ajaxURL: "{{ route("products.getProducts") }}", //set url for ajax request
            ajaxParams: {catalog_id: catalog_id}, //set any standard parameters to pass with the request
            paginationSize: 10, //optional parameter to request a certain number of rows per page
            paginationSizeSelector: sizes,
            layout: "fitColumns",
            columns: [
                {title: "Артикул", field: "articul", editor: "input"},
                {title: "Имя", field: "name", editor: "input"},
                {title: "Описание", field: "description", width: 500, editor: "textarea"},
                {title: "Цена", field: "price", editor: "input"},
                {
                    title: "Картинка", align: "center",
                    formatter: function (cell, formatterParams, onRendered) {
                        if(cell.getRow().getData().image != null){
                            const link = '{{ asset("storage") }}/' + cell.getRow().getData().image
                            return "<a class='click' href='" + link + "' target='_blank'>Клик</a>";
                        }
                        return "Картинки нет"
                    },
                },
                {
                    title: "Каталоги",
                    field: "catalogs",
                    formatter: function (cell, formatterParams, onRendered) {
                        return cell.getValue().map(e => e.name).join(","); //return the contents of the cell;
                    },
                },

                {
                    formatter: "buttonCross", width: 40, align: "center", cellClick: function (e, cell) {
                        let deleteURL = `{{ route("products.destroy", ":id") }}`;
                        deleteURL = deleteURL.replace(":id", cell.getRow().getData().id)
                        console.log(deleteURL)
                        fetch(deleteURL, {
                            method: "DELETE",
                            credentials: "include",
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({"id": cell.getRow().getData().id})
                        })
                            .then(({json}) => json())
                            .then(data => {
                                console.log(data)
                                alert(data.message)
                            })
                        cell.getRow().delete();
                    }
                },
                {
                    formatter: printIcon, width: 40, align: "center", cellClick: function (e, cell) {
                        let editURL = '{{ route("products.edit", ":id") }}';
                        editURL = editURL.replace(":id", cell.getRow().getData().id);
                        window.open(editURL, '_blank');
                    }
                },
            ],
            cellEdited: function (cell) {
                let data = cell.getRow().getData()
                data.req = "api";
                let updateURL = "{{ route("products.update", ":id") }}";
                updateURL = updateURL.replace(":id", data.id)
                fetch(updateURL, {
                    method: "PUT",
                    credentials: "include",
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                    .then(r => r.json())
                    .then(data => {
                        console.log(data)
                    });
            },
        });
    </script>
@endpush
