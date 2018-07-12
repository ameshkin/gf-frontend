<div class="admin-wrap">
    <div class="postbox-container">
        <div  class="postbox ">
            <h2 class="gf-header">
                <span>General Info</span>
            </h2>

            <div class="inside">
                <div class="main">
                    <p>We can add general information about this plugin here: </p>
                    <p>/wp-content/plugins/gf-frontend/templates/admin/help.php</p>
                    <p>Screen prints coming soon!</p>
                </div>
            </div>

        </div>

        <div class="postbox ">
            <h2 class="gf-header">
                <span>Quick Links</span>
            </h2>
            <div class="inside">
                <div class="main">
                    <ul>
                        <li><a href="/wp-admin/admin.php?page=gf_edit_forms"><?php echo $num_forms; ?> Forms</a></li>
                        <li><a href="/wp-admin/admin.php?page=gf_frontend-settings">Plugin Settings</a></li>
                        <li><a href="/wp-admin/admin.php?page=gf_frontend-help">Plugin Help</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="postbox-container">

        <div  class="postbox ">
            <h2 class="gf-header">
                <span>API Endpoints</span>
            </h2>

            <div class="inside">
                <div class="main">
                    <ul class="vertical">
                        <li>
                            <h4>Get All Forms</h4>
                            <p>/wp-json/gf-frontend/v1/forms</p>
                        </li>
                        <li>
                            <h4>Get One Form</h4>
                            <p>/wp-json/gf-frontend/v1/form/:id</p>
                        </li>
                    </ul>
                    <div class="sub">
                        <p>These API endpoints are not used at the moment. We can use an external api to get data from one site and display it in on others.</p>
                    </div>
                </div>
            </div>

        </div>


        <div  class="postbox ">
            <h2 class="gf-header">
                <span>Shortcodes</span>
            </h2>

            <div class="inside">
                <div class="main">

                    <p>As of now there is only one shortcode, that is very similar to the gravity forms shortcode.</p>
                    <div class="sub">
                        <p>
                            [gravityform_frontend id=2 title="override title" description="desc override" ajax=true tabindex=49]
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
