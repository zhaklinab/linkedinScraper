<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

        <!-- Custom styles for this template -->
        <link href="{{asset('css/resume.min.css')}}" rel="stylesheet">
        <!-- Bootstrap core CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
        <!-- Styles -->
       <style>
            .button-style{
                background-color: #bd5d38;
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
            }

        </style>
    </head>
    <body>
        <div class="container-fluid">
           <div class="col-md-10" style="margin-top: 150px;">
               <center>
                    <h1 style="font-size: 100px; color: #bd5d38;">
                       <span>Linkedin Scraper</span>
                    </h1>
               </center>
            </div>

            <div class="col-md-10">
               <form action="{{url('api/scraper')}}" method="post">
                    <div class="form-group">
                        <input name="url" class="form-control" type="text" placeholder="Enter linkedin profile url" required/>
                    </div>
                    <div class="form-group">
                       <center><button class="btn button-style" type="submit">Go scraper</button></center>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
