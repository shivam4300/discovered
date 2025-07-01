<html>
    <head></head>
    <body>
        <div>
            <center>
                <?php   
                    if(isset($error)) { 
                            echo '<h1 style="color:red;">'.$error.'</h1>';
                    }else if(isset($file_name)){
                        echo '<h1 style="color:green;"> File Upload Suuceesfully :- '.$file_name.'</h1>';
                    } 
                ?>
                <h1>Select File To Upload</h1>
                <form method="post" action="magnite_report_upload" enctype="multipart/form-data">
                    <input type="file" name="report_file">
                    <br>
                    <br>
                    <br>
                    <br>
                    <h1><button type="submit">Submit</button></h1>
                </form>
            </center>
        </div>
    </body>
<html>