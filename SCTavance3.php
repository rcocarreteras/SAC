<!DOCTYPE html>
<html lang="es">
<head>
    <title id='Description'>The sample shows how to toggle sub groups in jqxGrid.</title>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.grouping.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var url = "jqwidgets/customers.xml";
            // prepare the data
            var source =
            {
                datatype: "xml",
                datafields: [
                    { name: 'CompanyName', map: 'm\\:properties>d\\:CompanyName', type: 'string' },
                    { name: 'ContactName', map: 'm\\:properties>d\\:ContactName', type: 'string' },
                    { name: 'ContactTitle', map: 'm\\:properties>d\\:ContactTitle', type: 'string' },
                    { name: 'City', map: 'm\\:properties>d\\:City', type: 'string' },
                    { name: 'PostalCode', map: 'm\\:properties>d\\:PostalCode', type: 'string' },
                    { name: 'Country', map: 'm\\:properties>d\\:Country', type: 'string' }
                ],
                root: "entry",
                record: "content",
                id: 'm\\:properties>d\\:CustomerID',
                url: url
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            // Create jqxGrid
            $("#jqxgrid").jqxGrid(
            {
                width: 850,
                source: dataAdapter,
                groupable: true,
                columns: [
                  { text: 'Company Name', datafield: 'CompanyName', width: 250 },
                  { text: 'City', datafield: 'City', width: 120 },
                  { text: 'Country', datafield: 'Country' }
                ],
                groups: ['Country', 'City']
            });
            $("#expand").jqxButton({ theme: theme });
            $("#collapse").jqxButton({ theme: theme });
            $("#expandall").jqxButton({ theme: theme });
            $("#collapseall").jqxButton({ theme: theme });
            // expand group.
            $("#expand").on('click', function () {
                var groupnum = $("#groupnum").val();
                $("#jqxgrid").jqxGrid('expandgroup', groupnum);
            });
            // collapse group.
            $("#collapse").on('click', function () {
                var groupnum = $("#groupnum").val();
                $("#jqxgrid").jqxGrid('collapsegroup', groupnum);     
            });
            // expand all groups.
            $("#expandall").on('click', function () {
                $("#jqxgrid").jqxGrid('expandallgroups');
            });
            // collapse all groups.
            $("#collapseall").on('click', function () {
                $("#jqxgrid").jqxGrid('collapseallgroups');
            });
            // trigger expand and collapse events.
            $("#jqxgrid").on('groupexpand', function (event) {
                var args = event.args;
                $("#expandedgroup").text("Group: " + args.group + ", Level: " + args.level);
            });
            $("#jqxgrid").on('groupcollapse', function (event) {
                var args = event.args;
                $("#collapsedgroup").text("Group: " + args.group + ", Level: " + args.level);
            });
        });
    </script>
</head>
<body class='default'>
    <div id='jqxWidget' style="font-size: 13px; font-family: Verdana; float: left;">
        <div id="jqxgrid">
        </div>
        <div style="margin-top: 30px;">
            <div style="float: left; margin-left: 20px;">
                <input value="Expand Group" type="button" id='expand' />
                <br />
                <input style="margin-top: 10px;" value="Collapse Group" type="button" id='collapse' />
                <br />
                <span style="margin-top: 10px;">Group:</span>
                <input value="0.0" id="groupnum" style="margin-top: 10px; width: 20px;" type="text" />
            </div>
            <div style="float: left; margin-left: 20px;">
                <input value="Expand All Groups" type="button" id='expandall' />
                <br />
                <input style="margin-top: 10px; margin-bottom: 10px;" value="Collapse All Groups"
                    type="button" id='collapseall' />
                <br />
            </div>
            <div style="float: left; margin-left: 20px;">
                <div style="font-weight: bold;">
                    <span>Event Log:</span>
                </div>
                <div style="margin-top: 10px;">
                    <span>Expanded Group:</span> <span id="expandedgroup"></span>
                </div>
                <div style="margin-top: 10px;">
                    <span>Collapsed Group:</span> <span id="collapsedgroup"></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>