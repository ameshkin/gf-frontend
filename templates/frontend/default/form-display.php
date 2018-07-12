<?php

/*
Template Name: Default Template
Description: Create front end themes for gravity forms
Slug: default
Version: 1.0
Author: Amir Meshkin
Author URI: http://amir-meshkin.com
*/


?>



<div class="row">
    <div class="col-md-12">

        <h1><?php echo $title; ?></h1>
        <p><?php echo $description; ?></p>

        <textarea id="code1">
            <?php echo json_encode($form,JSON_PRETTY_PRINT); ?>
        </textarea>
        <script>
          jQuery(document).ready(function() {
            CodeMirror.fromTextArea(document.getElementById('code1'), {
              lineNumbers: true,
              matchBrackets: true,
              styleActiveLine: true,
              theme:"tomorrow-night-bright"
            });
          });
        </script>

    </div>
</div>
