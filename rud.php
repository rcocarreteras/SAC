<!DOCTYPE html>
<html>
<head>
    <meta name="keywords" content="jQuery Splitter, Splitter Widget, Splitter, jqxSplitter" />
    <meta name="description" content="This page demonstrates splitter's events" />
    <title id='Description'>RUD </title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxsplitter.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxpanel.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#mainSplitter').jqxSplitter({ width: 850, height: 480, panels: [{ size: 300 }] });
        });
    </script>
</head>
<body class='default'>
    <div id='jqxWidget'>
        <div id="mainSplitter">
            <div class="splitter-panel">
                Panel 1</div>
            <div class="splitter-panel">
                Panel 2</div>
        </div>
    </div>
</body>
</html>