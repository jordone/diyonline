<nav class="navbar-fixed-top navbar-static-top navbar navbar-default" style="margin:0px;">
    <div class="container" style="margin-top:0;">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#officemenu" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{URL::to('office')}}">{{COMPANY_NAME}}</a>
        </div>

        <div id="officemenu" class="collapse navbar-collapse">
            <?php
                $links = ['office' => 'Client Lookup', 'office/recent' => 'View Recent Clients', 'office/merchant' => 'Register New Client'];
                $CurrentRouteURL = Route::getCurrentRoute()->getUri();
            ?>

            <ul class="nav navbar-nav">
                @foreach($links as $linkuri => $linkname)
                   <li class="{{$CurrentRouteURL == $linkuri ? "active" : ""}}"><a href="{{URL::to($linkuri)}}">{{$linkname}}</a></li>
                @endforeach
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="active">
                   <a href="{{URL::to('office/logout')}}">Logout</a>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>