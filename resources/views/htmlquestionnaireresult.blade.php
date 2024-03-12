<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>HM-Wellness Questionnaire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('questinnaires/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('questinnaires/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('questinnaires/css/fontawesome-all.css') }}">
    <link rel="stylesheet" href="{{ asset('questinnaires/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('questinnaires/css/colors/switch.css') }}">
    <!-- Color Alternatives -->
    <link href="{{ asset('questinnaires/css/colors/color-2.css') }}" rel="alternate stylesheet" type="text/css" title="color-2">
    <link href="{{ asset('questinnaires/css/colors/color-3.css') }}" rel="alternate stylesheet" type="text/css" title="color-3">
    <link href="{{ asset('questinnaires/css/colors/color-4.css') }}" rel="alternate stylesheet" type="text/css" title="color-4">
    <link href="{{ asset('questinnaires/css/colors/color-5.css') }}" rel="alternate stylesheet" type="text/css" title="color-5">
</head>

<body class="hmwellness-questionnaire">
    @php
    $id=request()->route('id');
    $user_id=request()->route('user_id');
    $dt=DB::table('html_questionnaires')->where('id',$id)->first();
    $result=DB::table('html_questionnaire_users')->where('questionnaire_id',$id)->where('user_id',$user_id)->first();
    $result=(!empty($result->result)) ? json_decode($result->result,TRUE):null;
    @endphp
    @include('html_questionnaires_result.'.$dt->slug,['user_id'=>$user_id,'result'=>$result])
    <script src="{{ asset('questinnaires/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('questinnaires/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('questinnaires/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('questinnaires/js/main.js') }}"></script>
    <script src="{{ asset('questinnaires/js/switch.js') }}"></script>
    <script>
        $("#files").change(function() {
            filename = this.files[0].name
            // console.log(filename);
        });

        function UploadFile() {
            var reader = new FileReader();
            var file = document.getElementById('attach').files[0];
            reader.onload = function() {
                document.getElementById('fileContent').value = reader.result;
                document.getElementById('filename').value = file.name;
                document.getElementById('wizard').submit();
            }
            reader.readAsDataURL(file);
        }
    </script>
</body>
<style>
    p {
        color: blue !important;
    }

    p.color-norm {
        color: #333 !important;
    }

    .color-red {
        color: blue !important;
    }
</style>

</html>