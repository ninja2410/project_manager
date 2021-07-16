<section class="content-header">
    <h1>@yield('title')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="/">
                <i class="livicon" data-name="home" data-size="16" data-color="#000"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#">@yield('page_parent')</a>
        </li>
        <li class="active">@yield('title')</li>
    </ol>
</section>
