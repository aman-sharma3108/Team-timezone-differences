<!DOCTYPE html>
<html lang="en">
<head>
<title>View CSV</title>

<meta charset="utf-8" />
<meta name="description" content="Product form"  />
<meta name="keywords" content="Form, Input" />
<link href= "styles/style.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css%22%3E"/>
<script src="js/csv.js"></script>
<script src="js/scripts.js"></script>

</head>
<body>
    <?php
        include "./includes/header.inc";
        include "./includes/nav.inc";
    ?>
    
    <script type="text/javascript">
    function Upload() {
        var fileUpload = document.getElementById("fileUpload");
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;
        //if (regex.test(fileUpload.value.toLowerCase())) {   //this line is for testing file input name
            if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var table = document.createElement("table");
                    var rows = e.target.result.split("\n");
                    for (var i = 0; i < rows.length; i++) {
                        var cells = rows[i].split(",");
                        if (cells.length > 1) {
                            var row = table.insertRow(-1);
                            for (var j = 0; j < cells.length; j++) {
                                var cell = row.insertCell(-1);
                                cell.innerHTML = cells[j];
                            }
                        }
                    }
                    var dvCSV = document.getElementById("dvCSV");
                    dvCSV.innerHTML = "";
                    dvCSV.appendChild(table);
                }
                reader.readAsText(fileUpload.files[0]);
            } else {
                alert("This browser does not support HTML5.");
            }
        //} else {
        //    alert("Please upload a valid CSV file.");
        //}
    }
    </script>
    <input type="file" id="fileUpload" />
    <input type="button" id="upload" value="Upload" onclick="Upload()" />
    <hr />
    <div id="dvCSV">
    </div>


    <?php
        include "./includes/footer.inc";
    ?>
</body>
</html>
