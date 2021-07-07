<header>
    <h1>Расценщик</h1>
    <ul class="menu">
        @isset($user)
            <li><a href="/">Главная</a></li>
            @if($user->role == "admin")
            <li>
                <a href="{{ route("catalogs.show") }}">Каталоги</a>
                <ul>
                    <li><a href="{{ route("catalogs.choosePrint") }}">Распечатать</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route("products.index") }}">Продукты</a>
                <ul>
                    <li><a href="{{ route("products.create") }}">Создать товар</a></li>
                    <li><a href="{{ route("products.importPage") }}">Загрузить товары</a></li>
                    <li><a href="{{ route("products.search")  }}">Найти товар</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route("products.showAppraisings") }}">Заявки</a>
                <ul>
                    <li><a href="{{ route("products.appraisePage") }}">Расценить заявку</a></li>
                </ul>
            </li>
            <li><a href="{{ route("users.index") }}">Пользователи</a></li>
            <li><a href="{{ route("logout") }}">Выйти</a></li>
            @else
                <li><a href="{{ route("products.appraisePage") }}">Расценить заявку</a></li>
                <li><a href="{{ route("logout") }}">Выйти</a></li>
            @endif
        @else
            <li><a href="{{ route("login") }}">Войти</a></li>
            <li><a href="{{ route("getRegisterPage") }}">Регистрация</a></li>
        @endisset
    </ul>
</header>
