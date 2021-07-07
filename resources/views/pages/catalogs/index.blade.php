@extends("layouts.base.template")

@section("content")
    <h1 class="section_name">Каталоги</h1>
    <div class="catalogs">
        <button class="green_btn create_catalog">Создать Каталог</button>
        @foreach($catalogs as $catalog)
            <div class="catalog">
                <h1>{{ $catalog->name }}</h1>
                <a href="{{ route("products.index", ["catalog_id" => $catalog->id]) }}">Посмотреть продукты</a>
                <a href="" class="edit" data-id="{{ $catalog->id }}">Редактировать имя</a>
                <a href="" class="delete" data-id="{{ $catalog->id }}"> Удалить</a>
            </div>
        @endforeach
    </div>
    <input type="hidden" id="storeURL" value="{{ route("catalogs.store") }}">
    <input type="hidden" id="updateUrl" value="{{ route("catalogs.update") }}">
    <input type="hidden" id="deleteUrl" value="{{ route("catalogs.destroy") }}">
@endsection

@push("scripts")
    <script>
        function renderErrors(errors){
            let message = ""
            for (const [key, value] of Object.entries(errors)) {
                message += `${value}\n`
            }
            return message
        }

        window.onload = function() {
            document.querySelectorAll(".edit").forEach(function(item){
                item.addEventListener("click", edit)
            });
            document.querySelectorAll(".delete").forEach(function(item){
                item.addEventListener("click", remove)
            });
            document.querySelector(".create_catalog").addEventListener("click", store)
        }

        function store(e){
            e.preventDefault()
            let name = prompt("Введите имя вашего каталога", "")
            if(name === ""){
                alert("Ошибка, введена пустая строка")
                return
            }
            fetch(document.querySelector("#storeURL").value, {
                method: "POST",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({"name": name})
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.hasOwnProperty('errors')) {
                        alert("Успешно")
                        document.location.reload()
                        return
                    }

                    alert(renderErrors(data["errors"]))

                });
        }

        function edit(e){
            e.preventDefault()
            let id = e.target.getAttribute("data-id")
            let name = prompt("На какое название вы хотие изменить?", "");
            if(name === ""){
                alert("Ошибка, введена пустая строка")
                return
            }
            fetch(document.querySelector("#updateUrl").value, {
                method: "PUT",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({"id": id, "name": name})
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.hasOwnProperty('errors')) {
                        alert("Успешно")
                        document.location.reload()
                        return
                    }

                    alert(renderErrors(data["errors"]))

                });
        }

        function remove(e){
            e.preventDefault()
            let id = e.target.getAttribute("data-id")
            fetch(document.querySelector("#deleteUrl").value, {
                method: "DELETE",
                credentials: "include",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({"id": id})
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.hasOwnProperty('errors')) {
                        alert("Успешно")
                        document.location.reload()
                        return
                    }

                    alert(renderErrors(data["errors"]))

                });
        }
    </script>
@endpush
