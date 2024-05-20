<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Program :: {{ $package->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('questinnaires/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('questinnaires/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('questinnaires/css/fontawesome-all.css') }}">
    <link rel="stylesheet" href="{{ asset('questinnaires/css/style.css') }}">
    <style type="text/css">
        .shadow-md {
            box-shadow: 1px -1px 8px 1px #ccc;
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
            margin-top: 1rem;
            margin-block: 1rem;
        }

        .text-justify {
            text-align: justify;
        }

        .featured-image {
            padding-bottom: 2rem;
        }


        @media (min-width: 1281px) { 
            .dpt-10 {
                margin-top: 6rem;
            }

            .dpt-10 img {
                margin-right: auto;
                margin-left: auto;
                display: block;
                max-height: 100px;
            }
        }

        @media (min-width: 1025px) and (max-width: 1280px) {
            .dpt-10 {
                margin-top: 6rem;
            }

            .dpt-10 img {
                margin-right: auto;
                margin-left: auto;
                display: block;
            }
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            .dpt-10 {
                margin-top: 6rem;
            }

            .dpt-10 img {
                margin-right: auto;
                margin-left: auto;
                display: block;
            }
        }
        
    </style>
</head>

<body class="container shadow-md">
    <div class="row">
        <div class="col-md-4 col-lg-4 col-sm-4">
            <div class="featured-image">
                <img src="/view/file?path=program/images/{{ $package->image }}" class="img-fluid img-responsive">
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="page-header">
                <h4>{{ $package->title }}</h4>
                <br>
            </div>
            <div class="content text-justify">
                {!! $package->description !!}                
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 2rem;">
        <div class="col-md-3">
            <a href="javascript:void();"><img src="https://i2.wp.com/biasharacommsacco.co.ke/wp-content/uploads/2017/05/2884758_orig.gif?resize=434%2C195" style="max-height: 100px;"></a>
        </div>
        <div class="col-md-3">
            <a href="javascript:void();"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSc5RUTsCupdTivrbfl_aE6LmFVTfNfl6-mug&usqp=CAU" style="max-height: 100px;"></a>
        </div>
    </div>
</body>

</html>