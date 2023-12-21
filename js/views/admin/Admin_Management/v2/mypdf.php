<!DOCTYPE html>
<?
    if ( !isset($bg_url) ) {
        $bg_url = FCPATH;
    }
?>
<html>
<head>
	<title>Test</title>
    <style>
        @page { margin: 0px; }
        body { margin: 0px; }
        .background {
            position: absolute;
            z-index: 1;
            width: 100vw;
            height: auto;
            left: 0;
            top: 0;
        }
        .bg {
            width: 100%;
            height: auto;
            left: 0px;
            top: 0px;
            float:left;
        }
        .inv-number {
            position: absolute;
            z-index: 9;
            top: -120%;
            left: 20%;
            color: green;
            font-size: 22px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="background">
    <img class="bg" src='<? echo $bg_url ?>asset/pdf/template.jpg'></img>
</div>
<div class="inv-number">241017
</div>


</body>
</html>
