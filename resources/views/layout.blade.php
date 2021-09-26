<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <title>@yield('title')</title>
    <style>
        .container {
            padding: 1% 0;
        }

        tr td span {
            margin-right: 5px;
            display: inline-block;
        }
        .EX, .EW, .CR, .EN, .VU, .CD, .NT, .LC, .DD, .NE {
            padding: 1%;
            color: white;
            text-align: center;
        }

        .EX {
            color:red;
            background:black;
        }
        
        .EW {
            color: white;
            background: black;
        }

        .CR {
            background: Maroon;
        }

        .EN {
            background: Coral;
        }

        .NT {
            background: darkgreen;
        }

        .LC {
            background: DarkCyan;
        }

        .VU {
            background: GoldenRod;
        }

        .DD {
            color: black;
            border: grey 1px solid;
        }

        img.tree {
            width: 20px;
            height: 15px;
            object-fit: cover;
        }

        table tr td {
	    white-space: nowrap;
	    vertical-align: middle;
        }

        table tr th {
            vertical-align: middle;
        }

        table#taxa tr td img {
            width: 98px;
            height: 68px;
            object-fit: cover;
        }

        .mw-editsection {
            display: none;
        }

        .infobox {
            border: 1px solid #a2a9b1;
            background-color: #f8f9fa;
            color: black;
            margin: 0.5em 0 0.5em 1em;
            padding: 0.2em;
            float: right;
            clear: right;
            text-align: left;
            font-size: 88%;
            line-height: 1.5em;
        }
    </style>
</head>

<body>
    <div class="container">
        @auth
            <div style="text-align: right"><a href="/logout">logout</a></div>
        @else
            <div style="text-align: right"><a href="/login">login</a></div>
        @endauth
        <form action="/" method="GET" class="form-inline" style="margin-bottom: 1%">
            <div class="input-group">
                <input type="text" class="form-control" value="{{ $name ?? '' }}" name="name"
                    placeholder="Scientific Name" />
                <input type="text" class="form-control" value="{{ $jp ?? '' }}" name="jp" placeholder="Japanese" />
                <input type="text" class="form-control" value="{{ $en ?? ''  }}" name="en" placeholder="English" />
                <input type="text" class="form-control" value="{{ $rank ?? '' }}" name="rank"
                    placeholder="Taxon Rank" />
                <button class="btn btn-success" type="submit">find</button>
            </div>
        </form>
        @yield('content')
    </div>
    <script src="/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous">
    </script>
</body>

</html>
